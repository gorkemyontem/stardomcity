<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

 $location = 'en';

if($_SERVER['Env'] == 'Prod'){
  define('DB_NAME', $_SERVER['RDS_DB_NAME']);
  define('DB_USER', $_SERVER['RDS_USERNAME']);
  define('DB_PASSWORD', $_SERVER['RDS_PASSWORD']);
  define('DB_HOST', $_SERVER['RDS_HOSTNAME']);

  ini_set( 'display_errors', 0 );
  define( 'WP_DEBUG_DISPLAY', false );

} else if($_SERVER['Env'] == 'Test') {

  define('DB_NAME', $_SERVER['RDS_DB_NAME']);
  define('DB_USER', $_SERVER['RDS_USERNAME']);
  define('DB_PASSWORD', $_SERVER['RDS_PASSWORD']);
  define('DB_HOST', $_SERVER['RDS_HOSTNAME']);

  define( 'SAVEQUERIES', true );
  define( 'WP_DEBUG', true );
} else {
  define('DB_NAME', 'StardomCityEnTestDb');
  define('DB_USER', 'root');
  define('DB_PASSWORD', '');
  define('DB_HOST', 'localhost');

  define( 'SAVEQUERIES', true );
  define( 'WP_DEBUG', true );
}

define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ========================
// Custom Content Directory
// ========================
//define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . './../content' );
define( 'WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/content' );

define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-core' );
define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME'] . '/' . $location );
define('WP_DEFAULT_THEME', 'rehub-vendor');

// ========================
// Custom Cookie Settings
// ========================
define('COOKIEHASH', 'g5ca99bac8d');
define('COOKIE_DOMAIN', $_SERVER['SERVER_NAME']);
define('SITECOOKIEPATH', '/' . $location . '/');
define('COOKIEPATH', '/wp-core/');
define('ADMIN_COOKIE_PATH', COOKIEPATH . 'wp-admin');

// define('ADMIN_COOKIE_PATH', '/');
// define('COOKIE_DOMAIN', '');
// define('COOKIEPATH', '');
// define('SITECOOKIEPATH', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8z!5!A4n}9<29E]3E9X G%=Ez[7j,2IicUS?deQc -B#`3*.z$3nOLIZ#7eU!NB[');
define('SECURE_AUTH_KEY',  'wC~4m2Hw^CUFjL]Y=p 4;S-E}na RorskF,<5<VI2k}?*-#pEzhv0xGC]3NNgU{Z');
define('LOGGED_IN_KEY',    'ybl^[q3@KKa]A|DUchiP>j=/HRq=0*n0^cvV14= `ID|cdR69k~TwdvPIX<0wOG@');
define('NONCE_KEY',        '[@piZ!xllgn]:k8HI!%KrHeQ[u(;@8o9%0s:>n&}uo X]T ]S$`*TW,rN-]_OivE');
define('AUTH_SALT',        '(>Cw/w(@|DHmrr~8_[4uD8R!CRyG]zcc-<$D5@orcm[6F:$hHLL>LLinT#XPx6kk');
define('SECURE_AUTH_SALT', '9Os`MP*fKAj8xA<z,SgC6-84CJ/`j_lf4!?&1eHhe0:dz8Z7#+[jB7,w$@kd*2We');
define('LOGGED_IN_SALT',   'ZcTQkF5*JP/ST@9}u2bV*0o%_0Hn|*7j]ysx^XCND!&[Bt4vO>SJYHG]It?+B**y');
define('NONCE_SALT',       'G6xe:u[iQVuO!}Z<&c^)O!hoY`c$e4Gtvb}d$$mM=4.s)p@`#U-!Xzrj^5R>OTZH');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_str_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . './../wp-core/');

define( 'UPLOADS', './../' . $location . '/uploads' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');