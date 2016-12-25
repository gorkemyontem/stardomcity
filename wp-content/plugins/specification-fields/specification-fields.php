<?php
/*
Plugin Name: WPSM Specification Fields
Plugin URI: https://wpsoul.com
Description: Allow to create specification sections, and manage post layouts
Version: 1.4
Author: Wpsoul
Author URI: https://wpsoul.com
License: GPLv2
Text Domain: spec_fields
Domain Path: /languages/
*/

define( 'SPEC_PLUGIN_VERSION', '1.4' );
define( 'SPEC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPEC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPEC_FIELD_PREFIX', '_wpsm_' );

add_action( 'init', 'wpsm_init_spec_fields' );
function wpsm_init_spec_fields() {
	
	/**
	 * Setup Specification Post Type
	 *
	 * @since 1.0.0
	 */
	$labels_spec = array(
		'name' => __( "Specifications", "spec_fields" ),
		'singular_name' => __( "Specification", "spec_fields" ),
		'all_items' => __( "All Specifications", "spec_fields" ),
		'add_new' => __( "Add Specification", "spec_fields" ),
		'edit_item' => __( "Edit Specification", "spec_fields" )
	);
	$args_spec = array(
		'labels' => $labels_spec,
		'public' => true,
		'exclude_from_search' => true,
		'menu_icon' => 'dashicons-welcome-widgets-menus',
		'supports' => array( 'title' ),
		'has_archive' => false
	);
	register_post_type( 'rh_specification', $args_spec );
	$labels_tabs = array(
		'name' => __( "Layout Tabs", "spec_fields" ),
		'singular_name' => __( "Layout Tab", "spec_fields" ),
		'all_items' => __( "All Layout Tabs", "spec_fields" ),
		'add_new' => __( "Add Layout Tab", "spec_fields" ),
		'edit_item' => __( "Edit Layout Tab", "spec_fields" )
	);
	$args_tabs = array(
		'labels' => $labels_tabs,
		'public' => true,
		'exclude_from_search' => true,
		'menu_icon' => 'dashicons-welcome-widgets-menus',
		'supports' => array( 'title' ),
		'has_archive' => false
	);
	register_post_type( 'rh_tabpost', $args_tabs );	
	
	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	load_plugin_textdomain( 'spec_fields', false, dirname( SPEC_PLUGIN_BASENAME ) . '/languages/' );
}


if ( file_exists( SPEC_PLUGIN_DIR . 'cmb2/init.php' ) ) {
	/**
	 * Init Framework CMB2
	 */
	require_once SPEC_PLUGIN_DIR . 'cmb2/init.php';
	/**
	 * Preload CMB2 Extension for Depended Fields
	 */
	add_action( 'plugins_loaded', 'cmb2_conditionals_load_actions' );
	include SPEC_PLUGIN_DIR . 'cmb2-conditionals/cmb2-conditionals.php';
	/**
	 * Preload CMB2 Slider Field Class
	 */
	 require_once SPEC_PLUGIN_DIR . 'cmb2-field-slider/cmb2_field_slider.php';
	 /**
	 * Preload CMB2 FontAwesome Picker Class
	 */
	 require_once SPEC_PLUGIN_DIR . 'cmb2-fontawesome-icon-picker/cmb2-fontawesome-picker.php';
	 /**
	 * Preload CMB2 Search Post Class
	 */
	 require_once SPEC_PLUGIN_DIR . 'cmb2-search/cmb2_post_search_field.php';
	/**
	 * Setup Specification Fields
	 */
	require_once SPEC_PLUGIN_DIR . 'fields.php';
	/**
	 * Setup Admin Options class
	 */
	require_once SPEC_PLUGIN_DIR . 'admin-options.php'; 
	/**
	 * Setup Functions for frontend
	 */
	require_once SPEC_PLUGIN_DIR . 'shortcode.php';	
}

/**
 * gets the current post type in the WordPress Admin
 */
function get_current_post_type() {
  global $post, $typenow, $current_screen;
	
  if ( $post && $post->post_type )
    return $post->post_type;
    
  elseif( $typenow )
    return $typenow;
    
  elseif( $current_screen && $current_screen->post_type )
    return $current_screen->post_type;
  
  elseif( isset( $_REQUEST['post_type'] ) )
    return sanitize_key( $_REQUEST['post_type'] );
	
  return null;
}

/* function wpsm_deactivate_spec_fields() {
}
register_deactivation_hook( __FILE__, 'wpsm_deactivate_spec_fields'); */
?>