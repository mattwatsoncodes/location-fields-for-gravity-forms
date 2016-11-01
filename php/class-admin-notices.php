<?php
namespace mkdo\location_fields_for_gravity_forms;
/**
 * Class Admin_Notices
 *
 * Notifies the user if the admin needs attention
 *
 * @package mkdo\location_fields_for_gravity_forms
 */
class Admin_Notices {

	private $options_prefix;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix      = $plugin_options->get_options_prefix();
		$this->plugin_settings_url = $plugin_options->get_plugin_settings_url();
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function admin_notices() {

		$prefix = $this->options_prefix;

		$google_api_key = get_option(
			$prefix . 'google_maps_api_key',
			''
		);

		$enqueued_front_end_assets = get_option(
			$prefix . 'enqueue_front_end_assets',
			array(
				'google_maps_api_js',
				'plugin_css',
				'plugin_js',
			)
		);

		$enqueued_back_end_assets = get_option(
			$prefix . 'enqueue_back_end_assets',
			array(
				'google_maps_api_js',
				'plugin_admin_css',
				'plugin_admin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_front_end_assets ) ) {
			$enqueued_front_end_assets = array();
		}

		if ( ! is_array( $enqueued_back_end_assets ) ) {
			$enqueued_back_end_assets = array();
		}

		if ( empty( $google_api_key ) && ( in_array( 'google_maps_api_js', $enqueued_front_end_assets ) || in_array( 'google_maps_api_js', $enqueued_back_end_assets ) ) ) {

			?>
			<div class="notice notice-warning is-dismissible">
			<p>
			<?php _e( sprintf( 'You have enqueued the Google Maps API JS in %sLocation Fields for Gravity Forms%s, but they API Key has not been defined. %sYou can set this on the plugin settings page%s', '<strong>', '</strong>', '<a href="' . $this->plugin_settings_url . '">', '</a>' ) , MKDO_LFFGF_TEXT_DOMAIN ); ?>
			</p>
			</div>
			<?php
		}

		if ( ! class_exists( 'GFFormsModel', false ) ) {
			$gravity_forms_url = 'http://www.gravityforms.com/';
			?>
			<div class="notice notice-warning is-dismissible">
			<p>
			<?php _e( sprintf( 'The %sLicence Type Field for Gravity Forms%s plugin requires that you %sinstall and activate the Gravity Forms plugin%s.', '<strong>', '</strong>', '<a href="' . $gravity_forms_url . '" target="_blank">', '</a>' ) , MKDO_LFFGF_TEXT_DOMAIN ); ?>
			</p>
			</div>
			<?php
		}
	}
}
