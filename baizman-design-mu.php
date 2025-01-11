<?php
/**
 * Baizman Design Must-Use Plugin
 *
 * @author        Baizman Design
 * @package       Baizman Design MU
 * @version       1.0.13
 *
 * @wordpress-plugin
 * Plugin Name:   Baizman Design Must-Use Plugin
 * Plugin URI:    https://github.com/baizman-design/baizman-design-mu/
 * Description:   A must-use WordPress plugin containing constant definitions and general configuration settings used across my development environments.
 * Author:        Saul Baizman
 * Author URI:    https://baizmandesign.com
 * Version:       1.0.13
 * License:       GPLv3
 * Text Domain:   baizman-design-mu
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

namespace baizman_design_mu ;

class mu_plugin
{
	// plugin file
	private string $plugin_file = __FILE__;

	// slugs of plugins to forcibly disable.
	private array $disabled_plugins = [
		'sucuri-scanner', // Sucuri
		'updraftplus', // UpdraftPlus
		'comet-cache', // Comet Cache
		'sg-cachepress', // Speed Optimizer (SiteGround)
		'sg-security', // Security Optimizer (SiteGround)
		'wp-2fa', // WP 2FA - Two-factor authentication for WordPress
		'backwpup', // BackWPup
		'w3-total-cache', // W3 Total Cache
		'wordfence', // Wordfence
		'akismet', // Akismet
		];

	private const config_filename = '.baizman-design-mu.ini' ;

	private const user_disabled_plugins_filename = '.baizman-design-mu-disabled-plugins' ;

	private array $user_disabled_plugins = [];

	private string $autologin_email = '';

	private string $disabled_plugin_class = 'disabled_plugin';

	public function __construct()
	{

		$this->define_constants();

		$this->_load_config_file();

		// enable autologin
		add_action( 'init', [$this, 'autologin'], -PHP_INT_MAX );

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
		add_action('admin_head', [$this, 'add_admin_styles'] );

		// callback to remove "activate" link from plugin actions for disabled plugins
		add_filter( 'plugin_action_links', [$this, 'remove_activate_link'], 10, 2 );

		// callback to modify login screen #nav links.
		add_filter( 'lost_password_html_link', [$this, 'add_autologin_link'] );

		// callback to add styles to the login screen.
		add_action( 'login_enqueue_scripts', [$this, 'add_login_screen_styles'] );

	}

	/**
	 * Define some useful constants.
	 *
	 * @return void
	 */
	public function define_constants (): void
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

		// https://kinsta.com/blog/wp-query/
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
	}

	/**
	 * Look for auto query string argument. If present, try to log in as a user. On failure, forward to login screen.
	 *
	 * @return void
	 */
	public function autologin(): void
	{
		if ( ! empty( $_GET['auto'] ) ) {
			$user = get_user_by( 'email', $_GET['auto'] );
			if ( $user ) {
				wp_clear_auth_cookie();
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				wp_redirect( get_dashboard_url() );
			} else {
				wp_redirect( wp_login_url() );
			}
			exit ;
		}
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
		foreach ( $plugins as $plugin_index => $plugin ) {
			list ( $directory ) = explode ( DIRECTORY_SEPARATOR, $plugin );
			if (in_array ( $directory, $this->_get_disabled_plugins() ) ) {
				unset( $plugins[$plugin_index] ) ;
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
	public function add_disabled_notice ( $plugin_meta, $plugin_file ): array
	{
		list ( $directory ) = explode ( DIRECTORY_SEPARATOR, $plugin_file );
		if ( in_array ( $directory, $this->_get_disabled_plugins() ) ) {
			$plugin_meta[] = sprintf('<span class="%1$s">%2$s %3$s (%4$s)</span>',
			$this->disabled_plugin_class,
			__('Disabled by'),
			$this->_get_plugin_name(),
			// disabled by the user or the plugin/system?
			in_array($directory, $this->user_disabled_plugins) ? __('User') : __('System'),
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
	public function add_admin_styles(): void
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
	 * Add styles to the WordPress login screen.
	 *
	 * @return void
	 */
	public function add_login_screen_styles(): void
	{
		print('<style>
			/* boldface the "autologin" link. */
			body.login #nav a.autologin {
			  font-weight: bold;
			}
		</style>');
	}

	/**
	 * Remove "activate" link from plugin actions for disabled plugins.
	 *
	 * @param $plugin_actions
	 * @param $plugin_file
	 * @return array
	 */
	public function remove_activate_link ( $plugin_actions, $plugin_file ): array
	{
		list ( $directory ) = explode ( DIRECTORY_SEPARATOR, $plugin_file );
		if ( in_array ( $directory, $this->_get_disabled_plugins()) ) {
			unset ( $plugin_actions['activate'] ) ;
		}
		return $plugin_actions;
	}

	public function add_autologin_link ( $link ): string
	{
		if ( ! empty ($this->autologin_email) ){
			$link .= sprintf(' | <a class="autologin" href="%1$s/?auto=%2$s">Autologin</a>',
				get_home_url(),
				$this->autologin_email,
			) ;
		}
		return $link;
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

	/**
	 * Load configuration from a file.
	 *
	 * @return void
	 */
	private function _load_config_file (): void {
		// load deprecated file.
		$this->_load_deprecated_user_disabled_plugins_file ( ) ;
		$config_file_path = ABSPATH.self::config_filename ;
		if ( file_exists( $config_file_path ) ) {
			$config = parse_ini_file ( filename: $config_file_path, process_sections: true ) ;
			// add arrays of plugins in both files together.
			$this->user_disabled_plugins = array_merge ( $this->user_disabled_plugins, $config['disabled_plugins']['plugin'] ?? [] );
			// set autologin email address.
			$this->autologin_email = $config['autologin']['email'] ?? $this->autologin_email;
		}
	}


	/**
	 * Load user disabled plugins from a file. Deprecated.
	 *
	 * @return void
	 */
	private function _load_deprecated_user_disabled_plugins_file(): void
	{
		$user_disabled_plugins_path = ABSPATH.self::user_disabled_plugins_filename ;
		if (file_exists($user_disabled_plugins_path)){
			$user_disabled_plugins = file($user_disabled_plugins_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}
		$this->user_disabled_plugins = $user_disabled_plugins ?? [] ;
	}

	/**
	 * Get all disabled plugins.
	 *
	 * @return array
	 */
	private function _get_disabled_plugins():array
	{
		return array_merge ( $this->disabled_plugins, $this->user_disabled_plugins );
	}

}

new mu_plugin;
