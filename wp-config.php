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
define( 'DB_NAME', 'db_wordpress' );

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

define( 'FS_METHOD', 'direct' );

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
define( 'AUTH_KEY',         '[%`x;k]0q.T)p~N:`Ry>o%|P~:$Hmx4xBF.$}HIV7f&8DUdXckV2`s6ADHhP[;#W' );
define( 'SECURE_AUTH_KEY',  'Yw],7bBBxNc>RhUsduS^*$iqD5-Bavvhte>G)BO=YiXe&~=)E4^y4L-zq{z9*Tn)' );
define( 'LOGGED_IN_KEY',    'f&Il]xx@$W0lbBxiKM,X?x3qMj<qN&>.#{Qx-v|@^ r3l(YZvb.szP{JlK6/A2G[' );
define( 'NONCE_KEY',        '`M`&4|J;m?vF}W<MFm ;`{:;{[k#x80kz]Yx>kcS,Ad[{=&(9@~KnC1j%[yaH>5j' );
define( 'AUTH_SALT',        'MM{WxcNxsK1M0|$+((h*(J7+YV^;<Y2|X),L[>}MG}86`1Ld@ee-LeDxMJ8VcDbt' );
define( 'SECURE_AUTH_SALT', '7)1J_}r^!Cy;1w::>qb}u q-MO.:*%]]FRmuT@(48c<r]Ycr{: =D2 fn%#(^T&B' );
define( 'LOGGED_IN_SALT',   '4qK4-MUp#F>WPxU]AIHSO3#,37%?ODsy=f~qhl/X_[%iTRH(Bl9 {Oz.4Xm&A`2m' );
define( 'NONCE_SALT',       'jc!Jm|w,aihYKpy}M]b#hvw%SsOxb[i>iK^1:{7Dv(xH*zqyZ<kML.rnc!@{D3;X' );

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
