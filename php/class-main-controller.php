<?php

namespace mkdo\location_fields_for_gravity_forms;

/**
 * Class Main_Controller
 *
 * The main loader for this plugin
 *
 * @package mkdo\location_fields_for_gravity_forms
 */
class Main_Controller {

	private $plugin_options;
	private $assets_controller;
	private $admin_notices;
	private $choose_location_field;

	/**
	 * Constructor
	 *
	 * @param Options            $options              Object defining the options page
	 * @param AssetsController   $assets_controller    Object to load the assets
	 */
	public function __construct(
		Plugin_Options $plugin_options,
		Assets_Controller $assets_controller,
		Admin_Notices $admin_notices,
		Choose_Location_Field $choose_location_field
	) {
		$this->plugin_options          = $plugin_options;
        $this->assets_controller       = $assets_controller;
		$this->admin_notices           = $admin_notices;
		$this->choose_location_field   = $choose_location_field;
	}

	/**
	 * Do Work
	 */
	public function run() {
		load_plugin_textdomain( MKDO_LFFGF_TEXT_DOMAIN, false, MKDO_LFFGF_ROOT . '\languages' );
		$this->plugin_options->run();
		$this->assets_controller->run();
		$this->admin_notices->run();
		$this->choose_location_field->run();
	}
}
