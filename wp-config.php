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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'myportfolio' );

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
define( 'AUTH_KEY',         'lh24T%h&|+h*U>ONdFjSqtDj8DHp&wKAeHYtmBae!3@{U-z4I}nOWE2I w^-k&sU' );
define( 'SECURE_AUTH_KEY',  'h6)l~wf$(NGhR-k}0fD4>P{LkFWzx_k%W}m4)zbaftHxH}W;6dm)Oi(wAs)<[~2`' );
define( 'LOGGED_IN_KEY',    '1H,# ;nBE[~~g`/=XN- 3JFftnkdO>.HBI}D|5(U&[(&M=aTb^ba0~!{ p-el3/!' );
define( 'NONCE_KEY',        '!LqL-s3<a*9ji&0@+M_g}lJ:Y{=fB@dFz!sq(zKLfk}&2F^Ej62T8t}b_,1$9)te' );
define( 'AUTH_SALT',        ')wy1;YaM[UJBu}eJ+iaRT|ZNIdNI$B!,Q.Le)GEuaIHF[B)dyttuKO&6a;HqJ57-' );
define( 'SECURE_AUTH_SALT', 'W`h.@F)Q>z{Fvy<{f72)XB*CDPYqf]98e>{F.b<:Z+^;BML1A/z6ST8GS_8x9qUu' );
define( 'LOGGED_IN_SALT',   '=OlY1[S4EB_dAXuBD*^>4mgfl,=v4:5Wy(-+6b$K|lN[0~cx~blGG#b#RKAiQs(%' );
define( 'NONCE_SALT',       '_UUg+20}OfJy}X!m(..mi:P):ERwXCmk4#@lj[VY&sbm.OWqpHbq|4.Nb^yr=$^~' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
