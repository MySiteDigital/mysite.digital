<?php
/**
 * Plugin Name: My Site
 * Description: Unique functionality for http://mysite.labcat.nz
 * Version: 1.0.0
 * Author: LABCAT
 * Author URI: http://mysite.labcat.nz
 * Requires at least: 5.3
 * Tested up to: 5.3
 */
namespace MySiteDigital;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Main MySite Class.
 *
 * @class MySite
 * @version    1.0.0
 */

final class MySite {

    /**
     * MySite Constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
    }

    /*
        *
        * Define MySite Constants.
        */
    private function define_constants() {
        if ( ! defined( 'MD_PLUGIN_PATH' ) ) {
            define( 'MD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        if ( ! defined( 'MD_PLUGIN_URL' ) ) {
            define( 'MD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes()
    {
        include_once( MD_PLUGIN_PATH . 'includes/woocommerce/class-checkout-extensions.php' );
        include_once( MD_PLUGIN_PATH . 'includes/woocommerce/class-email-extensions.php' );
        include_once( MD_PLUGIN_PATH . 'includes/woocommerce/class-order-extensions.php' );
    }

}

new MySite();
