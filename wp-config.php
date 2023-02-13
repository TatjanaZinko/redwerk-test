<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * WordPress debugging mode
 * *
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */
define( 'DB_NAME', getenv('MYSQL_DATABASE') );

/** Database username */
define( 'DB_USER', getenv('MYSQL_USER') );

/** Database password */
define( 'DB_PASSWORD', getenv('MYSQL_PASSWORD') );

/** Database hostname */
define( 'DB_HOST', getenv('MYSQL_DB_HOST') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define( 'AUTH_KEY',         getenv('AUTH_KEY') );
define( 'SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY') );
define( 'LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY') );
define( 'NONCE_KEY',        getenv('NONCE_KEY') );
define( 'AUTH_SALT',        getenv('AUTH_SALT') );
define( 'SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') );
define( 'LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT') );
define( 'NONCE_SALT',       getenv('NONCE_SALT') );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv('TABLE_PREFIX') ? getenv('TABLE_PREFIX') : 'wp_';

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

// WP_DEBUG mode
define( 'WP_DEBUG', getenv('WP_DEBUG') );

// Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', getenv('WP_DEBUG_LOG') );

// Display of errors and warnings
define( 'WP_DEBUG_DISPLAY', getenv('WP_DEBUG_DISPLAY') );

@ini_set( 'display_errors', getenv('DISPLAY_ERRORS') ? 1 : 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', getenv('SCRIPT_DEBUG') );

/**
 * Multisite settings
 * 
 * A multisite network is a collection of sites that all share the same WordPress installation 
 * core files. They can also share plugins and themes. The individual sites in the network are
 * virtual sites in the sense that they do not have their own directories on your server,
 * although they do have separate directories for media uploads within the shared installation,
 * and they do have separate tables in the database. 
 * 
 * @see https://wordpress.org/support/article/create-a-network/
 */

// define('WP_ALLOW_MULTISITE', true );
// define('MULTISITE', true);
// define('SUBDOMAIN_INSTALL', false);
// define('DOMAIN_CURRENT_SITE', getenv('DOMAIN_CURRENT_SITE') );
// define('PATH_CURRENT_SITE', '/');
// define('SITE_ID_CURRENT_SITE', 1);
// define('BLOG_ID_CURRENT_SITE', 1);

/**
 * WP Cron settings
 * 
 * Cron is a standard UNIX utility for scheduling task execution (script or command) at a
 * specific time, date, or interval. A cron job is a task that it’s going to be executed.
 * Its sole purpose is to automate repetitive tasks so that you can use your time more
 * productively.
 * 
 * WordPress has its cron system for scheduling tasks. WP-Cron handles these tasks. Although
 * the main idea and name come from UNIX cron, WP-Cron works differently and instead uses
 * intervals for task scheduling.
 * 
 * @see https://blog.runcloud.io/external-cron-jobs-in-wordpress/
 * @see https://kinsta.com/knowledgebase/disable-wp-cron/
 */

define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') );

/* Add any custom values between this line and the "stop editing" line. */

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

/** Disable Automatic Update WordPress. */
define('WP_AUTO_UPDATE_CORE', getenv('WP_AUTO_UPDATE_CORE') );

define( 'FS_METHOD', 'direct' );
