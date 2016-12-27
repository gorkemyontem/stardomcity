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

// function setup_hooks() {
//
//   // Only take action in admin if not super-admin
//   if ( is_super_admin() )
//     return;
//
//   // Remove menu order and separator hooks
//   remove_action( 'admin_menu',        'bbp_admin_separator'         );
//   remove_action( 'custom_menu_order', 'bbp_admin_custom_menu_order' );
//   remove_action( 'menu_order',        'bbp_admin_menu_order'        );
//
//   // Turn UI off in admin
//   add_filter( 'bbp_register_forum_post_type', array( $this, 'filter_post_type' ), 4 );
//   add_filter( 'bbp_register_topic_post_type', array( $this, 'filter_post_type' ), 4 );
//   add_filter( 'bbp_register_reply_post_type', array( $this, 'filter_post_type' ), 4 );
// }
//
// function filter_post_type( $args ) {
//   $args['show_in_nav_menus'] = $args['show_ui'] = $args['can_export'] = false;
//
//   return $args;
// }
