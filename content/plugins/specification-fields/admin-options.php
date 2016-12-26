<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class WPSM_SF_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'wpsmsf_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'wpsmsf_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page URL
	 * @var string
	 */
	protected $parent_slug = '';
	
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var WPSM_SF_Admin
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		$this->title = __( "Assign layout", "spec_fields" );
		$this->parent_slug = 'edit.php?post_type=rh_tabpost';
	}

	/**
	 * Returns the running object
	 *
	 * @return WPSM_SF_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}

	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page( $this->parent_slug, $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div id="post">
				<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  1.0.4
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$options = new_cmb2_box( array(
			'id' => $this->metabox_id,
			'hookup' => false,
			'cmb_styles' => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Add repeatable Layout Group
		$options_group_id = $options->add_field( array(
			'id' => '_wpsm_spec_options_layout',
			'type' => 'group',
			'description' => __( "Assign plugin layout to post type. Use this if you want to assign any layout which created with plugin to posts automatically.", "spec_fields" ),
			'options' => array(
				'group_title' => __( "Layout {#}", "spec_fields" ),
				'add_button' => __( "Add Layout", "spec_fields" ),
				'remove_button' => __( "Remove Layout", "spec_fields" ),
				'sortable' => true,
			),
		) );

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.

		$options->add_group_field( $options_group_id, array(
		    'name'       => __( 'Select your layout', 'spec_fields' ),
		    'id'         => 'spec_assign_layout',
		    'show_option_none' => true,
		    'type'       => 'select',
		    'options_cb' => 'rh_generate_spec_titles',
		) );		
		
		$options->add_group_field( $options_group_id, array(
		    'name'       => __( 'Assign to post type', 'spec_fields' ),
		    'id'         => 'spec_assign_posttype',
		    'show_option_none' => true,
		    'type'       => 'select',
		    'options_cb' => 'rh_generate_spec_post_types',
		) );

		$options->add_group_field( $options_group_id, array(
			'name' => __( "Assign to special category", "spec_fields" ),
			'desc' => __( "Use this only if you need to assign layout to special category. Place category slug or category ID. You can use several (divide by comma).", "spec_fields" ),			
			'id' => 'spec_assign_custom_cat',
			'type' => 'text',		
		) );

		$options->add_group_field( $options_group_id, array(
			'name' => __( "Custom taxonomy slug", "spec_fields" ),
			'id' => 'spec_assign_custom_tax',
			'desc' => __( "Use this field if you want to use custom taxonomy for previous field", "spec_fields" ),			
			'type' => 'text',		
		) );				

		$options->add_group_field( $options_group_id, array(
		    'name'       => __( 'How to show', 'spec_fields' ),
		    'id'         => 'spec_assign_show',
		    'show_option_none' => true,
		    'type'       => 'select',
		    'options'          => array(
		        'top' => __( 'In top of post', 'spec_fields' ),
		        'bottom' => __( 'In bottom of post', 'spec_fields' ),
		        'firsttab' => __( 'Show content as first tab (only if you specified tabs layout)', 'spec_fields' ),
		    ),
		) );			
		$options->add_group_field( $options_group_id, array(
			'name' => __( "Title for First Tab", "spec_fields" ),
			'id' => 'spec_assign_tab_title',
			'type' => 'text',
			'attributes' => array(
				'data-conditional-id' => json_encode( array( $options_group_id, 'spec_assign_show' ) ),
				'data-conditional-value' => 'firsttab',
			)			
		) );
		
		$options->add_group_field( $options_group_id, array(
			'name' => __( "Select Icon for first tab", "spec_fields" ),
			'id' => 'spec_assign_tab_icon',
			'type' => 'fontawesome_icon',
			'attributes' => array(
				'data-conditional-id' => json_encode( array( $options_group_id, 'spec_assign_show' ) ),
				'data-conditional-value' => 'firsttab',
			)			
		) );	

		$options->add_group_field( $options_group_id, array(
			'name' => __( "Select color for first tab", "spec_fields" ),
			'desc' => __( "default - #111111 (optional)", "spec_fields" ),
			'id' => 'spec_assign_tab_color',
			'type'  => 'colorpicker',
			'default' => '#111111',
			'attributes' => array(
				'data-conditional-id' => json_encode( array( $options_group_id, 'spec_assign_show' ) ),
				'data-conditional-value' => 'firsttab',
			)
		) );								

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( "Settings updated.", "spec_fields" ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the WPSM_SF_Admin object
 * @since  0.1.0
 * @return WPSM_SF_Admin object
 */
function wpsmsf_admin() {
	return WPSM_SF_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function wpsmsf_get_option( $key = '' ) {
	return cmb2_get_option( wpsmsf_admin()->key, $key );
}

if( !function_exists('rh_generate_spec_titles') ) {
function rh_generate_spec_titles( $query_args ) {
    $args = array(
        'post_type'   => 'rh_tabpost',
        'numberposts' => -1,
        'no_found_rows' => 1,
    );
    $posts = get_posts( $args );
    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }
    return $post_options;
}
}

//CPT chooser
if( !function_exists('rh_generate_spec_post_types') ) {
    function rh_generate_spec_post_types() {
        $post_types = get_post_types( array('public'   => true) );
        $post_types_list = array();
        foreach ( $post_types as $post_type ) {
            if ( $post_type !== 'revision' && $post_type !== 'nav_menu_item' && $post_type !== 'attachment' && $post_type !== 'rh_tabpost' && $post_type !== 'rh_specification') {
                $label = $post_type;
                $post_types_list[$label] = $post_type;
            }
        }
        return $post_types_list;
    }
}

// Get it started
wpsmsf_admin();