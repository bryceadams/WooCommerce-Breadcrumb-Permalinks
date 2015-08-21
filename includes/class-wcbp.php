<?php
/**
 * WooCommerce Breadcrumb Permalinks Class
 *
 * @package   WooCommerce Breadcrumb Permalinks
 * @author    Bryce Adams
 * @license   GPL-2.0+
 * @since     1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WCBP Class
 *
 * @package  WooCommerce Breadcrumb Permalinks
 * @since    1.1.0
 */

class WCBP {

	const VERSION = '1.1.0';

	protected $plugin_slug = 'woocommerce-breadcrumb-permalinks';

	protected static $instance = null;

	/**
	 * Constructor.
	 */
	private function __construct() {

		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'nag_ignore' ) );
		add_action( 'registered_post_type', array( $this, 'add_permastruct' ), 10, 2 );
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 1, 3 );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Start the Class when called
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
	 * @since   1.1.0
	 */

	public function admin_notice() {

    	if ( ! get_option( 'wcbp_ignore_notice' ) ) {
            if ( current_user_can( 'manage_options' ) ) {
                echo '<div class="updated"><p><strong><a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">';
                _e( 'Please re-save your permalinks!', 'woocommerce-breadcrumb-permalinks' );
                echo '</a></strong></p></div>';
            }
    	}

    }

    /**
     * Remove admin notice when permalink settings saved.
     * @since  1.1.0
     */
	public function nag_ignore() {

        if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
             update_option( 'wcbp_ignore_notice', true );
        }

    }

    /**
	 * Add permalinks settings action link to the plugins page.
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . __( 'Permalinks', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}


	/**
	 * Rewrite Permalinks
	 * @since   1.1.0
	 *
	 * @param $link
	 * @param WP_Post $post
	 * @return string|void
	 */

	public function post_type_link( $link, WP_Post $post ) {

		if ( $post->post_type == 'product' ) {

			if ( $terms = wp_get_post_terms( $post->ID, 'product_cat' ) ) {

				$terms = $this->remove_parent_terms( $terms );

				$main_term = reset( $terms );

				$ancestors = array_reverse( get_ancestors( $main_term->term_id, 'product_cat' ) );

				$term_slugs = array();

				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, 'product_cat' );

					if ( ! is_wp_error( $ancestor ) && $ancestor ) {
						$term_slugs[] = $ancestor->slug;
					}
				}

				$term_slugs[] = $main_term->slug;
				$term_slug    = implode( '/', $term_slugs );

				$search  = array( '%post_id%', '%postname%', '%product_cat%' );
				$replace = array( $post->ID, $post->post_name, $term_slug );

				return str_replace( $search, $replace, $link );

			} else {
				return $link;
			}

		} else {
			return $link;
		}

	}

	/**
	 * Remove Parent Term from Term list.
	 * @since 1.1.0
	 *
	 * @param array $terms
	 * @return array
	 */
	public function remove_parent_terms( Array $terms ) {
		$new_term = array();
		foreach ( $terms as $key => $term ) {

			if ( ! $this->exist_child_term( $term, $terms ) ) {
				$new_term = array( $term );
			}
		}

		return $new_term;
	}

	/**
	 * Search parent term in $terms
	 * @since 1.1.0
	 *
	 * @param object $term term object.
	 * @param array $terms term object list.
	 * @return bool
	 */
	public function exist_child_term( $term, Array $terms ) {
		if ( empty( $term->term_id ) ) {
			return false;
		}

		foreach ( $terms as $obj ) {
			if ( $obj->parent == $term->term_id ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Register Permalink Structure for Product.
	 * @since 1.1.0
	 *
	 * @param string $post_type
	 * @param array $args
	 */
	public function add_permastruct( $post_type, $args ) {

		if ( $post_type == 'product' ) {
			$wcbp_base_setting = trim( get_option( 'wcbp_permalinks_base' ), '/' );
			if ( ! $wcbp_base_setting ) {
				$wcbp_base_setting = 'shop';
			}

			add_rewrite_tag( '%product_cat%', '(.+?)', "post_type=product&product_cat=" );

			$permastruct_args         = $args->rewrite;
			$permastruct_args['feed'] = $permastruct_args['feeds'];
			add_permastruct( $post_type, $wcbp_base_setting . '/%product_cat%/%postname%', $permastruct_args );
			// for post id.
			//add_permastruct( $post_type, $wcbp_base_setting.'/%post_id%' ,  $permastruct_args );
		}
	}

}