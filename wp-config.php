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

/*
 * cPanel & WHM® Site Software
 *
 * Core updates should be disabled entirely by the cPanel & WHM® Site Software
 * plugin, as Site Software will provide the updates.  The following line acts
 * as a safeguard, to avoid automatically updating if that plugin is disabled.
 *
 * Allowing updates outside of the Site Software interface in cPanel & WHM®
 * could lead to DATA LOSS.
 *
 * Re-enable automatic background updates at your own risk.
 */
define( 'WP_AUTO_UPDATE_CORE', false );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'rockst56_cloth');

/** MySQL database username */
define('DB_USER', 'rockst56_cloth');

/** MySQL database password */
define('DB_PASSWORD', '*57V24AgVVLF');

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
define('AUTH_KEY',         'aajXX6_8jz79eHKTJquCymAe_16nc92FJbzDPKXuQ_9IiN_hQR1kRCZ2osCA9kAj');
define('SECURE_AUTH_KEY',  '3pADBwudDYsKXAfLUYeY_xE5Es8H2BUmbH0hthsF3FAjLEF9xlyBx7E6e5iiEp3A');
define('LOGGED_IN_KEY',    'nxWVgR8reiiwsCrSTjwtdvAmFbZYxRu9tN49hvHWc_DzU9hSsNEIaQFKNZz4AjEU');
define('NONCE_KEY',        'W8XnoTXBP4oWfdUvJDdlX9jwHDRPbjOoHMf_I1n1DYmVEFqnb5RTTrZaSJ4pgYNP');
define('AUTH_SALT',        'V2pIKrB_zMpYDtyAp85Rs0tkROgd9Y8PPrKYRd4d9JPIVrNSF_uer5etNaNG6fIU');
define('SECURE_AUTH_SALT', 'VSWSXGX6_F8DF68lVss6MGTRqeVH6oct_poLvL36dedcXPEBKIC3jd3Gg2V1ZH7D');
define('LOGGED_IN_SALT',   '071NhiuPxttI90E_chta68DFpj3lZEd5NHbGnzC4q74iFEGk6UyYBliBeUD18JIN');
define('NONCE_SALT',       'u_UZKjAxAGFuf9DevuCn7lcY3tI_GZm9x2yizasX0hTNE41Tii1BzZeQR2SOGtEm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
