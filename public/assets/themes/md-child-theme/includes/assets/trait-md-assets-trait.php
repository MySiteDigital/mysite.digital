<?php
/**
 * @trait     Assets\AssetsTrait
 * @Version: 1.0.0
 * @package   MySiteDigital/Assets
 * @category  Trait
 * @author    MySite Digital
*/

namespace MySiteDigital\Assets;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! trait_exists ( 'MySiteDigital\Assets\AssetsTrait' ) ) {
    trait AssetsTrait {

        public function init(){
            add_action( 'init', [ $this , 'register_styles_and_scripts' ] );
            add_action( 'enqueue_block_editor_assets', [ $this , 'enqueue_block_editor_assets' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_styles' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
        }

        public function register_styles_and_scripts() {

            if( property_exists( self::class, 'editor_styles' ) ){
                wp_register_style(
                    $this->editor_styles[ 'handle' ],
                    $this->get_asset_location( $this->editor_styles['src'] ),
                    [ 'wp-edit-blocks' ],
                    self::get_asset_version( $this->editor_styles['src'] )
                );
            }

            if( property_exists( self::class, 'editor_scripts' ) ){
                $test = wp_register_script(
                    $this->editor_scripts[ 'handle' ],
                    $this->get_asset_location( $this->editor_scripts['src'] ),
                    [ 'wp-blocks', 'wp-i18n', 'wp-element' ],
                    self::get_asset_version( $this->editor_scripts['src'] ),
                    true
                );
            }

            if( property_exists( self::class, 'frontend_styles' ) ){
                wp_register_style(
                    $this->frontend_styles[ 'handle' ],
                    $this->get_asset_location( $this->frontend_styles['src'], $this->frontend_styles['type'] ),
                    [],
                    self::get_asset_version( $this->frontend_styles['src'], $this->frontend_styles['type'] )
                );
            }

            if( property_exists( self::class, 'frontend_scripts' ) ){
                
                $script = $this->get_asset_location( $this->frontend_scripts['src'], $this->frontend_styles['type'] );

                if( $this->is_webpack_dev_server() ){
                    $script = 'http://localhost:3000/' . $this->frontend_scripts['src'];
                }

                wp_register_script(
                    $this->frontend_scripts[ 'handle' ],
                    $script,
                    [ 'jquery' ],
                    self::get_asset_version( $this->frontend_scripts['src'], $this->frontend_styles['type'] ),
                    true
                );
            }

            if( property_exists( self::class, 'admin_styles' ) ){
                wp_register_style(
                    $this->admin_styles[ 'handle' ],
                    $this->get_asset_location( $this->admin_styles['src'] ),
                    [],
                    self::get_asset_version( $this->admin_styles['src'] )
                );
            }

            if( property_exists( self::class, 'admin_scripts' ) ){
                wp_register_script(
                    $this->admin_scripts[ 'handle' ],
                    $this->get_asset_location( $this->admin_scripts['src'] ),
                    [ 'jquery' ],
                    self::get_asset_version( $this->admin_scripts['src'] ),
                    true
                );
            }
        }

        public function enqueue_block_editor_assets(){
            global $post;

            if( property_exists( self::class, 'editor_styles' ) ){
                if( in_array( $post->post_type, $this->editor_styles[ 'post_types' ] ) ){
                    wp_enqueue_style( $this->editor_styles[ 'handle' ] );
                }
            }

            if( property_exists( self::class, 'editor_scripts' ) ){
                if( in_array( $post->post_type, $this->editor_scripts[ 'post_types' ] ) ){
                    wp_enqueue_script( $this->editor_scripts[ 'handle' ] );
                }
            }

        }

        public function enqueue_frontend_styles(){
            if( $this->is_webpack_dev_server() ){
                return;
            }

            if( property_exists( self::class, 'frontend_styles' ) ){
                if( $this->can_enqueue( $this->frontend_styles[ 'post_types' ] ) ){
                    wp_enqueue_style( $this->frontend_styles[ 'handle' ] );
                }
            }
        }

        public function enqueue_frontend_scripts(){
            if( property_exists( self::class, 'frontend_scripts' ) ){
                if( $this->can_enqueue( $this->frontend_scripts[ 'post_types' ] ) ) {
                    wp_enqueue_script( $this->frontend_scripts[ 'handle' ] );
                }
            }
        }

        public function can_enqueue( $post_types_array ){
            global $post;
            $valid_post_type = is_object( $post ) && in_array( $post->post_type, $post_types_array );
            $show_for_all = in_array( 'all', $post_types_array ) ||  is_404(); 
            return ( $valid_post_type  || $show_for_all );
        }

        public function admin_styles() {
            if( property_exists( self::class, 'admin_styles' ) ){
                wp_enqueue_style( $this->admin_styles[ 'handle' ] );
            }
        }
    
        public function admin_scripts() {
            if( property_exists( self::class, 'admin_scripts' ) ){
                wp_enqueue_script( $this->admin_scripts[ 'handle' ] );
            }
        }

        public function get_asset_location( $filename, $type = '', $dir = false ){
            
            $base = self::base_url( $type );


            if( $dir ){
                $base = self::base_path( $type );
            }

            if( self::is_plugin( $type ) ){
                $base = str_replace( '/includes/assets', '', $base );
            }


            $type = substr( strrchr( $filename, '.' ), 1 );

            $asset_location = $base . '/assets/' . $type . '/' . $filename;

            return $asset_location;
            
        }


        public static function get_asset_version( $filename, $type = ''){
            return @filemtime( self::get_asset_location( $filename, $type, true ) );
        }

        public static function base_path( $type ){
            return self::is_plugin( $type ) ? plugin_dir_path( __FILE__ ) : get_stylesheet_directory();
        }

        public static function base_url( $type ){
            return self::is_plugin( $type ) ? plugin_dir_url( __FILE__ ) : get_stylesheet_directory_uri();;
        }

        public static function is_plugin( $type ){
            return $type === 'theme' ? false : true;
        }

        public function is_webpack_dev_server(){
            if( ! defined( 'WP_ENV' ) || WP_ENV !== 'dev' ){
                return false;
            }
            $socket = false;
            $socket = @fsockopen( 'localhost', 3000, $errno, $errstr, 1 );

            return $socket ? true : false;
        }
    }
}
