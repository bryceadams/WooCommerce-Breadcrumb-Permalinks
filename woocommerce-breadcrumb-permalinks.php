<?php
/**
 * Plugin Name: WooCommerce Breadcrumb Permalinks
 * Plugin URI: http://bryceadams.com/
 * Description: Allows for WC permalinks to have breadcrumb ancestory, including parent & multiple child categories.
 * Version: 1.1.0
 * Author: Bryce Adams
 * Author URI: http://bryceadams.com/
 * License: GPL2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This plugin only supports PHP 5.4.0.
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/lib/WPUpdatePhp.php' );
$updatePhp = new WPUpdatePhp( '5.4.0' );

if ( $updatePhp->does_it_meet_required_php_version( PHP_VERSION ) ) {

    /**
	 * Check if WooCommerce is active
	 **/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	    
		// Brace Yourself
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-wcbp.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-wcbp-settings.php' );

		// Start the Engine
		add_action( 'plugins_loaded', array( 'WCBP', 'get_instance' ) );
		add_action( 'plugins_loaded', array( 'WCBP_Settings', 'get_instance' ) );

	}

}