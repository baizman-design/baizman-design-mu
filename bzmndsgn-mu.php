<?php

/**
 * Common constants used across my development environments.
 * @package Baizman_Design_MU
 * @version 1.0.0
 */

/*
Constants that don't work in this file / must be located in wp-config.php:
	+ WP_DEBUG
	+ WP_DEBUG_LOG
	+ WP_DEBUG_DISPLAY
	+ SCRIPT_DEBUG
	+ WP_MEMORY_LIMIT
	+ WP_MAX_MEMORY_LIMIT
	+ WP_CACHE
*/

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

// # https://kinsta.com/blog/wp-query/
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

if ( ! defined ( 'EMPTY_TRASH_DAYS' ) ) { 
	define( 'EMPTY_TRASH_DAYS', 10 );
}

if ( ! defined ( 'IMAGE_EDIT_OVERWRITE' ) ) { 
	define( 'IMAGE_EDIT_OVERWRITE', true);
}

if ( ! defined ( 'AUTOMATIC_UPDATER_DISABLED' ) ) { 
	define( 'AUTOMATIC_UPDATER_DISABLED', true );
}

if ( ! defined ( 'CONCATENATE_SCRIPTS' ) ) { 
	define( 'CONCATENATE_SCRIPTS', false );
}

if ( ! defined ( 'ALLOW_UNFILTERED_UPLOADS' ) ) { 
	define( 'ALLOW_UNFILTERED_UPLOADS', true ) ;
}