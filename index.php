<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
 if(true){
 var_dump($_SERVER['RDS_HOSTNAME']);
 exit;

 	// $dbport = $_SERVER['RDS_PORT'];
 	// $dbname = $_SERVER['RDS_DB_NAME'];
 	//
 	// $username = $_SERVER['RDS_USERNAME'];
 	// $password = $_SERVER['RDS_PASSWORD'];

 }

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wordpress/wp-blog-header.php' );
