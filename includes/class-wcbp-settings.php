<?php
/**
 * WooCommerce Breadcrumb Permalinks Settings Class
 *
 * @package   WooCommerce Breadcrumb Permalinks
 * @license   GPL-2.0+
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WCBP Settings Class
 * @since    1.0.0
 */

class WCBP_Settings {

	protected static $instance = null;
    
    /**
     * Constructor.
     */
    private function __construct() {

		add_action( 'admin_init', array( $this, 'permalinks_settings' ) );
		add_action( 'admin_init', array( $this, 'permalinks_save' ) );

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
	 * Register and define the settings
	 * @since   1.0.0
	 */

	public function permalinks_settings() {

		register_setting(
			'permalink',
			'wcbp_permalinks_base',
			'esc_attr'
		);
		
		add_settings_field(
			'wcbp_the_base',
			'Shop Permalinks Base',
			array( $this, 'setting_input' ),
			'permalink',
			'woocommerce-permalink'
		);

	}

	/**
	 * Display and fill the form field
	 * @since  1.0.0
	 */
	public function setting_input() {

		$options = get_option( 'wcbp_permalinks_base' );

		if ( ISSET( $options ) ) {
			$value = $options;
		} else {
			$value = '';
		}

		
		?>

		<input name="the_permalink_base" id="wcbp_permalink_base" type="text" value="<?php echo esc_attr( $value ); ?>" class="regular-text code" /> 
		<?php
		echo '<span style="font-size: 13px; font-style: italic;">';
		_e( 'Enter the custom base to use, defined above in the Products Permalink Base settings, eg. products, items. If using \'shop\', you may leave blank.' );
		echo '</span>';

		echo '<p style="padding-top: 10px;">' . __( 'Please Note: The <strong>Product Permalink Base</strong> above must be ' ) . '<code>/your-shop-permalinks-base/%product_cat%/</code>,' . __( 'where <strong>your-shop-permalinks-base</strong> is the same as this <strong>Shop Permalinks Base</strong> option.' ) . '</p>';

	}

	/**
	 * Save the permalinks.
	 * We need to save the options ourselves; settings api does not trigger save for the permalinks page
	 * @since 1.0.0
	 */
	public function permalinks_save() {
		if ( ! is_admin() )
			return;

		if ( isset( $_POST['the_permalink_base'] ) ) {

			$wcbp_base = wc_clean( $_POST['the_permalink_base'] );

			$permalinks = get_option( 'wcbp_permalinks_base' );
			if ( ! $permalinks ) {
				$permalinks = array();
			}

			$permalinks = untrailingslashit( $wcbp_base );
			$permalinks = preg_replace( '/\s+/', '', $permalinks );

			update_option( 'wcbp_permalinks_base', $permalinks );
		}
	}

}
