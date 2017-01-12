<?php

/*
 * Default size of avatar
 */
define ( 'BP_AVATAR_THUMB_WIDTH', 55 );
define ( 'BP_AVATAR_THUMB_HEIGHT', 55 );
define ( 'BP_AVATAR_FULL_WIDTH', 110 );
define ( 'BP_AVATAR_FULL_HEIGHT', 110 );

//remove starred messages
add_filter( 'bp_is_messages_star_active', '__return_false' );



/*
 * BP callback for the cover image feature.
 */
 if( ! function_exists( 'rh_cover_image_callback' ) ) :
	function rh_cover_image_callback( $params = array() ) {
		if ( empty( $params ) ) {
			return;
		}

		$cover_image = !empty( $params['cover_image'] ) ? 'background-image:url(' . $params['cover_image'] . ')' : 'background-image: url("'.get_template_directory_uri() . '/images/swirl_pattern.png"); background-repeat:  repeat;background-size: inherit;';
		return '
			/* Cover image */
			#rh-header-cover-image {'. $cover_image .'}';
	}
endif;

/* Call BP cover-image styles in head */
if( ! function_exists( 'rh_cover_image_css' ) ) :
	function rh_cover_image_css( $settings = array() ) {

		// If you are using a child theme, use bp-child-css as the theme handel

		$theme_handle = (is_rtl()) ? 'bp-parent-css-rtl' : 'bp-parent-css';

		$settings['theme_handle'] = $theme_handle;
		$settings['callback'] = 'rh_cover_image_callback';

		return $settings;
	}
	add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'rh_cover_image_css', 10, 1 );
	add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'rh_cover_image_css', 10, 1 );
endif;


/* Custom Tabs for User`s Profile */
if( ! function_exists( 'rh_content_setup_nav_profile' ) ) :
	function rh_content_setup_nav_profile() {
		global $bp;
		$userid = (!empty($bp->displayed_user->id)) ? $bp->displayed_user->id : '';
		if($userid){
			$totaldeals = count_user_posts( $userid, $post_type = 'product' );
			$totalposts = count_user_posts( $userid, $post_type = 'post' );
			$total = $totaldeals + $totalposts;
			$class    = ( 0 === $total ) ? 'no-count' : 'count';
		}
		else {
			$class = 'hiddencount';
			$total = '';
		}
		if(REHUB_NAME_ACTIVE_THEME == 'REVENDOR'){
			$position_posttab = 40;
			$position_posts = 20;
			$position_product = 10;
			$post_text = sprintf( __( 'My products <span class="%s">%s</span>', 'rehub_framework' ), esc_attr( $class ), $total  );
			$default_tab = 'deals';
			$userpostslabel = (rehub_option('rehub_userposts_text') !='') ? rehub_option('rehub_userposts_text') : __( 'Reviews', 'rehub_framework' );
			$userdealslabel = (rehub_option('rehub_userdeals_text') !='') ? rehub_option('rehub_userposts_text') : __( 'Products', 'rehub_framework' );
		}
		else{
			$userpostslabel = (rehub_option('rehub_userposts_text') !='') ? rehub_option('rehub_userposts_text') : __( 'Deals', 'rehub_framework' );
			$userdealslabel = (rehub_option('rehub_userdeals_text') !='') ? rehub_option('rehub_userposts_text') : __( 'Products', 'rehub_framework' );
			$position_posttab = 40;
			$position_posts = 10;
			$position_product = 20;
			$default_tab = 'articles';
			$post_text = sprintf( __( 'My Posts <span class="%s">%s</span>', 'rehub_framework' ), esc_attr( $class ), $total  );
		}


		bp_core_new_nav_item( array(
			'name' => $post_text,
			'slug' => 'posts',
			'screen_function' => 'my_posts_screen_link',
			'position' => $position_posttab,
			'default_subnav_slug' => $default_tab
		) );
		bp_core_new_subnav_item( array(
			'name' => $userpostslabel,
			'slug' => 'articles',
			'parent_url' => $bp->displayed_user->domain . 'posts/',
			'parent_slug' => 'posts',
			'screen_function' => 'articles_screen_link',
			'position' => $position_posts
		) );
		bp_core_new_subnav_item( array(
			'name'                  => $userdealslabel,
			'slug'                  => 'deals',
			'parent_url'            => $bp->displayed_user->domain . 'posts/',
			'parent_slug'           => 'posts',
			'screen_function'       => 'deals_screen_link',
			'position'              => $position_product
		) );
	do_action( 'rh_content_setup_nav_profile' );
	}
	add_action( 'bp_setup_nav', 'rh_content_setup_nav_profile' );
endif;

function articles_screen_link() {

	function articles_screen_content() {
		?>
		<div id="posts-list" class="bp-post-wrapper posts">

			<?php
				$containerid = 'rh_deallist_' . uniqid();
				$infinitescrollwrap = ' re_aj_pag_clk_wrap';
				$show = $ajaxoffset = 12;
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => 12,
					'author' => bp_displayed_user_id(),
					);
			    $loop = new WP_Query($args);
			?>
			<?php if ( $loop->have_posts() ) : ?>
				<?php
					$jsonargs = json_encode($args);
				?>
				<div class="wpsm_recent_posts_list <?php  echo $infinitescrollwrap;?>" data-filterargs='<?php echo $jsonargs;?>' data-template="simplepostlist" id="<?php echo $containerid;?>">

					<?php while ( $loop->have_posts() ) : $loop->the_post();  ?>
						<?php include(locate_template('inc/parts/simplepostlist.php')); ?>
					<?php endwhile; ?>

					<div class="re_ajax_pagination"><span data-offset="<?php echo $ajaxoffset;?>" data-containerid="<?php echo $containerid;?>" class="re_ajax_pagination_btn def_btn"><?php _e('Next posts', 'rehub_framework') ?></span></div>

				</div>
				<div class="clearfix"></div>
			<?php endif; wp_reset_query(); ?>

		</div><!--/.posts-->
	<?php
	}

    add_action( 'bp_template_content', 'articles_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function deals_screen_link() {


	function deals_screen_content() {
		if ( class_exists( 'Woocommerce' ) ) {
		?>
		<div id="posts-list" class="bp-post-wrapper posts">

			<?php
				$containerid = 'rh_woocolumn_' . uniqid();
				$infinitescrollwrap = ' re_aj_pag_clk_wrap';
				$show = $ajaxoffset = 8;
				$columns = '4_col';
				$additional_vars = array();
				$additional_vars['columns'] = $columns;
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 8,
					'author' => bp_displayed_user_id(),
					);
			    $loop = new WP_Query($args);
			?>
			<?php if ( $loop->have_posts() ) : ?>
				<?php
					$jsonargs = json_encode($args);
					$json_innerargs = json_encode($additional_vars);
				?>
				<div class="woocommerce">
				<div class="rh-flex-eq-height column_woo products col_wrap_fourth <?php  echo $infinitescrollwrap;?>" data-filterargs='<?php echo $jsonargs;?>' data-template="woocolumnpart" data-innerargs='<?php echo $json_innerargs;?>' id="<?php echo $containerid;?>">

					<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
						<?php include(locate_template('inc/parts/woocolumnpart.php')); ?>
					<?php endwhile; ?>

					<div class="re_ajax_pagination"><span data-offset="<?php echo $ajaxoffset;?>" data-containerid="<?php echo $containerid;?>" class="re_ajax_pagination_btn def_btn"><?php _e('Next posts', 'rehub_framework') ?></span></div>

				</div>
				</div>
				<div class="clearfix"></div>
			<?php endif; wp_reset_query(); ?>

		</div><!--/.posts-->
		<?php
		}
	}

    add_action( 'bp_template_content', 'deals_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

 /* Get the Resized Cover Image URL otherwise Background styled URL */
 if( ! function_exists( 'rh_cover_image_url' ) ) :
	function rh_cover_image_url( $object_dir, $height, $background = false ) {

		if( $object_dir == 'members' ) {
			$item_id = bp_get_member_user_id();
		} elseif( $object_dir == 'groups' ) {
			$item_id = bp_get_group_id();
		} else {
			$item_id = 0;
		}

		$get_cover_image_url = bp_attachments_get_attachment('url', array(
			'object_dir' => $object_dir,
			'item_id' => $item_id
		) );
		$resized_cover_image_url = '';

		if( $get_cover_image_url ) {

 		$resized_cover_image = new WPSM_image_resizer();
		$resized_cover_image->src = $get_cover_image_url;
        $resized_cover_image->height = $height;

		$resized_cover_image_url = $resized_cover_image->get_resized_url();
		}

		if( $background && $resized_cover_image_url ) {
			$cover_image_inline_style = 'background-image:url('. $resized_cover_image_url .')';
			echo $cover_image_inline_style;
		} else {
			echo $resized_cover_image_url;
		}
	}
endif;


if( ! function_exists( 'rh_nologedin_add_buttons' ) ) :
	function rh_nologedin_add_buttons() {
		if( ! is_user_logged_in() && rehub_option('userlogin_enable') == '1') {
		?>
			<div class="generic-button">
				<a href="#" title="Add Friend" rel="add" class="act-rehub-login-popup friendship-button"><?php _e( 'Add Friend', 'budypress' ); ?></a>
			</div>
			<div class="generic-button">
				<a href="#" title="Send a private message to this user." class="act-rehub-login-popup send-message"><?php echo __( 'Private Message', 'buddypress' ); ?></a>
			</div>
		<?php
		}
	}
	add_action( 'bp_member_header_actions', 'rh_nologedin_add_buttons', 10, 0 );
endif;

if( ! function_exists( 'rh_nologedin_add_buttons_group' ) ) :
	function rh_nologedin_add_buttons_group() {
		if( ! is_user_logged_in() && rehub_option('userlogin_enable') == '1') {
		?>
			<div class="generic-button">
				<a href="#" title="Join Group" rel="add" class="act-rehub-login-popup"><?php echo __( 'Join Group', 'budypress' ); ?></a>
			</div>
		<?php
		}
	}
	add_action( 'bp_group_header_actions', 'rh_nologedin_add_buttons_group', 10, 0 );
endif;

if (rehub_option('bp_deactivateemail_confirm') == 1){
	add_filter( 'bp_registration_needs_activation', '__return_false' );
}

if (!function_exists('rh_show_vendor_store_in_bp')) {
function rh_show_vendor_store_in_bp() {
	$vendor_id = bp_displayed_user_id();
	$label = __('Owner of shop:', 'rehub_framework');
	rh_show_vendor_ministore($vendor_id, $label);
}}
if (defined('wcv_plugin_dir')) {
add_action( 'bp_after_member_header', 'rh_show_vendor_store_in_bp' );
}

if(!bp_is_active( 'activity' ) && REHUB_NAME_ACTIVE_THEME == 'REVENDOR'){
	define('BP_DEFAULT_COMPONENT', 'profile' );
}

add_post_type_support( 'product', 'buddypress-activity' );
function rh_customize_product_tracking_args() {
    // Check if the Activity component is active before using it.
    if ( ! bp_is_active( 'activity' ) ) {
        return;
    }

    bp_activity_set_post_type_tracking_args( 'product', array(

        'action_id'                => 'new_product',
        'bp_activity_admin_filter' => __( 'Published a new product', 'rehub_framework' ),
        'bp_activity_front_filter' => __( 'Products', 'rehub_framework' ),
        'contexts'                 => array( 'activity', 'member' ),
        'activity_comment'         => true,
        'bp_activity_new_post'     => __( '%1$s posted a new <a href="%2$s">product</a>', 'rehub_framework' ),
        'bp_activity_new_post_ms'  => __( '%1$s posted a new <a href="%2$s">product</a>, on the site %3$s', 'rehub_framework' ),
        'position'                 => 100,
    ) );
}
add_action( 'bp_init', 'rh_customize_product_tracking_args' );

add_filter('bp_get_messages_content_value', 'rh_custom_message_placeholder_in_bp_message' );
function rh_custom_message_placeholder_in_bp_message(){
	if(!empty($_GET['ref'])){
		$content = __('I am interesting in: ', 'rehub_framework').urldecode($_GET['ref']);
		$content = esc_html($content);
	}
	elseif(!empty( $_POST['content'] )){
		$content = $_POST['content'];
	}
	else{
		$content = '';
	}
	return $content;
}






///// STARDOMCITY
add_filter( 'bp_after_has_members_parse_args', 'exclude_users_by_role');
add_filter( 'bp_get_total_member_count', 'get_active_member_count' );
add_action( 'bp_setup_nav', 'bpex_primary_nav_tabs_position', 999 );
add_action( 'bp_setup_nav', 'profile_tab_favorites' );
add_action( 'bp_setup_nav', 'profile_tab_vendor_dashboard_settings' );

// add_action( 'wp', 'hide_admins_profile', 1 );

// function hide_admins_profile() {
//   if( bp_is_user() ){
//     if ( bp_displayed_user_id() == 1 && bp_loggedin_user_id() != 1 ){
//       bp_core_redirect( home_url() );
//     }
//   }
// }

global $roles_to_exclude;
$roles_to_exclude =  [ 'administrator', 'test'];

function exclude_users_by_role( $args ) {
  //do not exclude in admin
  if( is_admin() && ! defined( 'DOING_AJAX' ) ) {
      return $args;
  }
  $excluded = isset( $args['exclude'] ) ? $args['exclude'] : array();
  if( !is_array( $excluded ) ) {
      $excluded = explode(',', $excluded );
  }
  global $roles_to_exclude;
  $user_ids =  get_users( array( 'role__in' => $roles_to_exclude ,'fields'=>'ID') );
  $excluded = array_merge( $excluded, $user_ids );
  $args['exclude'] = $excluded;
  return $args;
}

function get_active_member_count($bp_core_get_active_member_count ){
  global $roles_to_exclude;
  $user_ids =  get_users( array( 'role__in' => $roles_to_exclude ,'fields'=>'ID') );
  return $bp_core_get_active_member_count - count($user_ids);
}


function bpex_primary_nav_tabs_position() {
  //change_buddypress_profile_nav
  if( bp_is_members_component() || bp_is_user() ) {
    $wcv_profile_id   = bp_displayed_user_id();
    $wcv_profile_info = get_userdata( bp_displayed_user_id() );
    if ( isset($wcv_profile_info->roles[0]) && $wcv_profile_info->roles[0] != "vendor" ) {
      bp_core_remove_nav_item('posts');
    }
  }

	buddypress()->members->nav->edit_nav( array( 'position' => 10,), 'profile');
  buddypress()->members->nav->edit_nav( array( 'position' => 20,), 'vendor-dashboard');
  buddypress()->members->nav->edit_nav( array( 'position' => 30,), 'posts');
  buddypress()->members->nav->edit_nav( array( 'position' => 40,), 'messages');
	buddypress()->members->nav->edit_nav( array( 'position' => 50,), 'notifications');
  buddypress()->members->nav->edit_nav( array( 'position' => 60,), 'following');
  buddypress()->members->nav->edit_nav( array( 'position' => 70,), 'followers');
  buddypress()->members->nav->edit_nav( array( 'position' => 80,), 'settings');
}

function profile_tab_favorites() {
      global $bp;
      $display = false;
      if ( is_super_admin() || bp_is_my_profile() ){
        $display = true;
      }

      bp_core_new_nav_item( array(
            'name' => 'Favorites',
            'slug' => 'favorites',
            'screen_function' => 'favorites_screen',
            'position' => 60,
            'parent_url'      => bp_displayed_user_domain () . '/favorites/',
            'parent_slug'     => $bp->profile->slug,
            'default_subnav_slug' => 'favorites',
            'show_for_displayed_user' => $display
      ) );

      bp_core_new_subnav_item( array(
      	'name'            => 'Favorilerim',
      	'slug'            => 'favorites',
      	'parent_url'      => bp_displayed_user_domain() . '/favorites/',
      	'parent_slug'     => 'favorites',
      	'screen_function' => 'favorites_screen',
      	'position'        => 60,
    	) );
}

function favorites_screen() {
    add_action( 'bp_template_content', 'favorites_content' );
    bp_core_load_template( 'members/single/plugins' );
}

function favorites_content() {
    echo '<article class="post" id="stardom-fav-content">';
    echo do_shortcode( '[rh_get_favorite_shops]');
    echo '</article>';
}



function profile_tab_vendor_dashboard_settings() {


    bp_core_remove_subnav_item( 'settings', 'notifications' );
    bp_core_remove_subnav_item( 'settings', 'profile' );
    //  bp_core_remove_subnav_item( 'vendor-dashboard', 'vendor-dashboard-settings' );

    bp_core_new_subnav_item( array(
    	'name'            => 'Store',
    	'slug'            => 'store',
    	'parent_url'      => bp_displayed_user_domain() . '/settings/',
    	'parent_slug'     => 'settings',
    	'screen_function' => 'store_screen',
    	'position'        => 10,
      'user_has_access' => bp_is_my_profile()
  	) );

    bp_core_new_subnav_item( array(
      'name'            => 'Payment',
      'slug'            => 'payment',
      'parent_url'      => bp_displayed_user_domain() . '/settings/',
      'parent_slug'     => 'settings',
      'screen_function' => 'payment_screen',
      'position'        => 20,
      'user_has_access' => bp_is_my_profile()
    ) );

    bp_core_new_subnav_item( array(
      'name'            => 'Branding',
      'slug'            => 'branding',
      'parent_url'      => bp_displayed_user_domain() . '/settings/',
      'parent_slug'     => 'settings',
      'screen_function' => 'branding_screen',
      'position'        => 30,
      'user_has_access' => bp_is_my_profile()
    ) );

    bp_core_new_subnav_item( array(
      'name'            => 'Social',
      'slug'            => 'social',
      'parent_url'      => bp_displayed_user_domain() . '/settings/',
      'parent_slug'     => 'settings',
      'screen_function' => 'social_screen',
      'position'        => 40,
      'user_has_access' => bp_is_my_profile()
    ) );


}


function store_screen() {
    add_action( 'bp_template_content', 'custom_all_settings');
    bp_core_load_template( 'members/single/plugins' );
}

function payment_screen() {
    add_action( 'bp_template_content', 'custom_all_settings' );
    bp_core_load_template( 'members/single/plugins' );
}

function branding_screen() {
    add_action( 'bp_template_content', 'custom_all_settings' );
    bp_core_load_template( 'members/single/plugins' );
}

function social_screen() {
    add_action( 'bp_template_content', 'custom_all_settings' );
    bp_core_load_template( 'members/single/plugins' );
}

function custom_all_settings(){
  global $bp;
  global $stardomBase;
  $vendor_id = get_current_user_id();
  $store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
  $store_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
  ?>

  <form method="post" action="" class="wcv-form wcv-formvalidator">

  <?php WCVendors_Pro_Store_Form::form_data(); ?>

    <div class="wcv-tabs top" data-prevent-url-change="true">


  	<!-- Store Settings Form -->
      <?php
      if($bp->current_action  != 'store'){
        echo '<span style="visibility: hidden; position:absolute; bottom:9999px;">';
      }
      ?>
  		<!-- Store Name -->
  		<?php WCVendors_Pro_Store_Form::store_name( $store_name ); ?>

  		<?php do_action( 'wcvendors_settings_after_shop_name' ); ?>

  		<!-- Store Description -->
  		<?php WCVendors_Pro_Store_Form::store_description( $store_description ); ?>

  		<?php do_action( 'wcvendors_settings_after_shop_description' ); ?>
  		<br />

  		<!-- Seller Info -->
  		<?php WCVendors_Pro_Store_Form::seller_info( ); ?>

  		<?php do_action( 'wcvendors_settings_after_seller_info' ); ?>

  		<br />

  		<!-- Company URL -->
  		<?php do_action( 'wcvendors_settings_before_company_url' ); ?>
  		<?php WCVendors_Pro_Store_Form::company_url( ); ?>
  		<?php do_action(  'wcvendors_settings_after_company_url' ); ?>

  		<!-- Store Phone -->
  		<?php do_action( 'wcvendors_settings_before_store_phone' ); ?>
  		<?php WCVendors_Pro_Store_Form::store_phone( ); ?>
  		<?php do_action(  'wcvendors_settings_after_store_phone' ); ?>

  		<!-- Store Address -->
  		<?php //do_action( 'wcvendors_settings_before_address' ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address_country( ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address1( ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address2( ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address_city( ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address_state( ); ?>
  		<?php //WCVendors_Pro_Store_Form::store_address_postcode( ); ?>
  		<?php //do_action(  'wcvendors_settings_after_address' ); ?>

  		<!-- Store Vacation Mode -->
  		<?php do_action( 'wcvendors_settings_before_vacation_mode' ); ?>
  		<?php WCVendors_Pro_Store_Form::vacation_mode( ); ?>
  		<?php do_action(  'wcvendors_settings_after_vacation_mode' ); ?>

      <?php
      if($bp->current_action  != 'store'){
        echo "</span>";
      }
      ?>

      <?php
      if($bp->current_action  != 'payment'){
        echo '<span style="visibility: hidden; position:absolute; bottom:9999px;">';
      }
      ?>
  		<!-- Paypal address -->
  		<?php do_action( 'wcvendors_settings_before_paypal' ); ?>

  		<?php WCVendors_Pro_Store_Form::paypal_address( ); ?>

  		<?php do_action( 'wcvendors_settings_after_paypal' ); ?>
      <?php
      if($bp->current_action  != 'payment'){
        echo "</span>";
      }
      ?>

      <?php
      if($bp->current_action  != 'branding'){
        echo '<span style="visibility: hidden; position:absolute; bottom:9999px;">';
      }
      ?>
  		<?php do_action( 'wcvendors_settings_before_branding' ); ?>

  		<!-- Store Banner -->
  		<?php WCVendors_Pro_Store_Form::store_banner( ); ?>

  		<!-- Store Icon -->
  		<?php WCVendors_Pro_Store_Form::store_icon( ); ?>

  		<?php do_action( 'wcvendors_settings_after_branding' ); ?>
      <?php
      if($bp->current_action  != 'branding'){
        echo "</span>";
      }
      ?>


      <?php
      if($bp->current_action  != 'social'){
        echo '<span style="visibility: hidden; position:absolute; bottom:9999px;">';
      }
      ?>
  	<?php
    $settings_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );
    $social_total 		= count( $settings_social );
    $social_count = 0;
    foreach ( $settings_social as $value) { if ( 1 == $value ) $social_count +=1;  }

    if ( $social_count != $social_total ) :  ?>


  			<?php do_action( 'wcvendors_settings_before_social' ); ?>

        <!-- Scorp -->
        <?php $stardomBase->profile_scorp_username(); ?>
        <!-- Snapchat -->
        <?php WCVendors_Pro_Store_Form::snapchat_username( ); ?>
  			<!-- Instagram -->
  			<?php WCVendors_Pro_Store_Form::instagram_username( ); ?>
        <!-- Twitter -->
        <?php WCVendors_Pro_Store_Form::twitter_username( ); ?>
        <!-- Twitch -->
        <?php $stardomBase->profile_twitch_username(); ?>
  			<!-- Facebook -->
  			<?php WCVendors_Pro_Store_Form::facebook_url( ); ?>
        <!-- Youtube URL -->
        <?php WCVendors_Pro_Store_Form::youtube_url( ); ?>
  			<!-- Linked in -->
  			<?php WCVendors_Pro_Store_Form::linkedin_url( ); ?>
  			<!-- Pinterest URL -->
  			<?php WCVendors_Pro_Store_Form::pinterest_url( ); ?>
  			<!-- Google+ URL -->
  			<?php WCVendors_Pro_Store_Form::googleplus_url( ); ?>

  			<?php do_action(  'wcvendors_settings_after_social' ); ?>

  	<?php endif; ?>

    <?php
    if($bp->current_action  != 'store'){
      echo "</span>";
    }
    ?>
  	</div>
  		<!-- Submit Button -->
  		<!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
  		<?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
  	</form>
 <?php
}




function custom_store_settings() {
  $vendor_id = get_current_user_id();
  $store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
  $store_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
  ?>
  <form method="post" action="" class="wcv-form wcv-formvalidator">

  <?php WCVendors_Pro_Store_Form::form_data();

  WCVendors_Pro_Store_Form::store_name( $store_name );

  do_action( 'wcvendors_settings_after_shop_name' );

  WCVendors_Pro_Store_Form::store_description( $store_description );

  do_action( 'wcvendors_settings_after_shop_description' );
  ?>
  <br />
  <?php
  WCVendors_Pro_Store_Form::seller_info( );


  do_action( 'wcvendors_settings_after_seller_info' );
  ?>
  <br />
  <?php
  do_action( 'wcvendors_settings_before_company_url' );
  WCVendors_Pro_Store_Form::company_url( );
  do_action(  'wcvendors_settings_after_company_url' );

  do_action( 'wcvendors_settings_before_store_phone' );
  WCVendors_Pro_Store_Form::store_phone( );
  do_action(  'wcvendors_settings_after_store_phone' );

  do_action( 'wcvendors_settings_before_address' );
  WCVendors_Pro_Store_Form::store_address_country( );
  WCVendors_Pro_Store_Form::store_address1( );
  WCVendors_Pro_Store_Form::store_address2( );
  WCVendors_Pro_Store_Form::store_address_city( );
  WCVendors_Pro_Store_Form::store_address_state( );
  WCVendors_Pro_Store_Form::store_address_postcode( );
  do_action(  'wcvendors_settings_after_address' );

  do_action( 'wcvendors_settings_before_vacation_mode' );
  WCVendors_Pro_Store_Form::vacation_mode( );
  do_action(  'wcvendors_settings_after_vacation_mode' );
  WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') );
  ?>
  </div>
  </form>
  <?php
}
function custom_payment_settings() {
  ?>
  <form method="post" action="" class="wcv-form wcv-formvalidator">

  <?php WCVendors_Pro_Store_Form::form_data();

  do_action( 'wcvendors_settings_before_paypal' );

  WCVendors_Pro_Store_Form::paypal_address( );

  do_action( 'wcvendors_settings_after_paypal' );

  WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
  </div>
  	</form>
  <?php
}
function custom_branding_settings() {
  ?>
  <form method="post" action="" class="wcv-form wcv-formvalidator">

  <?php WCVendors_Pro_Store_Form::form_data();

  do_action( 'wcvendors_settings_before_branding' );

  WCVendors_Pro_Store_Form::store_banner( );

  WCVendors_Pro_Store_Form::store_icon( );

  do_action( 'wcvendors_settings_after_branding' );

  WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
  </div>
  	</form>
  <?php
}
function custom_social_settings() {
  $vendor_id = get_current_user_id();
  $store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );

  ?>
  <form method="post" action="" class="wcv-form wcv-formvalidator">

  <?php WCVendors_Pro_Store_Form::form_data();
  $settings_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );
  $social_total 		= count( $settings_social );
  $social_count = 0;
  foreach ( $settings_social as $value) {
    if ( 1 == $value )
    $social_count +=1;
  }

  echo "<div style=\"visibility: hidden;height: 0px;\">";
    WCVendors_Pro_Store_Form::store_name( $store_name );
  echo "</div>";
  if ( $social_count != $social_total ) :

      do_action( 'wcvendors_settings_before_social' );
      WCVendors_Pro_Store_Form::youtube_url( );
      WCVendors_Pro_Store_Form::snapchat_username( );
      WCVendors_Pro_Store_Form::instagram_username( );
      WCVendors_Pro_Store_Form::facebook_url( );
      WCVendors_Pro_Store_Form::twitter_username( );
      WCVendors_Pro_Store_Form::pinterest_url( );
      WCVendors_Pro_Store_Form::linkedin_url( );
      WCVendors_Pro_Store_Form::googleplus_url( );
      do_action( 'wcvendors_settings_after_social' ); ?>
  <?php endif; ?>
  <?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
  </div>
  	</form>
  <?php
}











  // define( 'BP_FOLLOWING_SLUG', 'takip-ettiklerim' );
  // define( 'BP_FOLLOWERS_SLUG', 'takip-edenler' );
  // define ( 'BP_ACTIVITY_SLUG', 'haber-akisi' );
  // define( 'BP_SETTINGS_SLUG', 'ayarlar' );
  // define ( 'BP_MESSAGES_SLUG', 'mesajlar' );
  // define ( 'BP_XPROFILE_SLUG', 'profil' );


  // define ( 'BP_BLOGS_SLUG', '2' );
  // define ( 'BP_MEMBERS_SLUG', '3' );
  // define ( 'BP_FRIENDS_SLUG', '4' );
  // define ( 'BP_GROUPS_SLUG', '5' );
  // define ( 'BP_WIRE_SLUG', '6' );
  //
  // /* Some other non-component slugs */
  // define ( 'BP_REGISTER_SLUG', 'signup' );
  // define ( 'BP_ACTIVATION_SLUG', 'enable' );
  // define ( 'BP_SEARCH_SLUG', '8' );
  // define ( 'BP_HOME_BLOG_SLUG', '9' );
