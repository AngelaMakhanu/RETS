<?php
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/research/public_html/wp-content/plugins/wp-super-cache/' );
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
//**define( 'FS_METHOD', 'direct' ); **//
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'research_research' );

/** MySQL database username */
define( 'DB_USER', 'research_root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Research2020' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'WP_MEMORY_LIMIT', '256M' );



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Gr,~C/9-2te*Ebtah:^ iLnO7=lJHx^hUpk4[z9_0pX.i$xAi~Id35~a]>IRhgl*' );
define( 'SECURE_AUTH_KEY',  'S`:`)O:F/@]9i}F8Jj-u pV63jk}]H=>,at*`eTqM[5sp)z8*V@hW=V}P0T[9Vv;' );
define( 'LOGGED_IN_KEY',    'Uanclram?7&ZObvLGW#FkAg L9vS<B$<=%T-o1=ZCquW_ Q*U)w2pKT2*m8[IT%)' );
define( 'NONCE_KEY',        'F;fD3ifQGWDr!DPn^&J`NK,h;rj_*cMNX ]vZdYBWE7AbEBd1]5t0o<Zb[{bi20u' );
define( 'AUTH_SALT',        'xTzEn%2 p.;G[fO1bsR`UT%hK5Y3Ey>Oejf;JfLR*rIoRFaf!DwEn:m#<XxVk~P@' );
define( 'SECURE_AUTH_SALT', ':){2GaZ-J],X4QTa-D@Q9:ykiX:r+R_!ZjO^Dtaw),*gbzh%d?nV[zVp@<e<e*?.' );
define( 'LOGGED_IN_SALT',   'dsb[nSj81=S$ t.)sUCR6y@-URY!Lbp$*N}Y?.;XIQR!!E<V0|PDjqbw;%6-ROm,' );
define( 'NONCE_SALT',       'Z.3*nU$%MXcqzO5%[LfL],}s>1Nns*x1 ~RluL:6]l)m>!2mKNB9<*IFyxsc6pk>' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'rh7x_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
