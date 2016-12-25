<?php

add_action( 'cmb2_admin_init', 'wpsm_add_spec_fields' );

function wpsm_add_spec_fields() {

	$specification = new_cmb2_box( array(
		'id' => SPEC_FIELD_PREFIX . 'spec_data',
		'title' => __( "Specification settings", "spec_fields" ),
		'object_types' => array( 'rh_specification' ),
		'context' => 'normal',
		'priority' => 'default',
	) );

	// Add repeatable TabGroup
	$group_field_id = $specification->add_field( array(
		'id' => SPEC_FIELD_PREFIX . 'spec_line',
		'type' => 'group',
		'description' => __( "Generate Specification line", "spec_fields" ),
		'options' => array(
			'group_title' => __( "Line {#}", "spec_fields" ),
			'add_button' => __( "Add Line", "spec_fields" ),
			'remove_button' => __( "Remove Line", "spec_fields" ),
			'sortable' => true,
		),
		'after_group' => 'wpsm_set_shortcode_after_row',
	) );	

	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Select generated content:", "spec_fields" ),
		'id' => 'column_type',
		'type' => 'select',
		'show_option_none' => true,
		'options' => array(
			'heading_line' => __( "Section heading", "spec_fields" ),
			'divider_line' => __( "Line divider", "spec_fields" ),			
			'meta_line' => __( "Meta value", "spec_fields" ),			
			'tax_line' => __( "Taxonomy value", "spec_fields" ),
			'shortcode_line' => __( "Shortcode or html value", "spec_fields" ),
			'photo_line' => __( "Photo group", "spec_fields" ),
			'video_line' => __( "Video group", "spec_fields" ),	
			'mdtf_line' => __( "MDTF filter section", "spec_fields" ),	
			'proscons_line' => __( "Pros and cons section", "spec_fields" ),
			'map_line' => __( "Map section", "spec_fields" ),	
		),
	) );
	// Option fields for heading
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Section heading", "spec_fields" ),
		'id' => 'heading_line',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'heading_line',
		)
	) );	
	// Option fields for photo
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Photo group", "spec_fields" ),
		'id' => 'photo_line',
		'desc' => __( "Set key of custom field where stored url of photo or array of urls. Array must be stored with [title] and [url]", "spec_fields" ),
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'photo_line',
		)
	) );
	// Option fields for video
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Video group", "spec_fields" ),
		'id' => 'video_line',
		'desc' => __( "Set key of custom field where stored url of video or array of videos. Also has support for set of urls divided by enter. Works only with yourtube and vimeo!!!", "spec_fields" ),
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'video_line',
		)
	) );	
	// Option fields for MDTF
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Import MDTF filter section", "spec_fields" ),
		'type' => 'post_search_text',
		'post_type'   => array('meta_data_filter' ),
		// Default is 'checkbox', used in the modal view to select the post type
		'select_type' => 'radio',
		// Will replace any selection with selection from modal. Default is 'add'
		'select_behavior' => 'replace',		
		'id' => 'mdtf_line',
		'desc' => __( "Set ID of filter section. Use button to quick search", "spec_fields" ),
		'btn_text'  => __( "Choose filters", "spec_fields" ),
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'mdtf_line',
		)
	) );		
	// Option fields for shortcode line
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter shortcode value", "spec_fields" ),
		'id' => 'shortcode_line',
		'type'  => 'textarea_code',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'shortcode_line',
		)
	) );		

	// Option fields for "Meta value"
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Type of meta value", "spec_fields" ),
		'id' => 'meta_line_type',
		'type' => 'select',
		'default' => 'text',
		'options' => array(
			'text' => __( "Single value", "spec_fields" ),
			'acfmulti' => __( "Multiple ACF meta value", "spec_fields" ),
			'checkbox' => __( "Checkbox (true or false)", "spec_fields" ),
			'usermeta' => __( "Post author user meta", "spec_fields" ),
			'bpmeta' => __( "Post author buddypress profile meta", "spec_fields" ),			
		),
		'desc' => __( "Multiple value field is working only for ACF plugin fields (checkbox, selects, repeater)", "spec_fields" ),
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		),
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Label", "spec_fields" ),
		'id' => 'meta_line_label',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Tooltip", "spec_fields" ),
		'id' => 'meta_line_tooltip',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );		
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Key (slug) of custom field", "spec_fields" ),
		'id' => 'meta_line_key',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Prefix before value", "spec_fields" ),
		'id' => 'meta_line_prefix',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Postfix after value", "spec_fields" ),
		'id' => 'meta_line_postfix',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );

	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Customize font size and colors of column?", "spec_fields" ),
		'id' => 'meta_line_customize',
		'type'  => 'checkbox',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'meta_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Meta Value Font size", "spec_fields" ),
		'desc' => __( "default - 15px", "spec_fields" ),
		'id' => 'meta_line_size',
		'type' => 'own_slider',
		'min' => '10',
		'max' => '36',
		'default' => '15',
		'value_label' => __( "Value:", "spec_fields" ),
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'meta_line_customize' ) ),
			'data-conditional-value' => 'on',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Meta Value Font Color", "spec_fields" ),
		'desc' => __( "default - #111111", "spec_fields" ),
		'id' => 'meta_line_color',
		'type'  => 'colorpicker',
		'default' => '#111111',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'meta_line_customize' ) ),
			'data-conditional-value' => 'on',
		)
	) );
	// Option fields for "Taxonomy value"
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter label", "spec_fields" ),
		'id' => 'tax_line_label',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'tax_line',
		)
	) );	
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter taxonomy", "spec_fields" ),
		'desc' => __( "enter slug of your taxonomy, for example taxonomy for posts is 'category'", "spec_fields" ),
		'id' => 'tax_line_name',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'tax_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Show taxonomy as links?", "spec_fields" ),
		'id' => 'tax_line_type',
		'type'  => 'checkbox',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'tax_line',
		)
	) );		
	// Option fields for PROS AND CONS
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter label for PROS", "spec_fields" ),
		'id' => 'proscons_line_pros_label',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'proscons_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter key for PROS", "spec_fields" ),
		'id' => 'proscons_line_pros_field',
		'desc' => 'Enter meta key where pros is stored (lines must be divided by enter).',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'proscons_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter label for CONS", "spec_fields" ),
		'id' => 'proscons_line_cons_label',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'proscons_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter meta key for CONS", "spec_fields" ),
		'id' => 'proscons_line_cons_field',
		'desc' => 'Enter meta key where cons is stored (lines must be divided by enter). This field is optional',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'proscons_line',
		)
	) );		
	// Option fields for Map
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter meta key for map location", "spec_fields" ),
		'desc' => 'Use this field if you have location',
		'id' => 'map_line_location',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'map_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter key for longitude", "spec_fields" ),
		'id' => 'map_line_longitude',
		'desc' => 'Use this field if your longitude stored separatelly',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'map_line',
		)
	) );
	$specification->add_group_field( $group_field_id, array(
		'name' => __( "Enter key for latitude", "spec_fields" ),
		'id' => 'map_line_latitude',
		'desc' => 'Use this field if your latitude stored separatelly',
		'type'  => 'text',
		'attributes' => array(
			'data-conditional-id' => json_encode( array( $group_field_id, 'column_type' ) ),
			'data-conditional-value' => 'map_line',
		)
	) );


	// Tabs
	$specification_tabs = new_cmb2_box( array(
		'id' => SPEC_FIELD_PREFIX . 'spec_tabs',
		'title' => __( "Post tab layout", "spec_fields" ),
		'object_types' => array( 'rh_tabpost' ),
		'context' => 'normal',
		'priority' => 'default',
	) );

	$specification_tabs_group_id = $specification_tabs->add_field( array(
		'id' => SPEC_FIELD_PREFIX . 'spec_tab_group',
		'type' => 'group',
		'description' => __( "Generate Tabs. Use it if you want to add tabs to post layout", "spec_fields" ),
		'options' => array(
			'group_title' => __( "Tab {#}", "spec_fields" ),
			'add_button' => __( "Add Tab", "spec_fields" ),
			'remove_button' => __( "Remove Tab", "spec_fields" ),
			'sortable' => true,
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$specification_tabs->add_group_field( $specification_tabs_group_id, array(
		'name' => __( "Title for Tab", "spec_fields" ),
		'id' => 'tab_layout_title',
		'type' => 'text',
	) );
	
	$specification_tabs->add_group_field( $specification_tabs_group_id, array(
		'name' => __( "Select Icon", "spec_fields" ),
		'id' => 'tab_layout_icon',
		'type' => 'fontawesome_icon',
	) );
	
	$specification_tabs->add_group_field( $specification_tabs_group_id, array(
		'name' => __( "Insert Shortcode", "spec_fields" ),
		'desc' => __( "which you want to use for content of tab", "spec_fields" ),
		'id' => 'tab_layout_shortcode',
		'type'  => 'text',
	) );

	$specification_tabs->add_group_field( $specification_tabs_group_id, array(
	    'name'       => __( 'Color for tab', 'spec_fields' ),
		'id' => 'spec_assign_tab_color',
		'desc' => __( "default - #111111", "spec_fields" ),
		'type'  => 'colorpicker',
		'default' => '',
	) );

	$specification_tabs->add_field( array(
		'name' => __( "Enable compact view of tabs?", "spec_fields" ),
		'id' => 'tabs_compact',
		'type' => 'checkbox',
	) );		

}


function wpsm_set_shortcode_after_row( $field_args, $field ) {
	
 	if ( $field->object_id ) {
		$postid = $field->object_id;
		$after_tab = '<div class="about-wrap"><p class="about-description">' . __( "You can use shortcode to insert this specification section: ", "spec_fields" ) . '<b>[wpsm_specification_builder id=' . $postid . ']</b></p></div>';
 	} else {
		$after_tab = '';
 	}

	return $after_tab; 
}