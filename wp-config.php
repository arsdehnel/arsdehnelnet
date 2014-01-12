<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'arsdehnelnet');

/** MySQL database username */
define('DB_USER', 'arsdehnelnet');

/** MySQL database password */
define('DB_PASSWORD', '1arsdehnelNET');

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
define('AUTH_KEY',         'yyLzcd~9- WVDkLeXqKZ6oF16GxS!rFF*D7CMrv|K!xx8qy-`:wicHO%jLaGT|=t');
define('SECURE_AUTH_KEY',  'q0EKXt/T>iAK{Z[EX)_@We%I9+G[!sttdST89G[Cl+Tp+L60P* ?4koEooqFe@ *');
define('LOGGED_IN_KEY',    'zp|<KWAmIoO:`pGG! _E0%$-cso@b2bdw]D cc4azJD%h(K(Mt.jtd}]N6j2R(X*');
define('NONCE_KEY',        '<%Ij)P6S:Q0uRLj(r#%!ul6u+eX|,-YruUsJDLk+(x/eW|f-7J{N5vvEgUrm}t+P');
define('AUTH_SALT',        'j)VIIZaN0IJ%r||YrLg/-h}a9?JR<gw+v.~k?-f:Bo?)s}Qv+-/yaz@-$f|tVlL{');
define('SECURE_AUTH_SALT', '-fW05CeiNy3|N:p5c3Rfp|AF?y+Y`TdGq-REF7<OI+=xx5&AP}c4HSKbj4`,[VT|');
define('LOGGED_IN_SALT',   'q_5)BwZc{28Ar51W!`K>E9-*>hdi{8>@S;l?6-afeO|y*.xc%7|?(,!wrTxs974d');
define('NONCE_SALT',       'z@!#rJ0|)}B,.|LpQx$d__?|N/gp@>|50kzrcOK)+g+aj5JTCR$rJ_J-{/._s=]f');

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
