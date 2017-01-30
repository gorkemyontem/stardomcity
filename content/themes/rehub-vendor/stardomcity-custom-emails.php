<?php
class StardomCityCustomEmails {

  function __construct() {
    $this->init();
  }

  public function init(){
    add_action( 'woocommerce_email_header', function(){ add_filter( "better_wc_email", "__return_true" ); } );
    add_action( 'woocommerce_email_header', function(){ ob_start(); }, 1 );
    add_action( 'woocommerce_email_header', function(){ ob_get_clean(); }, 100 );
    add_action( 'woocommerce_email_footer', function(){ ob_start(); }, 1 );
    add_action( 'woocommerce_email_footer', function(){ ob_get_clean(); }, 100 );
    add_filter('phpmailer_init', array($this, 'better_phpmailer_init'),20);
    add_filter('comment_notification_text', array($this, 'custom_comment_notification_text'), 10, 2);
  }

  // Selectively apply WPBE template if it's a WooCommerce email
  public function better_phpmailer_init( $phpmailer ){
      // this filter will return true if the woocommerce_email_header action has run
      if ( apply_filters( 'better_wc_email', false ) ){
          global $wp_better_emails;
          // Add template to message
          $phpmailer->Body = $wp_better_emails->set_email_template( $phpmailer->Body );
          // Replace variables in email
          $phpmailer->Body = apply_filters( 'wpbe_html_body', $wp_better_emails->template_vars_replacement( $phpmailer->Body ) );
      }
  }


  public function custom_comment_notification_text($notify_message, $comment_id){
    $comment = get_comment($comment_id);
    $post = get_post($comment->comment_post_ID);
		$notify_message  = sprintf( __( 'New comment on your post "%s"' ), $post->post_title ) . "\r\n";
		$notify_message .= sprintf( __( 'Author: %1$s' ), $comment->comment_author ) . "\r\n";
		$notify_message .= sprintf( __( 'Email: %s' ), $comment->comment_author_email ) . "\r\n";
		$notify_message .= sprintf( __('Comment: %s' ), "\r\n" . $comment->comment_content ) . "\r\n\r\n";
		$notify_message .= __( 'You can see all comments on this post here:' ) . "\r\n";
   	$notify_message .= get_permalink($comment->comment_post_ID) . "#comments\r\n\r\n";
   	$notify_message .= sprintf( __('Permalink: %s'), get_comment_link( $comment ) ) . "\r\n";
    return $notify_message;
  }
}
