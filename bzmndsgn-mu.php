<?php
/**
 * Baizman Design Must-Use Plugin
 *
 * @author        Baizman Design
 * @package       Baizman_Design_MU
 * @version       1.0.4
 *
 * @wordpress-plugin
 * Plugin Name:   Baizman Design Must-Use Plugin
 * Plugin URI:    https://bitbucket.org/baizmandesign/bzmndsgn-mu/
 * Description:   A must-use WordPress plugin containing constant definitions and general configuration settings used across my development environments.
 * Author:        Saul Baizman
 * Author URI:    https://baizmandesign.com
 * Version:       1.0.4
 * License:       GPLv3
 * Text Domain:   bzmndsgnmu
 */

/*
Some constants must be located in wp-config.php and have no effect in a must-use plugin:
+ WP_DEBUG
+ WP_DEBUG_LOG
+ WP_DEBUG_DISPLAY
+ SCRIPT_DEBUG
+ WP_MEMORY_LIMIT
+ WP_MAX_MEMORY_LIMIT
+ WP_CACHE
+ WP_DEVELOPMENT_MODE
*/

namespace baizman_design_mustuse ;

class mu_plugin
{
	// plugin file
	private string $plugin_file = __FILE__;

	// paths of plugins to forcibly disable.
	private array $disabled_plugins = [
		'sucuri-scanner/sucuri.php', // Sucuri
		'updraftplus/updraftplus.php', // UpdraftPlus
		'comet-cache/comet-cache.php', // Comet Cache
		'sg-cachepress/sg-cachepress.php', // Speed Optimizer (SiteGround)
		'sg-security/sg-security.php', // Security Optimizer (SiteGround)
		'wp-2fa/wp-2fa.php', // WP 2FA - Two-factor authentication for WordPress
		'backwpup/backwpup.php', // BackWPup
		'w3-total-cache/w3-total-cache.php', // W3 Total Cache
		];

	private string $disabled_plugin_class = 'disabled_plugin';

	public function __construct()
	{

		if (!defined('JETPACK_STAGING_MODE')) {
			define('JETPACK_STAGING_MODE', true);
		}

		if (!defined('WP_AUTO_UPDATE_CORE')) {
			define('WP_AUTO_UPDATE_CORE', false);
		}

		// https://kinsta.com/blog/wp-config-php/
		if (!defined('WP_POST_REVISIONS')) {
			define('WP_POST_REVISIONS', false);
		}

		// # https://kinsta.com/blog/wp-query/
		if (!defined('SAVEQUERIES')) {
			define('SAVEQUERIES', true);
		}

		if (!defined('WP_LOCAL_DEV')) {
			define('WP_LOCAL_DEV', true);
		}

		if (!defined('DISABLE_WP_CRON')) {
			define('DISABLE_WP_CRON', true);
		}

		if (!defined('WP_ENVIRONMENT_TYPE')) {
			define('WP_ENVIRONMENT_TYPE', 'local');
		}

		if (!defined('WP_DISABLE_FATAL_ERROR_HANDLER')) {
			define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
		}

		if (!defined('DISALLOW_FILE_EDIT')) {
			define('DISALLOW_FILE_EDIT', true);
		}

		if (!defined('EMPTY_TRASH_DAYS')) {
			define('EMPTY_TRASH_DAYS', 10);
		}

		if (!defined('IMAGE_EDIT_OVERWRITE')) {
			define('IMAGE_EDIT_OVERWRITE', true);
		}

		if (!defined('AUTOMATIC_UPDATER_DISABLED')) {
			define('AUTOMATIC_UPDATER_DISABLED', true);
		}

		if (!defined('CONCATENATE_SCRIPTS')) {
			define('CONCATENATE_SCRIPTS', false);
		}

		if (!defined('ALLOW_UNFILTERED_UPLOADS')) {
			define('ALLOW_UNFILTERED_UPLOADS', true);
		}

		// https://toolset.com/documentation/programmer-reference/debugging-sites-built-with-toolset/
		// Alternative debugging method
		// define('TOOLSET_LOGGING_STATUS', 'info');
		//
		// Advanced Debug Information
		// define('TOOLSET_LOGGING_STATUS', 'debug');

		// disable all emails.
		// @link https://wordpress.stackexchange.com/questions/302176/how-to-disable-all-wordpress-emails-modularly-and-programatically
		add_filter('wp_mail', function ($args) {
			unset ($args['to']);
			return $args;
		} );

		// disable administration email verification screen.
		// @link https://www.wpbeginner.com/wp-tutorials/how-to-disable-wordpress-admin-email-verification-notice/
		add_filter('admin_email_check_interval', '__return_false');

		// callback to forcibly disable select plugins.
		add_filter( 'option_active_plugins', [$this, 'disable_plugins'] );

		// callback to modify plugin data on plugins page.
		add_filter( 'plugin_row_meta', [$this, 'add_disabled_notice'], 10, 2 );

		// callback to add styles to dashboard.
		add_action('admin_head', [$this, 'admin_styles'] );

		// callback to remove "activate" link from plugin actions for disabled plugins
		add_filter( 'plugin_action_links', [$this, 'remove_activate_link'], 10, 2 );

	}

	/**
	 * Forcibly disable select plugins.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/option_option/
	 * @link https://github.com/mklasen/alcedo-wordpress/blob/master/app/www/content/mu-plugins/manage-plugins.php
	 *
	 * TODO (maybe): use deactivate_plugins() function instead of unsetting the array value.
	 *
	 * @param array $plugins
	 * @return array
	 */
	public function disable_plugins( array $plugins ): array
	{
		if ( count( $this->disabled_plugins ) > 0 ) {
			foreach ( $plugins as $plugin_index => $plugin_path ) {
				if (in_array ( $plugin_path, $this->disabled_plugins ) ) {
					unset( $plugins[$plugin_index] ) ;
				}
			}
		}
		return $plugins;
	}

	/**
	 * Add disabled notice to plugins that have been forcibly disabled.
	 *
	 * @param $plugin_meta
	 * @param $plugin_file
	 * @return array
	 */
	public function add_disabled_notice ($plugin_meta, $plugin_file ): array
	{
		if ( in_array (  $plugin_file, $this->disabled_plugins) ) {
			$plugin_meta[] = sprintf('<span class="%1$s">%2$s %3$s</span>',
			$this->disabled_plugin_class,
			__('Disabled by'),
			$this->_get_plugin_name(),
			);
		}
		return $plugin_meta;
	}


	/**
	 * Add styles to dashboard.
	 *
	 * @link https://css-tricks.com/snippets/wordpress/apply-custom-css-to-admin-area/
	 * @return void
	 */
	public function admin_styles(): void
	{
	  printf('<style>
		span.%1$s {
		  font-weight: bold;
		  color: rgba(265,165,0,1);
		}
	  </style>',
	  $this->disabled_plugin_class,
	  );
	}

	/**
	 * Remove "activate" link from plugin actions for disabled plugins.
	 *
	 * @param $plugin_actions
	 * @param $plugin_file
	 * @return array
	 */
	public function remove_activate_link ($plugin_actions, $plugin_file ): array
	{
		if (in_array ( $plugin_file, $this->disabled_plugins) ) {
			unset ( $plugin_actions['activate'] ) ;
		}
		return $plugin_actions;
	}

	/**
	 * Get the plugin name.
	 *
	 * @link https://developer.wordpress.org/reference/functions/get_plugin_data/
	 *
	 * @return string
	 */
	private function _get_plugin_name (): string
	{
		$plugin_data = get_plugin_data ($this->plugin_file) ;
		return $plugin_data['Name'] ; // note: array key is not 'Plugin Name'
	}

}

new mu_plugin ( ) ;
