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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'HomeID' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'C+O=2O$mkZ/<f1QYEsWdI0i#1n2HYt!x.lYmIG<_bSEPi8?Cja:Nalf@~>oYI*.Q' );
define( 'SECURE_AUTH_KEY',  'pBg>,bnQm_O+N8~m,JSpP`Mh=yAu!n8m318i8]Bv,*%v1=~-/_+vy7EI3%/Z!FxT' );
define( 'LOGGED_IN_KEY',    'eHr<nQyR5Q1|F,B_)y51`.L8<d9iJ&dagPa!M#P6R6IZ|l`*v!V`[C VC8n}}2UY' );
define( 'NONCE_KEY',        '$sMgJDF1&5geQXT3ZWG|ZDe~$ITTECWV!1O*bw4Oe=9etRch)^IodzERATs~}RI(' );
define( 'AUTH_SALT',        '~R %&3A{k?[N}Nr[j,6u_$;N[Or7w]y%tDZ!  mFY0L3qN+3TH }Y+HWz$JLLs9`' );
define( 'SECURE_AUTH_SALT', 'N8o{R-rl-F8OSOnZiFTBx$]pA>&p^9(7N%JHBzhOhmwM6{=CTpC;Eb4^0oUwX?wz' );
define( 'LOGGED_IN_SALT',   'w%abuSzZQipcYeoV[V6{2[]gxugYb8n#Zzz,Sa5Djna_|34U@R|EpPT`H4o9(uky' );
define( 'NONCE_SALT',       ':dFs-7H%/J`.VF<P6%,p%e%Wu&,6$SE1jl8/irX[zeA11Vy*2z?F4XJOBT=/-KvH' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_HomeID';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
