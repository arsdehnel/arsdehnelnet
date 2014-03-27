<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
 
//define environments and their respective tld
$environments = array(
	'dev'	=> 'dev',
	'test'  => 'test',
	'prod'	=> 'net'
); 

//localize/cache the server name
$tld = array_pop( explode( '.', $_SERVER['SERVER_NAME'] ) );

//assign the environment
foreach($environments AS $key => $env){
	if( $tld == $env ):
		define('ENVIRONMENT', $key);
		break;
	endif;
}

switch( ENVIRONMENT ):
	case "dev":
		define('DB_NAME', 'arsdehnelnet');
		define('DB_USER', 'arsdehnelnet');
		define('DB_PASSWORD', '1arsdehnelNET');
		define('DB_HOST', 'localhost:3306');
		define('DB_CHARSET', 'utf8');
		define('DB_COLLATE', '');
		break;
	case "prod":
		define('DB_NAME', 'arsdehnelnet');
		define('DB_USER', 'arsdehnelnet');
		define('DB_PASSWORD', '3a4d7a2mD!');
		define('DB_HOST', 'localhost:3306');
		define('DB_CHARSET', 'utf8');
		define('DB_COLLATE', '');
		break;
endswitch;

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
 
//these are production
/*
define('AUTH_KEY',         'm$rMBWtJmp61BC)lPX3)4LlJL3Bolps@)BuxYJmvWJU1r!qiy@Yh*OjcLgpm9PaU');
define('SECURE_AUTH_KEY',  'PvVCW^4pF&swnzrn3eCUV6z2e3ap#XL7Bd1094VoW%3DDB#k^hSej&n#amawq4)v');
define('LOGGED_IN_KEY',    'xUa#i&i3s1Anpkfj5&FSQgaI11p^3e60pLH1T55Ap#$NiT*KujARUzZ0$C5WUk$1');
define('NONCE_KEY',        'B&Tps17DbAqL2tqpg&eFNwF@m)$)%tg)Ln3CVsESb#Ze3v5op1$K@QGe4bgsAlIH');
define('AUTH_SALT',        '%l6vXwUt4PHYksdP&NHSTg!guauo4$6crfIV)4(&nQHATcy#UjCgqW0jj&dqriI0');
define('SECURE_AUTH_SALT', '7j^Vv#dEEB6UO&wVx)HXe8(39ph@7LZ)3S%rjGtFxg&k)p@g)dJer275jtjw^lgb');
define('LOGGED_IN_SALT',   'qXRDrV8@yt3!C9IYzBsFK)@DS7BsfNBaa)YOnb(wf7MvxvAQO*c6#I5Ot!U@pzav');
define('NONCE_SALT',       'sgS1hz6uZhe)f2EuAN93cP5Q3Xl#NeI6d@i$op1cvWvr3HEM2@uLKGXoQj9ub!Nx');
*/

//these are dev
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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'en_US');

define ('FS_METHOD', 'direct');

define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

//--- disable auto upgrade
define( 'AUTOMATIC_UPDATER_DISABLED', true );



?>

