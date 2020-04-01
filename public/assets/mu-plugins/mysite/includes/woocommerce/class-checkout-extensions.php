<?php
/**
 * @class     WooCommerce\CheckoutExtensions
 * @Version: 0.0.1
 * @package   MySiteDigital\PostTypes
 * @category  Class
 * @author    MySiteDigital
 */
namespace MySiteDigital\WooCommerce;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CheckoutExtensions Class.
 */
class CheckoutExtensions {



    public function __construct()
    {
        add_filter( 'woocommerce_available_payment_gateways', [ $this, 'update_payment_gateways' ], 999, 3 );
    }

    public function update_payment_gateways( $available_gateways ) {
        if ( is_checkout() && is_wc_endpoint_url( 'order-pay' ) ) {
            // If paying from order, we need to get total from order not cart.
    		if ( isset( $_GET['pay_for_order'] ) && isset( $_GET['pay_by_credit'] ) && ! empty( $_GET['key'] ) ) {
    			$order = wc_get_order( wc_get_order_id_by_order_key( wc_clean( $_GET['key'] ) ) );
                $this->add_credit_card_fee( $order );
                //lets make sure there is a fee
                if( $this->has_credit_card_fee( $order ) ){
                    unset( $available_gateways['bacs'] );
                }
                else {
                    unset( $available_gateways['stripe'] );
                }
    		}
            else {
                unset( $available_gateways['stripe'] );
            }
        }
        return $available_gateways;
    }

    public static function has_credit_card_fee( $order ){
        if( count( $order->get_items('fee') ) ) {
            return true;
        }

        return false;
    }

    public static function add_credit_card_fee( $order, $includes_tax = true, $fee_tax_status = 'taxable' ){
            if( count( $order->get_items('fee') ) ) {
                //this order already has a Fee
                return;
            }

            $sub_total = $order->get_subtotal();
            $fee_amount = $sub_total * 0.03;

            if( ! count( $order->get_items('tax') ) ) {
                //if there is no tax
                $includes_tax = false;
                $fee_tax_status = 'none';
            }
            // Get a new instance of the WC_Order_Item_Fee Object
            $cc_fee = new \WC_Order_Item_Fee();
            $cc_fee->set_name( "Credit Card Processing Fee" ); // Generic fee name
            $cc_fee->set_amount( $fee_amount ); // Fee amount
            $cc_fee->set_total( $fee_amount ); // Fee amount
            $cc_fee->set_tax_status( $fee_tax_status ); // or 'none'

            $order->add_item( $cc_fee );
            $order->calculate_totals( $includes_tax );

            if( ! $includes_tax ){
                $order->set_cart_tax( 0 );
            }

            $order->save();

            //hack to make the details on the page update
            global $wp;
            wp_redirect( add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ) );
            exit;
        }
}

new CheckoutExtensions();
