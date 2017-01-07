<?php
add_filter( 'bp_after_has_members_parse_args', 'exclude_users_by_role');
add_filter( 'bp_get_total_member_count', 'get_active_member_count' );

// add_action( 'wp', 'hide_admins_profile', 1 );

// function hide_admins_profile() {
//   if( bp_is_user() ){
//     if ( bp_displayed_user_id() == 1 && bp_loggedin_user_id() != 1 ){
//       bp_core_redirect( home_url() );
//     }
//   }
// }

// add_action( 'bp_has_activities', 'hide_admin_activity', 10, 2 );


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


// Hide admin's activities from all activity feeds
function hide_admin_activity( $a, $activities ) {

  // ... but allow admin to see his activities!
  if ( is_super_admin() ){
    return $activities;
  }
  foreach ( $activities->activities as $key => $activity ) {
      // ID's to exclude, separated by commas. ID 1 is always the superadmin
      if ( $activity->user_id == 1  ) {
        unset( $activities->activities[$key] );
        $activities->activity_count = $activities->activity_count-1;
        $activities->total_activity_count = $activities->total_activity_count-1;
        $activities->pag_num = $activities->pag_num -1;
      }
    }
    // Renumber the array keys to account for missing items
    $activities_new = array_values( $activities->activities );
    $activities->activities = $activities_new;
    return $activities;
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
add_action( 'bp_setup_nav', 'bpex_primary_nav_tabs_position', 999 );


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
add_action( 'bp_setup_nav', 'profile_tab_favorites' );

function favorites_screen() {
    add_action( 'bp_template_content', 'favorites_content' );
    bp_core_load_template( 'members/single/plugins' );
}

function favorites_content() {
    echo '<article class="post" id="stardom-fav-content">';
    echo do_shortcode( '[rh_get_favorite_shops]');
    echo '</article>';
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
