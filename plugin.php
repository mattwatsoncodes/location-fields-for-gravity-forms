<?php

/**
 * @link              https://github.com/mkdo/location-fields-for-gravity-forms
 * @package           mkdo\location_fields_for_gravity_forms
 *
 * Plugin Name:       Location Fields for Gravity Forms
 * Plugin URI:        https://github.com/mkdo/location-fields-for-gravity-forms
 * Description:       Location Fields designed to work with Gravity Forms
 * Version:           1.0.0
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        http://www.makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       location-fields-for-gravity-forms
 * Domain Path:       /languages
 */

// Constants
define( 'MKDO_LFFGF_ROOT', __FILE__ );
define( 'MKDO_LFFGF_VERSION', '1.0.0' );
define( 'MKDO_LFFGF_TEXT_DOMAIN', 'location-fields-for-gravity-forms' );

// Load Classes
require_once 'php/class-main-controller.php';
require_once 'php/class-plugin-options.php';
require_once 'php/class-assets-controller.php';
require_once 'php/class-admin-notices.php';
require_once 'php/class-choose-location-field.php';

// Use Namespaces
use mkdo\location_fields_for_gravity_forms\Main_Controller;
use mkdo\location_fields_for_gravity_forms\Plugin_Options;
use mkdo\location_fields_for_gravity_forms\Assets_Controller;
use mkdo\location_fields_for_gravity_forms\Admin_Notices;
use mkdo\location_fields_for_gravity_forms\Choose_Location_Field;

// Initialize Classes
$plugin_options        = new Plugin_Options();
$assets_controller     = new Assets_Controller( $plugin_options );
$admin_notices         = new Admin_Notices( $plugin_options );
$choose_location_field = new Choose_Location_Field();
$main_controller       = new Main_Controller(
	$plugin_options,
	$assets_controller,
	$admin_notices,
	$choose_location_field
);

// Run the Plugin
$main_controller->run();
