<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'library_db' );

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
define( 'AUTH_KEY',         '<7Or?U7)#|/:z3c&}(s~`k[Os.wc8#CI1& u/v~V%BPP0!mQtq[V&*v[>wE+:fuh' );
define( 'SECURE_AUTH_KEY',  'f&_`*r~PA6o}5KWA=(fwF(RjI]F&6H)fg45hMh[kM>-YV wD.#e[zUmjw`QT8D?C' );
define( 'LOGGED_IN_KEY',    '%KH9YO.Dh4e^0_Gl=}ys:.&~U&@K(6}|-[$&(v-/-q[OFn8~jCCFF$gQenr!8z3:' );
define( 'NONCE_KEY',        'zqv>F3U>h!NX/t Hzv3<zu3a~vFoNWKes qvn=>%GMT[5r/YMX#_~|DJt e2{+eO' );
define( 'AUTH_SALT',        ' |r[t-IaNp@GX?trG,k<RLw?BrVLOaFN<[[rbd|$eyNcWhuREML/Ofj9W_HJ{IFg' );
define( 'SECURE_AUTH_SALT', '|L%.=[&>DkdEq8~SXROL[*4~%5z5= 7o9yW}eI>T.i^xgqk.S%<Dgtd}2G[Bd51d' );
define( 'LOGGED_IN_SALT',   'pzp#.*63%7j~dwM&dPUlbtO/1,K,,fLq(5B`;W+gi *Eni3,6FY(6u*S1,CM~m2R' );
define( 'NONCE_SALT',       'G6kY;aE&7zYvRC(.?p@U#?|A}::x*|lD&z$Wo!7I-hTef#D~Z{P^H)toLuiW,-?f' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
