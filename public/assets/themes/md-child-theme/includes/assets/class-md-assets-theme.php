<?php
/**
 * @trait     Assets\Admin
 * @Version: 0.0.1
 * @package   MySiteDigital/Assets
 * @category  Class
 * @author    MySite Digital
 */
namespace MySiteDigital\Assets;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Class.
 */
class Theme {

    use AssetsTrait;

    protected $frontend_styles = [
        'type' => 'theme',
        'handle' => 'simple-dining',
        'src' => 'theme.css',
        'post_types' => [ 'all' ],
    ];
    
    protected $frontend_scripts = [
        'type' => 'theme',
        'handle' => 'simple-dining',
        'src' => 'theme.js',
        'post_types' => [ 'all' ],
    ];

    public function __construct() {
       $this->init();
    }
}

new Theme();

