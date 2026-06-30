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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'winetwork_wp_ggd79' );

/** Database username */
define( 'DB_USER', 'winetwork_wp_6no3j' );

/** Database password */
define( 'DB_PASSWORD', 'AIG4@DO~qio7H?a4' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY', 'z87/bE9uI|r_hW&6sOW4~O-(6Fk#|3HmSt/S@/HuJaVl46L70V|j95f1_HR8+)/s');
define('SECURE_AUTH_KEY', '378V#-uJY6/2Zv22SF63:PusO_Qt#a*z1T*9|xTa3SCUJ%DQZkf57Y_Va_6TKZ*[');
define('LOGGED_IN_KEY', '/!*@*G&H6q!c1UYIo0QW(83+tHM65&Cf1%RY5&/#[@9hI)kvs#-*(3Gu/jExGG/O');
define('NONCE_KEY', 'l:m0;R|G0j)jI56Q(Q6r4T:7G0BARo99pjx8@:!Idy+nn-jv+69;-#]6HUbg1sPR');
define('AUTH_SALT', 'Z[eh8w#E5z:;7+74T-_3T98s)MK2YOjZCy%6jOgi)#@U4AXW379Jwu4qtby9+09f');
define('SECURE_AUTH_SALT', 'VAB-IDe11@5p8anL:s_s1DE*[LyR@(781K;!2p);ak0m(~;oHh)(y0T8GUtkt888');
define('LOGGED_IN_SALT', '72(3N(y0!5cY*6+E/4iV(0qSm]Cq60]]W8|:;00UD|7YK;L2sgL~36ld687X*FD8');
define('NONCE_SALT', 's9Ym*9U4INF]:qC[01/U51Cq0X1w~]yJRm20T16NFB2P260*&7796SA|]NR-XtR!');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wi2Mpriq_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
