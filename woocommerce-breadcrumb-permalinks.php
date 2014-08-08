<?php
/**
 * Plugin Name: WooCommerce Breadcrumb Permalinks
 * Plugin URI: http://captaintheme.com/
 * Description: Allows for WC permalinks to have breadcrumb ancestory, including parent & child categories.
 * Version: 1.0.0
 * Author: Captain Theme
 * Author URI: http://captaintheme.com/
 * License: GPL2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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