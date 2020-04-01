<?php
/**
 * @class     WooCommerce\EmailExtensions
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
 * EmailExtensions Class.
 */
class EmailExtensions {



    public function __construct()
    {
        add_filter( 'woocommerce_email_subject_customer_invoice', [ $this, 'change_email_subject' ], 1, 2 );
    }

    public function custom_email_subject( $subject, $order ) {
        $invoice_title = get_post_meta( $order->get_id(), '_invoice_email_title', true );
        if( $invoice_title ) {
            $subject = $invoice_title;
        }
        return $invoice_title;
    }
}

new EmailExtensions();
