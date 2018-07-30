<?php

/**
 *
 * Defines custom TimberPost extensions
 *
 * @since      0.1.0
 *
 * @package    NewCityCustom
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NewCityLocalScriptsShortcode {

	public function __construct() {
		add_shortcode( 'local_script', array( 'NewCityLocalScriptsShortcode', 'local_script' ), 7 );
	}

    public static function local_script( $attr ) {
		$attr = wp_parse_args(
			$attr, array(
				'source' => '',
			)
		);

		wp_enqueue_script( 'nc_local_script_' . $attr['script'], get_stylesheet_directory_uri() . '/local-scripts/' . $attr['script'] . '.js', '', true );
		return '';
	}

}
