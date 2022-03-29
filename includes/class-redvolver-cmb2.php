<?php

class Redvolver_CMB2 {

	private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {
    add_action( 'cmb2_admin_init', array($this, 'timecard_metabox') );
  }

  public function timecard_metabox() {

    $cmb_timecard = new_cmb2_box( array(
  		'id'            => 'rv_timecard_metabox',
  		'title'         => esc_html__( 'Test Metabox', 'cmb2' ),
  		'object_types'  => array( 'timecard' ), // Post type
  		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
  		// 'context'    => 'normal',
  		// 'priority'   => 'high',
  		// 'show_names' => true, // Show field names on the left
  		// 'cmb_styles' => false, // false to disable the CMB stylesheet
  		// 'closed'     => true, // true to keep the metabox closed by default
  		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
  		// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

  		/*
  		 * The following parameter is any additional arguments passed as $callback_args
  		 * to add_meta_box, if/when applicable.
  		 *
  		 * CMB2 does not use these arguments in the add_meta_box callback, however, these args
  		 * are parsed for certain special properties, like determining Gutenberg/block-editor
  		 * compatibility.
  		 *
  		 * Examples:
  		 *
  		 * - Make sure default editor is used as metabox is not compatible with block editor
  		 *      [ '__block_editor_compatible_meta_box' => false/true ]
  		 *
  		 * - Or declare this box exists for backwards compatibility
  		 *      [ '__back_compat_meta_box' => false ]
  		 *
  		 * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
  		 */
  		// 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
  	) );

    $cmb_timecard->add_field( array(
  		'name'       => esc_html__( 'Test Text', 'cmb2' ),
  		'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
  		'id'         => 'yourprefix_demo_text',
  		'type'       => 'text',
  		'show_on_cb' => 'yourprefix_hide_if_no_cats', // function should return a bool value
  		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
  		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
  		// 'on_front'        => false, // Optionally designate a field to wp-admin only
  		// 'repeatable'      => true,
  		// 'column'          => true, // Display field value in the admin post-listing columns
  	) );

    $cmb_timecard->add_field( array(
    	'name' => 'Test Date Picker',
    	'id'   => 'wiki_test_textdate_timestamp',
    	'type' => 'text_date',
    	// 'timezone_meta_key' => 'wiki_test_timezone',
    	'date_format' => 'd/m/Y',
    ) );

  }


}

Redvolver_CMB2::instance();
