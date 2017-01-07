<?php
class StardomCityBase {

    function __construct() {
      $this->init();
    }

    private function init(){
      //   add_filter( 'login_redirect', array( $this, 'custom_user_login_redirect' ), 10, 3 );  //static
      add_filter( 'wcv_product_description', array( $this, 'make_required_fieldname' ), 10, 1);

      // add_filter( 'media_upload_newtab', array( $this, 'media_upload_callback' ) );  //instance
      // add_filter( 'media_upload_newtab', array( 'My_Class', 'media_upload_callback' ) );  //static
      // add_filter( 'the_title', function( $title ) { return '<strong>' . $title . '</strong>'; } ); //anonymous
    }


function make_required_fieldname( $args ) {
  return $args;
  // 'product_type_options', array(
  //  'virtual' => array(
  //    'id'            => '_virtual',
  //    'wrapper_class' => 'show_if_simple',
  //    'label'         => __( 'Virtual', 'wcvendors-pro' ),
  //    'description'   => __( 'Virtual products are intangible and aren\'t shipped.', 'wcvendors-pro' ),
  //    'default'       => 'no'
  //  ),


    // $args[ 'custom_attributes' ] = array(
    //   'post_id'	=> $post_id,
    //   'id' 		=> 'post_content',
    //   'label'	 	=> __( 'Product Description', 'wcvendors-pro' ),
    //   'value' 	=> $product_description,
    //   'placeholder' 		=> __( 'Please add a full description of your product here', 'wcvendors-pro' ),
    //   'custom_attributes' => array(
    //     'data-rules' => 'required',
    //     'data-error' => __( 'Product description is required.', 'wcvendors-pro' )
    //
    //   ));

      // 'wcv_product_title', array(
      //  'post_id' 			=> $post_id,
      //  'id'	 			=> 'post_title',
      //  'label' 			=> __( 'Product Name', 'wcvendors-pro' ),
      //  'value' 			=> $product_title,
      //  'custom_attributes' => array(
      //      'data-rules' => 'required|max_length[100]',
      //      'data-error' => __( 'Product name is required or is too long.', 'wcvendors-pro' ),
      //      'data-label' => __( 'Product Name', 'wcvendors-pro' ),
      //
      //    )


      // 'wcv_product_categories',
      //   array(
      //     'post_id'			=> $post_id,
      //     'id' 				=> 'product_cat[]',
      //     'taxonomy'			=> 'product_cat',
      //     'show_option_none'	=> $show_option_none,
      //     'taxonomy_args'		=> array(
      //                 'hide_empty'	=> 0,
      //                 'orderby'		=> 'order',
      //                 'exclude'		=> $exclude,
      //               ),
      //     'label'	 			=> ( $multiple ) ? __( 'Categories', 'wcvendors-pro' ) : __( 'Category', 'wcvendors-pro' ),
      //     'custom_attributes' => $custom_attributes,
      //     )
      //   )


        //
        // WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_tags', array(
        //     'id' 					=> 'product_tags',
        //     'label' 				=> __( 'Tags', 'wcvendors-pro' ),
        //     'value' 				=> implode( ',', array_keys( $tag_ids ) ),
        //     'style'					=> 'width: 100%;',
        //     'class'					=> 'wcv-tag-search',
        //     'type'					=> 'hidden',
        //     'show_label'			=> 'true',
        //     'custom_attributes' 	=> array(
        //         'data-placeholder' 	=> __( 'Search or add a tag&hellip;', 'wcvendors-pro' ),
        //         'data-action'		=> 'wcv_json_search_tags',
        //         'data-multiple' 	=> 'true',
        //         'data-tags'			=> 'true',
        //         'data-selected'		=> esc_attr( json_encode( $tag_ids ) )
        //       ),
        //   ) )
        // );

        // WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_price', array(
        //   'post_id'		=> $post_id,
        //   'id' 			=> '_regular_price',
        //   'label' 		=> __( 'Regular Price', 'wcvendors-pro' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        //   'data_type' 	=> 'price',
        //   'wrapper_start' => $wrapper_start,
        //   'wrapper_end' 	=> '</div>',
        //   'custom_attributes' => array(
        //     'data-rules' => 'decimal',
        //     'data-error' => __( 'Price should be a number.', 'wcvendors-pro' )
        //
        //   )



      // 'wcv_product_sale_price', array(
			// 	'post_id'		=> $post_id,
			// 	'id' 			=> '_sale_price',
			// 	'data_type' 	=> 'price',
			// 	'label' 		=> __( 'Sale Price', 'wcvendors-pro' ) . ' ('.get_woocommerce_currency_symbol().')',
			// 	'desc_tip' 		=> 'true',
			// 	'description' 	=> '<a href="#" class="sale_schedule right">' . __( 'Schedule', 'wcvendors-pro' ) . '</a>',
			// 	'wrapper_start' => '<div class="all-50 small-100">',
			// 	'wrapper_end' 	=>  '</div></div>',
			// 	'custom_attributes' => array(
		 // 			'data-rules' => 'decimal',
		 // 			'data-error' => __( 'Sale price should be a number.', 'wcvendors-pro' )
      //
      //



}

// Using description as an example





  private function custom_user_login_redirect( $redirect_to, $request, $user ) {
  	//is there a user to check?
  	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
      var_dump($user->roles);exit;
      if ( in_array( 'vendor', $user->roles ) ) {
        // redirect them to the default place
        return '/kanal-paneli/';
      }


  		if ( in_array( 'administrator', $user->roles ) ) {
  			// redirect them to the default place
  			return $redirect_to;
  		} else {
  			return home_url();
  		}
  	} else {
  		return $redirect_to;
  	}
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






}
