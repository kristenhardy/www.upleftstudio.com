<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'upleft');

/** MySQL database username */
define('DB_USER', 'upleft-admin');

/** MySQL database password */
define('DB_PASSWORD', 'c7UezxMLB7Fq4JSc');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~]vbs|lj9B4LW-|xh K?ZR~=n|*K9;`WD.J+)*|1>yp9b3b1#0@mwmWfJxsJYx9j');
define('SECURE_AUTH_KEY',  '>AJP0(l,IFg^q,u@@#`QaJVN-}?cL<p-=fb}RoqJnuxi0NjB=gpT?7^$-QK&eO!|');
define('LOGGED_IN_KEY',    '95+JBtPc;$Lt<Yb1_)%e@=SK%<B)@]X@U;l]sE77-GcDtmp/BM@J?Q@Jr-U6g,j[');
define('NONCE_KEY',        '5@s(YY oO-R$f`F3RR]#NUM0VGBB9^cdO8Kzj) [eRWC](O_dP|_@qC@;|^?-SE>');
define('AUTH_SALT',        '6A4zDd5@d0_pg6(3(%)(w-x=<MJf}i|;eV:yZs7oRU$.:N/VS:G<=bw~UM4+ydE<');
define('SECURE_AUTH_SALT', '3p^aEc]w=b+{[Bf>+z]4#W(D5?*Yn+shZ{WG84Sg>[n},oPhz|hlW#Ep:[K,<0j=');
define('LOGGED_IN_SALT',   'G|q|UhH.Ijq)4@SU`n>LLS2>mDh}rMC!XOi.`D$9yb|-&2f~wF|w_1;y2H+>,Tjv');
define('NONCE_SALT',       'YkxjF?Gzu=>Sf;tMeGw.vNd~Tbm)+pSS72Ab1_gc<a:1Pr6Tolv>,O4p9!o-Qyqo');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
