<?php
/**
 * Plugin Name: Yivic Password Protected Video Pro
 * Plugin URI:  https://yivic.net/
 * Description: Secure your videos with Password Protected Video Pro. Makes it easy for you to secure your video with a password set and requires viewers to do something to get the password to unlock your video.
 * Author:      manhphucofficial@yahoo.com
 * Author URI:  https://yivic.net/
 * Version:     1.0.0
 * Text Domain: yivic
 */

use Yivic\Wp\Plugin\YivicPpvp;

defined( 'YIVIC_PPVP_VERSION' ) || define( 'YIVIC_PPVP_VERSION', '1.0.0' );

// Use autoload if it isn't loaded before
// phpcs:ignore PSR2.ControlStructures.ControlStructureSpacing.SpacingAfterOpenBrace
if ( ! class_exists(YivicPpvp::class)) {
    require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}

$config = require( __DIR__ . DIRECTORY_SEPARATOR . 'config.php' );
$config = array_merge( $config, [
    'pluginFilename' => __FILE__,
] );

// We need to set up the main instance for the plugin.
// Use 'init' event but with low (<10) processing order to be able to execute before -> able to add other init.
add_action( 'init', function () use ( $config ) {
    YivicPpvp::initInstanceWithConfig( $config );

    register_activation_hook( __FILE__, [ YivicPpvp::getInstance(), 'activatePlugin' ] );
    register_deactivation_hook( __FILE__, [ YivicPpvp::getInstance(), 'deactivatePlugin' ] );

    YivicPpvp::getInstance()->initPlugin();
}, 7 );
