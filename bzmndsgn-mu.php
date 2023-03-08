<?php

/**
 * Common constants used across my development environments.
 * @package Baizman_Design_MU
 * @version 1.0.0
 */

if ( ! defined ( 'WP_DEBUG' ) ) { 
	define( 'WP_DEBUG', true );
}

if ( ! defined ( 'WP_DEBUG_LOG' ) ) { 
 define( 'WP_DEBUG_LOG', 'debug.log' );
}

if ( ! defined ( 'WP_DEBUG_DISPLAY' ) ) { 
	define( 'WP_DEBUG_DISPLAY', true );
}

if ( ! defined ( 'JETPACK_STAGING_MODE' ) ) { 
	define( 'JETPACK_STAGING_MODE', true );
}

if ( ! defined ( 'WP_AUTO_UPDATE_CORE' ) ) { 
	define( 'WP_AUTO_UPDATE_CORE', false );
}

// https://kinsta.com/blog/wp-config-php/
if ( ! defined ( 'WP_POST_REVISIONS' ) ) { 
	define( 'WP_POST_REVISIONS', false );
}

if ( ! defined ( 'SCRIPT_DEBUG' ) ) { 
	define( 'SCRIPT_DEBUG', true );
}

// # https://kinsta.com/blog/wp-query/
if ( ! defined ( 'SAVEQUERIES' ) ) { 
	define( 'SAVEQUERIES', true );
}

if ( ! defined ( 'SAVEQUERIES' ) ) { 
	define( 'SAVEQUERIES', true );
}

if ( ! defined ( 'WP_LOCAL_DEV' ) ) { 
	define( 'WP_LOCAL_DEV', true );
}

if ( ! defined ( 'DISABLE_WP_CRON' ) ) { 
	define( 'DISABLE_WP_CRON', true );
}

if ( ! defined ( 'WP_ENVIRONMENT_TYPE' ) ) { 
	define( 'WP_ENVIRONMENT_TYPE', 'development' );
}

if ( ! defined ( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) ) { 
	define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true);
}

if ( ! defined ( 'DISALLOW_FILE_EDIT' ) ) { 
	define( 'DISALLOW_FILE_EDIT', true );
}

if ( ! defined ( 'WP_MEMORY_LIMIT' ) ) { 
	define( 'WP_MEMORY_LIMIT', '100M' ) ;
}

if ( ! defined ( 'WP_MAX_MEMORY_LIMIT' ) ) { 
	define( 'WP_MAX_MEMORY_LIMIT', '256M' );
}

if ( ! defined ( 'EMPTY_TRASH_DAYS' ) ) { 
	define( 'EMPTY_TRASH_DAYS', 10 );
}

// "This constant has effect only if you install a persistent caching plugin."
if ( ! defined ( 'WP_CACHE' ) ) { 
	define( 'WP_CACHE', true);
}

if ( ! defined ( 'IMAGE_EDIT_OVERWRITE' ) ) { 
	define( 'IMAGE_EDIT_OVERWRITE', true);
}

if ( ! defined ( 'AUTOMATIC_UPDATER_DISABLED' ) ) { 
	define( 'AUTOMATIC_UPDATER_DISABLED', true );
}
