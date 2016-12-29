<?php

define( 'BP_FOLLOWING_SLUG', 'takip-ettiklerim' );
define( 'BP_FOLLOWERS_SLUG', 'takip-edenler' );

// define ( 'BP_ACTIVITY_SLUG', 'streams' );
// define ( 'BP_BLOGS_SLUG', 'journals' );
// define ( 'BP_MEMBERS_SLUG', 'users' );
// define ( 'BP_FRIENDS_SLUG', 'peeps' );
// define ( 'BP_GROUPS_SLUG', 'gatherings' );
// define ( 'BP_MESSAGES_SLUG', 'notes' );
// define ( 'BP_WIRE_SLUG', 'pinboard' );
// define ( 'BP_XPROFILE_SLUG', 'info' );
//
// /* Some other non-component slugs */
// define ( 'BP_REGISTER_SLUG', 'signup' );
// define ( 'BP_ACTIVATION_SLUG', 'enable' );
// define ( 'BP_SEARCH_SLUG', 'find' );
// define ( 'BP_HOME_BLOG_SLUG', 'news' );

add_filter( 'bp_after_has_members_parse_args', 'exclude_users_by_role');
add_filter( 'bp_get_total_member_count', 'get_active_member_count' );

add_action( 'wp', 'hide_admins_profile', 1 );
add_action( 'bp_has_activities', 'hide_admin_activity', 10, 2 );
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

function hide_admins_profile() {
  if( bp_is_user() ){
    if ( bp_displayed_user_id() == 1 && bp_loggedin_user_id() != 1 ){
      bp_core_redirect( home_url() );
    }
  }
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
