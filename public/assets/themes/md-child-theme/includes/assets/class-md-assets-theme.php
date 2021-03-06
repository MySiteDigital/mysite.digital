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
        'handle' => 'mysite',
        'src' => 'theme.css',
        'deps' => ['parent-styles'],
        'post_types' => [ 'all' ],
    ];
    
    protected $frontend_scripts = [
        'type' => 'theme',
        'handle' => 'mysite',
        'src' => 'theme.js',
        'post_types' => [ 'all' ],
    ];

    public function __construct() {
       $this->init();
    }
}

new Theme();

