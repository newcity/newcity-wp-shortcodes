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
 * Version:           0.1.2-dev
 * Author:            NewCity  <geeks@insidenewcity.com>
 * Author URI:        http://insidenewcity.com
 * License:           NONE
 */


 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
     die;
 }

require_once( __DIR__ . '/class-newcity-shortcodes.php');
new NewCityShortcodes();
