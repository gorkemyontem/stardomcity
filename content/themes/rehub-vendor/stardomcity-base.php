<?php
class StardomCityBase {

    function __construct() {
      $this->init();
    }

    public function init(){
      $this->create_custom_taxonomy_campaign_type();
      $this->create_custom_taxonomy_social_media_channels();

      if($_SERVER['Env'] == 'Prod'){
        add_action('wp_head', array($this, 'add_google_tag_manager'), 10);
        add_action('wp_footer', array($this, 'add_google_tag_manager_noscript'), 10);
      }

      add_filter('wcv_admin_lockout_capability', array($this, 'allow_editors_to_login_admin_panel'), 10, 1);
      add_filter('wcv_product_description',      array($this, 'make_required_fieldname'), 10, 1);
      add_action('wcv_save_product',             array($this, 'save_campaign_type'), 10, 1);
      add_action('wcv_save_product',             array($this, 'save_social_media_channel'), 10, 1);
      add_action('wcv_save_product_meta',        array($this, 'save_product_type_virtual'), 10, 1);
      add_filter('wcv_product_categories',       array($this, 'wcv_product_categories_required'));
      add_filter('wcv_product_price',            array($this, 'wcv_product_price_required'));
    }

    // Determine if it's an email using the WooCommerce email header
    add_action( 'woocommerce_email_header', function(){ add_filter( "better_wc_email", "__return_true" ); } );
    // Hide the WooCommerce Email header and footer
    add_action( 'woocommerce_email_header', function(){ ob_start(); }, 1 );
    add_action( 'woocommerce_email_header', function(){ ob_get_clean(); }, 100 );
    add_action( 'woocommerce_email_footer', function(){ ob_start(); }, 1 );
    add_action( 'woocommerce_email_footer', function(){ ob_get_clean(); }, 100 );

    // Selectively apply WPBE template if it's a WooCommerce email
    add_action( 'phpmailer_init', 'better_phpmailer_init', 20 );
    function better_phpmailer_init( $phpmailer ){
        // this filter will return true if the woocommerce_email_header action has run
        if ( apply_filters( 'better_wc_email', false ) ){
            global $wp_better_emails;

            // Add template to message
            $phpmailer->Body = $wp_better_emails->set_email_template( $phpmailer->Body );

            // Replace variables in email
            $phpmailer->Body = apply_filters( 'wpbe_html_body', $wp_better_emails->template_vars_replacement( $phpmailer->Body ) );
        }
    }


    public function allow_editors_to_login_admin_panel( $capability ) {
      return 'edit_pages'; //because editors and admins have this capability in common
    }



    //   // Hook the appropriate WordPress action
    //   add_action('init', 'prevent_wp_login');
    //
    //   function prevent_wp_login() {
    //       // WP tracks the current page - global the variable to access it
    //       global $pagenow;
    //       // Check if a $_GET['action'] is set, and if so, load it into $action variable
    //       $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    //       // Check if we're on the login page, and ensure the action is not 'logout'
    //       if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp'))))) {
    //           // Load the home page url
    //           $page = get_bloginfo('url');
    //           // Redirect to the home page
    //           wp_redirect($page);
    //           // Stop execution to prevent the page loading for any reason
    //           exit();
    //       }
    //   }
    //
    //
    //   add_action('init','custom_login');
    //
    // function custom_login(){
    //  global $pagenow;
    //  if( 'wp-login.php' == $pagenow ) {
    //   wp_redirect('http://yoursite.com/');
    //   exit();
    //  }
    // }




    public function add_google_tag_manager() { ?>
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-WGN894M');</script>
    <?php }

    public function add_google_tag_manager_noscript() { ?>
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WGN894M" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php }



    public function save_product_type_virtual($post_id)  {
       update_post_meta( $post_id, '_downloadable', 'no' );
       update_post_meta( $post_id, '_virtual', 'yes' );
    }

    public function create_custom_taxonomy_campaign_type()  {
      $labels = array(
          'name'                       => 'Campaign Types',
          'singular_name'              => 'Campaign Type',
          'menu_name'                  => 'Campaign Type',
          'all_items'                  => 'All Campaign Types',
          'parent_item'                => 'Parent Campaign Type',
          'parent_item_colon'          => 'Parent Campaign Type:',
          'new_item_name'              => 'New Campaign Type Name',
          'add_new_item'               => 'Add New Campaign Type',
          'edit_item'                  => 'Edit Campaign Type',
          'update_item'                => 'Update Campaign Type',
          'separate_items_with_commas' => 'Separate Campaign Type with commas',
          'search_items'               => 'Search Campaign Types',
          'add_or_remove_items'        => 'Add or remove Campaign Types',
          'choose_from_most_used'      => 'Choose from the most used Campaign Types',
      );
      $args = array(
          'labels'                     => $labels,
          'hierarchical'               => true,
          'public'                     => true,
          'show_ui'                    => true,
          'show_admin_column'          => true,
          'show_in_nav_menus'          => true,
          'show_tagcloud'              => true,
      );
      register_taxonomy( 'campaign_type', 'product', $args );
      register_taxonomy_for_object_type( 'campaign_type', 'product' );
    }

    public function create_custom_taxonomy_social_media_channels()  {
      $labels = array(
          'name'                       => 'Social Media Channels',
          'singular_name'              => 'Social Media Channel',
          'menu_name'                  => 'Social Media Channel',
          'all_items'                  => 'All Social Media Channels',
          'parent_item'                => 'Parent Social Media Channel',
          'parent_item_colon'          => 'Parent Social Media Channel:',
          'new_item_name'              => 'New Social Media Channel Name',
          'add_new_item'               => 'Add New Social Media Channel',
          'edit_item'                  => 'Edit Social Media Channel',
          'update_item'                => 'Update Social Media Channel',
          'separate_items_with_commas' => 'Separate Social Media Channel with commas',
          'search_items'               => 'Search Social Media Channels',
          'add_or_remove_items'        => 'Add or remove Social Media Channels',
          'choose_from_most_used'      => 'Choose from the most used Social Media Channels',
      );
      $args = array(
          'labels'                     => $labels,
          'hierarchical'               => true,
          'public'                     => true,
          'show_ui'                    => true,
          'show_admin_column'          => true,
          'show_in_nav_menus'          => true,
          'show_tagcloud'              => true,
      );
      register_taxonomy( 'social_media_channel', 'product', $args );
      register_taxonomy_for_object_type( 'social_media_channel', 'product' );
    }

//WC vendors Social Settings
public function profile_twitch_username( ){
   if ( class_exists( 'WCVendors_Pro' ) ){
     $value = get_user_meta( get_current_user_id(), '_wcv_custom_settings_twitch_url', true );
     WCVendors_Pro_Form_Helper::input( array(
       'id' 				=> '_wcv_custom_settings_twitch_url',
       'label' 			=> __( 'Twitch Name', 'wcvendors-pro' ),
       'placeholder' 			=> __( 'First Bank', 'wcvendors-pro' ),
       'desc_tip' 			=> 'true',
       'description' 			=> __( 'Your Twitch Name', 'wcvendors-pro' ),
       'type' 				=> 'text',
       'value'				=> $value,
       )
     );
   }
 }

 public function profile_scorp_username( ){
    if ( class_exists( 'WCVendors_Pro' ) ){
      $value = get_user_meta( get_current_user_id(), '_wcv_custom_settings_scorp_url', true );
      WCVendors_Pro_Form_Helper::input( array(
        'id' 				=> '_wcv_custom_settings_scorp_url',
        'label' 			=> __( 'Scorp Username', 'wcvendors-pro' ),
        'placeholder' 			=> __( 'Scorp Username', 'wcvendors-pro' ),
        'desc_tip' 			=> 'true',
        'description' 			=> __( 'Your Scorp Username', 'wcvendors-pro' ),
        'type' 				=> 'text',
        'value'				=> $value,
        )
      );
    }
  }
  //WC Vendors Product Edit
  public function form_campaign_type( $object_id ) {
   WCVendors_Pro_Form_helper::select2( array(
  			'post_id'			=> $object_id,
  			'id'				=> 'wcv_custom_product_campaign_type[]',
  			'class'				=> 'select2',
  			'label'				=> __('Campaign Type', 'wcvendors-pro'),
  			'show_option_none'	=> __('Select a Campaign Type', 'wcvendors-pro'),
  			'taxonomy'			=>	'campaign_type',
  			'taxonomy_args'		=> array(
  									'hide_empty'	=> 0,
                    'orderby'		=> 'order',
                    'exclude'		=> ''),
  			'custom_attributes' => array(
            	 			'data-rules' => 'required',
            	 			'data-error' => __( 'This is a required field.', 'wcvendors-pro' )
            	 		)
  			)
  	);
  }

  public function save_campaign_type( $post_id ){
  	$term = $_POST[ 'wcv_custom_product_campaign_type' ];
  	$terms = implode(',', $term );
  	wp_set_post_terms( $post_id, $term, 'campaign_type' );
  }

  public function form_social_media_channel( $object_id ) {
   WCVendors_Pro_Form_helper::select2( array(
        'post_id'			=> $object_id,
        'id'				=> 'wcv_custom_product_social_media_channel[]',
        'class'				=> 'select2',
        'label'				=> __('Social Media Channel', 'wcvendors-pro'),
        'show_option_none'	=> __('Select a Social Media Channel', 'wcvendors-pro'),
        'taxonomy'			=>	'social_media_channel',
        'taxonomy_args'		=> array(
                    'hide_empty'	=> 0,
                    'orderby'		=> 'order',
                    'exclude'		=> ''),
        'custom_attributes' => array(
                    'data-rules' => 'required',
                    'data-error' => __( 'This is a required field.', 'wcvendors-pro' )
                  )
        )
    );
  }
  public function save_social_media_channel( $post_id ){
    $term = $_POST[ 'wcv_custom_product_social_media_channel' ];
    $terms = implode(',', $term );
    wp_set_post_terms( $post_id, $term, 'social_media_channel' );
  }

  public function wcv_product_categories_required( $args ) {
      $args[ 'custom_attributes' ] = array(
      	'data-rules' => 'required',
        'data-error' => __( 'This field is required.', 'wcvendors-pro' ),
        'multiple' => 'multiple',

    	);
      return $args;
  }

  public function wcv_product_price_required( $args ) {
      $args[ 'custom_attributes' ] = array(
      	'data-rules' => 'required|decimal',
        'data-error' => __( 'Price should be a number.', 'wcvendors-pro' )
    	);
      return $args;
  }

  public function show_store_name_alert(){

    $author_id = get_current_user_id();
    $shop_name_set = WCV_Vendors::get_vendor_shop_name( $author_id );
    $vendor_login = get_userdata($author_id);
    if ($shop_name_set == $vendor_login->user_login) {
      $this->print_woocommerce_message('Kanalınızın ismini değiştirmeyi unutmayın!');
    }
  }

  public function print_woocommerce_message($message){
    echo '<div class="woocommerce-message">' . $message . '</div>';
  }


}
