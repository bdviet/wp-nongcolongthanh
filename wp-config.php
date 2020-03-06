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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'non57551_web' );

/** MySQL database username */
define( 'DB_USER', 'non57551_web' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Admin@123' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '>7[t~AMbzl,qOJefgxAgG]wV$XX.T%S_:,#Jco==7aN:II6Mn$z]z9/0^dLVk{j<' );
define( 'SECURE_AUTH_KEY',   '~w67OLy^~x}{$hXH>mS,:e<W]nj+yg^{bzA]nlyB@&.G3Us4eQN#+[DsE+T+P#rO' );
define( 'LOGGED_IN_KEY',     'KS$q%!z&B^,UeRJWk$Z0_^?.iKqagk+-{N&{lWd7e e+2.^7FnO (x-K]JZxmI.t' );
define( 'NONCE_KEY',         'n0~sOS<96c0YW:w|,d:{/3fxtRaKmXL$zmXtTnAZ6FK~z7iaWUDVwp(0~Fsq~JH ' );
define( 'AUTH_SALT',         'L2[(DIRjvu#D+Gr`7nX]|eQ0ZS*(S4^,>VB*H@{qIl#y:GzA#DMzL-i4 t&1z%}D' );
define( 'SECURE_AUTH_SALT',  'dV.Oju8j0vro_&`ojh@:EckxZC32P+&9j~r*Xl7.?|6L6X0(F:Yrmf98!NJ;1!1d' );
define( 'LOGGED_IN_SALT',    'aLu|>X(NckNNPo2(_kf@r3=8_mo >N{Y9~9y7Nq+@)Wf4zFmT21eku(%`5;~Q#a%' );
define( 'NONCE_SALT',        '[N,:{gr,NR#g54G[-M#*E&$D%=6upB+qolDF:8u|%OyV^KCzbWFN}-RrOc{VYI:5' );
define( 'WP_CACHE_KEY_SALT', 'J-Wb,=`x<;<.&n<3#|l%Z&{0kmc6pAXRPwH&;1f}<kOi?b6QYK1G>ngOyNOWpr5a' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


define( 'FORCE_SSL_ADMIN', false );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
