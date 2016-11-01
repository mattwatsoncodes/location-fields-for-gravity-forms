<?php

namespace mkdo\location_fields_for_gravity_forms;

/**
 * Class Plugin Options
 *
 * Options page for the plugin
 *
 * @package mkdo\location_fields_for_gravity_forms
 */
class Plugin_Options {

	private $options_prefix;
	private $options_menu_slug;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options_prefix      = 'mkdo_lffgf_';
		$this->options_menu_slug   = 'location_fields_for_gravity_forms';
		$this->plugin_settings_url = admin_url( 'options-general.php?page=' . $this->options_menu_slug );
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_init', array( $this, 'init_options_page' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'plugin_action_links_' . plugin_basename( MKDO_LFFGF_ROOT ) , array( $this, 'add_setings_link' ) );
	}

	/**
	 * Getters and Setters
	 */
	public function get_options_prefix() {
		return $this->options_prefix;
	}

	public function get_plugin_settings_url() {
		return $this->plugin_settings_url;
	}

	/**
	 * Initialise the Options Page
	 */
	public function init_options_page() {

		$prefix   = $this->options_prefix;
		$settings = $prefix . 'settings';

		// Register Settings
		register_setting( $settings . '_group', $prefix . 'enqueue_front_end_assets' );
		register_setting( $settings . '_group', $prefix . 'enqueue_back_end_assets' );
		register_setting( $settings . '_group', $prefix . 'google_maps_api_key' );

		// Add section and fields for Asset Enqueing
		$section = $prefix . 'section_enqueue_assets';
		add_settings_section( $section, 'Enqueue Assets', array( $this, 'render_section_enqueue_assets' ), $settings );
		add_settings_field( $prefix . 'field_enqueue_front_end_assets', 'Enqueue Front End Assets:', array( $this, 'render_field_enqueue_front_end_assets' ), $settings, $section );
		add_settings_field( $prefix . 'field_enqueue_back_end_assets', 'Enqueue Back End Assets:', array( $this, 'render_field_enqueue_back_end_assets' ), $settings, $section );

		// Add section and fields for Google API
		$section = $prefix . 'section_google_api';
		add_settings_section( $section, 'Google Maps API', array( $this, 'render_section_google_api' ), $settings );
		add_settings_field( $prefix . 'field_google_api', 'Google Maps API Key:', array( $this, 'render_field_google_api' ), $settings, $section );
	}

	/**
	 * Render the Enqueue Assets section
	 */
	public function render_section_enqueue_assets() {
		echo '<p>';
		esc_html_e( 'Assets are loaded by default, however we recomend that you disable asset loading and include assets in your frontend workflow.', MKDO_LFFGF_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Enqueue Front End Assets field
	public function render_field_enqueue_front_end_assets() {

		$prefix          = $this->options_prefix;
		$enqueued_assets = get_option(
			$prefix . 'enqueue_front_end_assets',
			array(
				'google_maps_api_js',
				'plugin_css',
				'plugin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_front_end_assets[]" value="google_maps_api_js" <?php echo in_array( 'google_maps_api_js', $enqueued_assets ) ?  'checked="checked"' : '';?> />
						<?php esc_html_e( 'Google Maps JS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_front_end_assets[]" value="plugin_css" <?php echo in_array( 'plugin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin CSS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_front_end_assets[]" value="plugin_js" <?php echo in_array( 'plugin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin JS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	// Render the Enqueue Back End Assets field
	public function render_field_enqueue_back_end_assets() {

		$prefix          = $this->options_prefix;
		$enqueued_assets = get_option(
			$prefix . 'enqueue_back_end_assets',
			array(
				'google_maps_api_js',
				'plugin_admin_css',
				'plugin_admin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_back_end_assets[]" value="google_maps_api_js" <?php echo in_array( 'google_maps_api_js', $enqueued_assets ) ?  'checked="checked"' : '';?> />
						<?php esc_html_e( 'Google Maps JS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_back_end_assets[]" value="plugin_admin_css" <?php echo in_array( 'plugin_admin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin CSS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_back_end_assets[]" value="plugin_admin_js" <?php echo in_array( 'plugin_admin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin JS', MKDO_LFFGF_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render the Google API section
	 */
	public function render_section_google_api() {

		$api_key_documentation_url = 'https://developers.google.com/maps/documentation/javascript/get-api-key';

		echo '<p>';
		esc_html_e( 'If you are enqueing the Google Maps API, you can enter your API Key here.', MKDO_LFFGF_TEXT_DOMAIN );
		echo ' ';
		_e( sprintf( '%s' . $api_key_documentation_url . '%sIf you do not have an API Key, you can get one here%s.', '<a href="', '">', '</a>' ), MKDO_LFFGF_TEXT_DOMAIN );
		echo '</p>';

	}

	// Render the Google API field
	public function render_field_google_api() {

		$prefix     = $this->options_prefix;
		$google_api = get_option(
			$prefix . 'google_maps_api_key',
			''
		);

		?>
		<div class="field field-text field-google-api">
			<label class="screen-reader-text" for="<?php echo $prefix;?>google_api"><?php esc_html_e( 'Google Maps API Key', MKDO_LFFGF_TEXT_DOMAIN );?></label>
			<input type="text" id="<?php echo $prefix;?>google_api" name="<?php echo $prefix;?>google_maps_api_key" value="<?php echo $google_api;?>" size="50"/>
		</div>
		<?php
	}


	/**
	 * Add the options page
	 */
	public function add_options_page() {
		add_submenu_page( 'options-general.php', esc_html__( 'Location Fields for Gravity Forms', MKDO_LFFGF_TEXT_DOMAIN ), esc_html__( 'Location Fields for Gravity Forms', MKDO_LFFGF_TEXT_DOMAIN ), 'manage_options', 'location_fields_for_gravity_forms', array( $this, 'render_options_page' ) );
	}

	/**
	 * Render the options page
	 */
	public function render_options_page() {
		$prefix   = $this->options_prefix;
		$settings = $prefix . 'settings';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Location Fields for Gravity Forms', MKDO_LFFGF_TEXT_DOMAIN );?></h2>
			<form action="options.php" method="POST">
	            <?php settings_fields( $settings . '_group' ); ?>
	            <?php do_settings_sections( $settings ); ?>
	            <?php submit_button(); ?>
	        </form>
		</div>
	<?php
	}

	/**
	 * Add 'Settings' action on installed plugin list
	 */
	public function add_setings_link( $links ) {
		array_unshift( $links, '<a href="' . $this->plugin_settings_url . '">' . esc_html__( 'Settings', MKDO_LFFGF_TEXT_DOMAIN ) . '</a>' );
		return $links;
	}

}
