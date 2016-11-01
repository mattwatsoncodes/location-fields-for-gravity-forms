<?php
namespace mkdo\location_fields_for_gravity_forms;
/**
 * Class Choose_Location_Field
 *
 * The Choose Location Field for Gravity Forms
 *
 * @package mkdo\location_fields_for_gravity_forms
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class Choose_Location_Field extends \GF_Field {

	public $type = 'choose_location';

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'gform_editor_js_set_default_values', array( $this, 'gform_editor_js_set_default_values' ) );
		add_action( 'gform_field_standard_settings', array( $this, 'gform_field_standard_settings' ) );
		add_filter( 'gform_tooltips', array( $this, 'gform_tooltips' ) );
		\GF_Fields::register( new \mkdo\location_fields_for_gravity_forms\Choose_Location_Field() );
	}

	/**
	 * Setup the form defaults
	 *
	 * This is where we need to define the label for the form, and also define any
	 * inputs (if a complex multi-input field).
	 *
	 * This function hooks into JS output in Gravity Forms so is a little odd to write.
	 */
	public function gform_editor_js_set_default_values() {
		?>
		case 'choose_location' :
			field.label = '<?php _e( 'Choose Location', MKDO_LFFGF_TEXT_DOMAIN ); ?>';
			field.inputs = [
				new Input( field.id + 0.1, "<?php _e( 'Longitude', MKDO_LFFGF_TEXT_DOMAIN ); ?>" ),
				new Input( field.id + 0.2, "<?php _e( 'Latitude', MKDO_LFFGF_TEXT_DOMAIN ); ?>" ),
				new Input( field.id + 0.3, "<?php _e( 'Search', MKDO_LFFGF_TEXT_DOMAIN ); ?>" ),
			];
		break;
		<?php
	}

	/**
	 * Add backend fields to Gravity Forms
	 *
	 * These are all handled with JavaScript, and the JS is handled in the
	 * get_form_editor_inline_script_on_page_render function
	 *
	 * You need to check the position, as if you ommit this the field loops
	 * in all positions in the backend.
	 *
	 * Note that the class on the li ('choose_location_setting' in this case) will
	 * be used in the 'get_form_editor_field_settings' function to allow us to
	 * use these settings
	 *
	 * @param  int      $position    The position of the field in the backend
	 * @return string                HTML output
	 */
	public function gform_field_standard_settings( $position ) {
		if ( 25 == $position  ) {
			?>
			<li class="display_type_setting field_setting">
				<label for="display_type_admin_label" class="section_label">
					<?php _e( 'Display Type', MKDO_LFGF_TEXT_DOMAIN ); ?>
					<?php gform_tooltip( 'display_type' ) ?>
				</label>
				<select id="display_type" onchange="SetFieldProperty( 'display_type', this.value );">
					<option value="">Show Search Input</option>
					<option value="no-search">No Search Input</option>
					?>
				</select>
			</li>
			<li class="alternate_input_setting field_setting">
				<label for="alternate_input_admin_label" class="section_label">
					<?php _e( 'Alternate Input', MKDO_LFGF_TEXT_DOMAIN ); ?>
					<?php gform_tooltip( 'alternate_input' ) ?>
				</label>
				<input type="text" id="alternate_input" onkeyup="SetFieldProperty( 'alternate_input', this.value );"/>
			</li>
			<?php
		}
	}

	/**
	 * Add inline script
	 *
	 * This lets us hook dynamically add our functions and bindings that will
	 * make our backend fields work
	 *
	 * @return String JavaScript output
	 */
	public function get_form_editor_inline_script_on_page_render() {
		$script = "
		jQuery(document).bind( 'gform_load_field_settings', function( event, field, form ) {
			jQuery( '#display_type').val( field.display_type == undefined ? '' : field.display_type );
			jQuery( '#alternate_input').val( field.alternate_input == undefined ? '' : field.alternate_input );
		});";
		return $script;
	}


	/**
	 * Form editor settings
	 *
	 * Add one or more backend settings here, make sure you add in your custom
	 * setting, in this case 'choose_location_setting'
	 *
	 * @return Array    An array of settings that the field uses in the backend
	 */
	function get_form_editor_field_settings() {
		return array(
			'display_type_setting',
			'alternate_input_setting',
		    'conditional_logic_field_setting',
		    'prepopulate_field_setting',
		    'error_message_setting',
		    'label_setting',
		    //'sub_labels_setting',
		    //'label_placement_setting',
		    //'sub_label_placement_setting',
		    'admin_label_setting',
		    //'time_format_setting',
		    'rules_setting',
		    'visibility_setting',
		    //'duplicate_setting',
		    'default_inputs_setting',
		    //'input_placeholders_setting',
		    'description_setting',
		    'css_class_setting',
		);
	}

	/**
	 * Define any tool tips you have setup
	 *
	 * @param  Array   $tooltips    An array of tooltips
	 * @return Array                An array of tooltips
	 */
	public function gform_tooltips( $tooltips ) {
		$tooltips['choose_location'] = __( 'Show the search field', TEXTDOMAIN );
		$tooltips['alternate_input'] = __( 'Use an alterate input to choose location. Enter the CSS selector here.', TEXTDOMAIN );

		return $tooltips;
	}

	/**
	 * Setup the form title
	 *
	 * @return String     The form title
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Choose Location', MKDO_LFFGF_TEXT_DOMAIN );
	}

	/**
	 * Is conditional logic supported?
	 *
	 * @return Boolean  True if conditoinal logic is supported
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	/**
	 * Create our form button
	 *
	 * @return Array    Form button details
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * Validate the form
	 *
	 * This is left empty so that the $value does not get overridden
	 *
	 * @param  String/Array  $value The field value
	 * @param  Object        $form  The form
	 */
	function validate( $value, $form ) {}


	/**
	 * Render the field
	 *
	 * @param  Object        $form     The form object
	 * @param  String/Array  $value    The value of the field
	 * @param  Object        $entry    The entry value
	 * @return String                  HTML of the form
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {

		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$form_id         = absint( $form['id'] );
		$id              = intval( $this->id );
		$field_id        = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$class_suffix    = $is_entry_detail ? '_admin' : '';

		/**
		 * Code for sub-lable placements, I have overriden as this will always be hidden unless in the admin
		 */

		// $form_sub_label_placement  = rgar( $form, 'subLabelPlacement' );
		// $field_sub_label_placement = $this->subLabelPlacement;
		// $is_sub_label_above        = $field_sub_label_placement == 'above' || ( empty( $field_sub_label_placement ) && $form_sub_label_placement == 'above' );
		// $sub_label_class_attribute = $field_sub_label_placement == 'hidden_label' ? "class='hidden_sub_label screen-reader-text'" : '';

		$sub_label_class_attribute = is_admin() ? "class=''" : "class='hidden_sub_label screen-reader-text'";
		$disabled_text             = $is_form_editor ? "disabled='disabled'" : '';

		/**
		 * Grab the values from the value (should always be an array)
		 */
		$longitute = null;
		$latitude  = null;
		$search    = null;

		if ( is_array( $value ) ) {
			$longitute = esc_attr( \RGForms::get( $this->id . '.1', $value ) );
			$latitude  = esc_attr( \RGForms::get( $this->id . '.2', $value ) );
			$search    = esc_attr( \RGForms::get( $this->id . '.3', $value ) );
		}

		/**
		 * Set the field type, we want them hidden on the front end for this plugin
		 */
		$field_type         = is_admin() ? 'text' : 'hidden';
		$field_type_search  = ( is_admin() || '' !== $this->display_type ) ? 'hidden' : 'text';

		/**
		 * Get the input values
		 */
		$longitute_input = \GFFormsModel::get_input( $this, $this->id . '.1' );
		$latitude_input  = \GFFormsModel::get_input( $this, $this->id . '.2' );
		$search_input    = \GFFormsModel::get_input( $this, $this->id . '.3' );

		/**
		 * Get the placeholder attributes (if set)
		 */
		$longitute_placeholder_attribute = \GFCommon::get_input_placeholder_attribute( $longitute_input );
		$latitude_placeholder_attribute  = \GFCommon::get_input_placeholder_attribute( $latitude_input );
		$search_placeholder_attribute    = \GFCommon::get_input_placeholder_attribute( $search_input );

		/**
		 * Get the tab indexes
		 */
		$longitute_tabindex = $this->get_tabindex();
		$latitude_tabindex  = $this->get_tabindex();
		$search_tabindex    = $this->get_tabindex();

		/**
		 * Set the labels (these could be manually set if the backend is configured)
		 */
		$longitute_label = rgar( $longitute_input, 'customLabel' ) != '' ? $longitute_input['customLabel'] : gf_apply_filters( array( 'longitude', $form_id ), esc_html__( 'Longitude', MKDO_LFFGF_TEXT_DOMAIN ), $form_id );
		$latitude_label  = rgar( $latitude_input, 'customLabel' ) != '' ? $latitude_input['customLabel'] : gf_apply_filters( array( 'latitude', $form_id ), esc_html__( 'Latitude', MKDO_LFFGF_TEXT_DOMAIN ), $form_id );
		$search_label    = rgar( $search_input, 'customLabel' ) != '' ? $search_input['customLabel'] : gf_apply_filters( array( 'search', $form_id ), esc_html__( 'Search for Address', MKDO_LFFGF_TEXT_DOMAIN ), $form_id );

		/**
		 * Create the labels and the fields
		 */
		$label1 = "<label for='{$field_id}_1' {$sub_label_class_attribute}>{$longitute_label}</label>";
		$label2 = "<label for='{$field_id}_2' {$sub_label_class_attribute}>{$latitude_label}</label>";
		$label3 = "<label for='{$field_id}_3' {$sub_label_class_attribute}>{$search_label}</label>";

		$input1 = "<input type='{$field_type}' class='lffgf-longitude' name='input_{$id}.1' id='{$field_id}_1' value='{$longitute}' {$longitute_tabindex}  {$disabled_text} {$longitute_placeholder_attribute} />";
		$input2 = "<input type='{$field_type}' class='lffgf-latitude' name='input_{$id}.2' id='{$field_id}_2' value='{$latitude}' {$latitude_tabindex}  {$disabled_text} {$latitude_placeholder_attribute} />";
		$input3 = "<input type='{$field_type_search}' class='lffgf-search' name='input_{$id}.3' id='{$field_id}_3' value='{$search}' {$search_tabindex}  {$disabled_text} {$search_placeholder_attribute} />{$label3}";

		$alternate_input = $this->alternate_input;

		return "
		<div class='ginput_complex{$class_suffix} ginput_container gfield_trigger_change lffgf-map-container' id='{$field_id}'>
			<input type='hidden' nbame='lffgf-alternate-input' value='{$alternate_input}' class='lffgf-alternate-input'/>
			{$input3}
			<div class='lffgf-map'></div>
			<span id='{$field_id}_1_container' class='longitute'>
				{$input1}{$label1}
			</span>
			<span id='{$field_id}_2_container' class='latitude'>
				{$input2}{$label2}
			</span>
			<div class='gf_clear gf_clear_complex'></div>
        </div>
		";
	}

	/**
	 * Get the field classes
	 * @return String     The field classes
	 */
	public function get_field_label_class() {
		return 'gfield_label gfield_label_before_complex';
	}

	/**
	 * Get input property
	 *
	 * @param  Int     $input_id      The ide of the input
	 * @param  String  $property_name The name of the propperty
	 * @return String                 Verturns the value
	 */
	public function get_input_property( $input_id, $property_name ) {
		$input = \GFFormsModel::get_input( $this, $this->id . '.' . (string) $input_id );

		return rgar( $input, $property_name );
	}

	/**
	 * Sanitize the settings
	 */
	public function sanitize_settings() {
		parent::sanitize_settings();
		if ( is_array( $this->inputs ) ) {
			foreach ( $this->inputs as &$input ) {
				if ( isset( $input['choices'] ) && is_array( $input['choices'] ) ) {
					$input['choices'] = $this->sanitize_settings_choices( $input['choices'] );
				}
			}
		}
	}

	/**
	 * Return the field
	 *
	 * @param  String/Array   $value                  The Value
	 * @param  Bool           $force_frontend_label   Force the frontend label
	 * @param  Object         $form                   The Form
	 * @return String                                 The field output
	 */
	// public function get_field_content( $value, $force_frontend_label, $form ) {
	//     $form_id         = $form['id'];
	//     $admin_buttons   = $this->get_admin_buttons();
	//     $is_entry_detail = $this->is_entry_detail();
	//     $is_form_editor  = $this->is_form_editor();
	//     //$is_admin        = $is_entry_detail || $is_form_editor;
	//     $is_admin        = true; //is_admin();
	//     $field_label     = $this->get_field_label( $force_frontend_label, $value );
	//     $field_id        = $is_admin || $form_id == 0 ? "input_{$this->id}" : 'input_' . $form_id . "_{$this->id}";
	//     $field_content   = ! $is_admin ? '{FIELD}' : $field_content = sprintf( "%s<label for='input_%s' class='gfield_label'>%s</label>{FIELD}", $admin_buttons, $field_id, esc_html( $field_label ) );
	//
	//     return $field_content;
	// }

	/**
	 * Show the value on the entries screen
	 */
	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		if ( is_array( $value ) && ! empty( $value ) ) {

			$longitute = trim( $value[ $this->id . '.1' ] );
	        $latitude  = trim( $value[ $this->id . '.2' ] );

			return $latitude . ' ' . $longitute;
	    } else {
	        return $value;
	    }
	}
}
