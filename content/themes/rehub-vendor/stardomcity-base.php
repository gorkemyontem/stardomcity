<?php
class StardomCityBase {

    function __construct() {
      $this->init();
    }

    private function init(){
      add_filter( 'product_type_options', array( $this, 'custom_product_type_options' ), 10);
      add_filter('product_type_selector', array( $this, 'custom_product_type_selector'), 10, 2);
      add_filter('product_type_options', array( $this, 'custom_product_type_options'), 10);
      //   add_filter( 'login_redirect', array( $this, 'custom_user_login_redirect' ), 10, 3 );  //static

      // add_filter( 'media_upload_newtab', array( $this, 'media_upload_callback' ) );  //instance
      // add_filter( 'media_upload_newtab', array( 'My_Class', 'media_upload_callback' ) );  //static
      // add_filter( 'the_title', function( $title ) { return '<strong>' . $title . '</strong>'; } ); //anonymous
    }

  // function custom_product_type_options( $product_type_options ) {
  // 	  $product_type_options['virtual'][ 'default' ] = 'yes';
  // 	  return $product_type_options;
  // }

  //Removes 'simple'   => __( 'Simple product', 'wcvendors-pro' ) ,  //'grouped'  => __( 'Grouped product', 'wcvendors-pro' ),	//external' => __( 'External/Affiliate product', 'wcvendors-pro' ),   //'variable' => __( 'Variable product', 'wcvendors-pro' ),
  function custom_product_type_selector($a, $b) {

  	$product_type = array();
  	return $product_type;
  }

  function custom_product_type_options() {
  	$product_type_options = array(
  			//'virtual' => array('id' => '_virtual', 'wrapper_class' => 'show_if_simple', 'label' => __( 'Virtual', 'wcvendors-pro' ), 'description'   => __( 'Virtual products are intangible and aren\'t shipped.', 'wcvendors-pro' ), 'default'       => 'no')
  			);
  	return $product_type_options;
  }


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
