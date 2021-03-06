<?php
/*
   Plugin Name: 3.1 - jQuery AJAX Example (Completed)
   Version: 1.0.0
   Author: Zac Gordon
   Author URI: https://twitter.com/zgordon
   Description: An example of how to do a simple AJAX call in WordPress
   Text Domain: jsforwp-jquery-ajax
   License: GPLv3
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );

$likes = get_option( 'jsforwp_likes' );
if ( null == $likes  ) {
  add_option( 'jsforwp_likes', 0 );
  $likes = 0;
}



function jsforwp_frontend_scripts() {

  wp_enqueue_script(
    'jsforwp-frontend-js',
    plugins_url( '/assets/js/frontend-main.js', __FILE__ ),
    ['jquery'],
    time(),
    true
  );

  // Change the value of 'ajax_url' to admin_url( 'admin-ajax.php' )
  // Change the value of 'total_likes' to get_option( 'jsforwp_likes' )
  // Change the value of 'nonce' to wp_create_nonce( 'jsforwp_likes_nonce' )
  wp_localize_script(
    'jsforwp-frontend-js',
    'jsforwp_globals',
    [
      'ajax_url'    => admin_url( 'admin-ajax.php' ),
      'total_likes' => get_option( 'jsforwp_likes' ),
      'nonce'       => wp_create_nonce( 'jsforwp_likes_nonce' )
    ]
  );
}
add_action( 'wp_enqueue_scripts', 'jsforwp_frontend_scripts' );


function jsforwp_add_like( ) {

  // Change the parameter of check_ajax_referer() to 'jsforwp_likes_nonce'
  check_ajax_referer( 'jsforwp_likes_nonce' );

  $likes = intval( get_option( 'jsforwp_likes' ) );
  $new_likes = $likes + 1;
  $success = update_option( 'jsforwp_likes', $new_likes );

  if( true == $success ) {
    $response['total_likes'] = $new_likes;
    $response['type'] = 'success';
  }

  $response = json_encode( $response );
  echo $response;
  die();

}
// Change 'wp_ajax_your_hook' to 'wp_ajax_jsforwp_add_like'
// Or change to 'wp_ajax_nopriv_your_hook' to 'wp_ajax_nopriv_jsforwp_add_like'
// Change 'your_hook' to 'jsforwp_add_like'
add_action( 'wp_ajax_jsforwp_add_like', 'jsforwp_add_like' );
add_action( 'wp_ajax_nopriv_jsforwp_add_like', 'jsforwp_add_like' );

require_once( 'assets/lib/plugin-page.php' );
