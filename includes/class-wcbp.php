<?php
/**
 * WooCommerce Breadcrumb Permalinks Class
 *
 * @package   WooCommerce Breadcrumb Permalinks
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WCBP Class
 *
 * @package  WooCommerce Breadcrumb Permalinks
 * @author   Captain Theme <info@captaintheme.com>
 * @since    1.0.0
 */

class WCBP {

	const VERSION = '1.0.0';

	protected $plugin_slug = 'woocommerce-breadcrumb-permalinks';

	protected static $instance = null;

	private function __construct() {

		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'nag_ignore' ) );
		add_action( 'init', array( $this, 'rewrites_init' ) );
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 1, 3 );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Start the Class when called
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.0.0
	 */

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	/**
	 * Admin Notice on Plugin Activation
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.0.0
	 */

	public function admin_notice() {

    	global $current_user;
        $user_id = $current_user->ID;

    	if ( ! get_user_meta( $user_id, 'wcbp_ignore_notice' ) ) {
            if ( current_user_can( 'publish_posts' ) ) {
                echo '<div class="updated"><p><strong><a href="' . get_admin_url() . 'options-permalink.php' . '">';
                _e( 'Please re-save your permalinks!', 'woocommerce-breadcrumb-permalinks' );
                echo '</a></strong></p></div>';
            }
    	}

    }

	public function nag_ignore() {

    	global $current_user;
        $user_id = $current_user->ID;
            
        if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
             add_user_meta( $user_id, 'wcbp_ignore_notice', 'true', true );
        }

    }

    /**
	 * Add permalinks settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-permalink.php' ) . '">' . __( 'Permalinks', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}


    /**
	 * Rewrite Permalinks
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.0.0
	 */

	public function post_type_link( $link, $post = 0 ) {

		global $product;
		
		$wcbp_base_setting = get_option( 'wcbp_permalinks_base' );

		if ( $wcbp_base_setting ) {
			$wcbp_base = get_option( 'wcbp_permalinks_base' );
		} else {
			$wcbp_base = 'shop';
		}

	    if ( $post->post_type == 'product' ){

	    	if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {

				$main_term = $terms[0];

				$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );

				$ancestors = array_reverse( $ancestors );

				$the_ancestor_slug = '';

				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, 'product_cat' );

					if ( ! is_wp_error( $ancestor ) && $ancestor )
						$the_ancestor_slug = $ancestor->slug;
				}

				return home_url( $wcbp_base . '/' . $the_ancestor_slug . '/' . $main_term->slug . '/' . $post->post_name );

			} else {
				return $link;
			}

	    } else {
	        return $link;
	    }

	}

	public function rewrites_init() {

	    add_rewrite_rule (
	        'product/([0-9]+)?$',
	        'index.php?post_type=product&p=$matches[1]',
	        'top'
	    );

	}

}