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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'travel' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Y>]Tb)(%iUwv_)[fb+?qT?3WL3m29P;U+wU9my+D*$~#_1>Zd3!&oMp[2q&@)nmL' );
define( 'SECURE_AUTH_KEY',  'QY9ZGx2tn1kOW8$.Xk~u:+].m[i7D_[VcYHb&RX@>CUI!UG|AQyVAnd0sKpQ?*j8' );
define( 'LOGGED_IN_KEY',    'yH$<s/TJ3KR_:yq*aj8z[I~7O;c(n_!gCM!2=<EW qyL~~=r5HZ)B=-CX0bE2nQ^' );
define( 'NONCE_KEY',        '(_dg9`&T6Dl8?h*m8k6M9tX%RF~To*U8MLZ##)^0UL:p;H5e#~|z]KmBm:FU8uML' );
define( 'AUTH_SALT',        'M{0M&*ZX90dm:dKM`5 Mmu1Hg3 q=j]dTynmle^&yd_A!Ba|ok?cT?xCW=PI#&ks' );
define( 'SECURE_AUTH_SALT', 'w?b)8_MuzNfIlc|Km(/sQJVD mUT}q`F)wh_^6hb~H_OF:>zl=[TI@|ct.} _Z2,' );
define( 'LOGGED_IN_SALT',   'Y12s[GjH|YdwR/lfT6w3i,oa!pj*53bqyo&B{W}[oyi-M3!18q919Pe(H=<NesDY' );
define( 'NONCE_SALT',       '?ZmC!O7gIYJV|Kn0NtMG{bLP2*$9.#t%6kNM]JD%6m$Z?)J0MA^KXPa^X]2n-ck[' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
