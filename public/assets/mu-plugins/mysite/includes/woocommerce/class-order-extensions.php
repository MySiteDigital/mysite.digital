<?php
/**
 * @class     WooCommerce\OrderExtensions
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
 * OrderExtensions Class.
 */
class OrderExtensions {



    public function __construct()
    {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_invoice_email_title_field' ], 10, 1 );
    }

    public function add_meta_boxes()
    {
        add_meta_box( 'invoice-title', 'Invoice Email Title', [ $this, 'invoice_email_title_field' ], 'shop_order', 'normal', 'core' );
    }

    public function invoice_email_title_field(){
        global $post;

        $meta_field = get_post_meta( $post->ID, '_invoice_email_title', true ) ? get_post_meta( $post->ID, '_invoice_email_title', true ) : '';

        echo    '<div id="titlediv"  style="display:block;">
                    <input type="hidden" name="invoice_email_title_nonce" value="' . wp_create_nonce() . '">
                    <input type="text" name="invoice_email_title" size="30"  id="title" spellcheck="true" autocomplete="off"  value="' . $meta_field . '">
                </div>';
    }

    public function save_invoice_email_title_field( $post_id ){
        if ( ! isset( $_POST[ 'invoice_email_title' ] ) ) {
            return $post_id;
        }

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_REQUEST[ 'invoice_email_title_nonce' ] ) ) {
            return $post_id;
        }

        

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }


        update_post_meta( $post_id, '_invoice_email_title', $_POST[ 'invoice_email_title' ] );
    }

}

new OrderExtensions();
