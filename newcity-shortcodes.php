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
 * Version:           0.2.0-dev
 * Author:            NewCity  <geeks@insidenewcity.com>
 * Author URI:        http://insidenewcity.com
 * License:           NONE
 */


 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
     die;
 }

require_once( dirname( __FILE__ ) . '/class-newcity-shortcodes.php');
require_once( dirname( __FILE__ ) . '/class-local-scripts-shortcode.php');

function newcity_shortcodes_run() {
	$newcity_shortcodes = new NewCityShortcodes();
	$newcity_local_scripts = new NewCityLocalScriptsShortcode();
}

newcity_shortcodes_run();
