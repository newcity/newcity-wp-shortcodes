<?php
/**
 * NewCity Custom Shortcodes Features
 *
 *
 * @since             0.1.0
 * @package           NewCity_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       NewCity Custom Shortcodes
 * Plugin URI: https://github.com/newcity/newcity-wp-shortcodes
 * Description:       Custom reusable tools and settings for NewCity clients
 * Version:           0.3.1-dev
 * Author:            NewCity  <geeks@insidenewcity.com>
 * Author URI:        http://insidenewcity.com
 * License:           NONE
 */


 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
     die;
 }


  if(!defined('NC_SHORTCODES_ABSPATH')) {
	define( 'NC_SHORTCODES_ABSPATH', plugin_dir_path( __FILE__ ) );
  }

require_once( dirname( __FILE__ ) . '/class-newcity-shortcodes.php');
require_once( dirname( __FILE__ ) . '/class-local-scripts-shortcode.php');

function newcity_shortcodes_run() {
	$options = get_option('newcity_shortcodes_options');

	
	if (is_admin() && current_user_can('activate_plugins')) {
		require_once(NC_SHORTCODES_ABSPATH . 'newcity-shortcodes-settings.php');
		$frontback_settings_path = new NewCityShortcodesSettings();
	}

	if( !isset($options['enabled_shortcodes'] ) || count($options['enabled_shortcodes']) === 0 ) return;

	if ( in_array( 'custom_blockquote', $options['enabled_shortcodes'] ) ) {
		$newcity_shortcodes = new NewCityShortcodes();
	}
	
	if ( in_array( 'local_script', $options['enabled_shortcodes'] ) ) {
		$newcity_local_scripts = new NewCityLocalScriptsShortcode();
	}

}

newcity_shortcodes_run();
