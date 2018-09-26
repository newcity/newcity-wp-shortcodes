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
		add_action( 'register_shortcode_ui', array($this, 'shortcode_ui_local_script') );
	}

    public static function local_script( $attr ) {
		$default_script_path = 'js';
		$options = get_option('newcity_shortcodes_options', false);
		$attr = wp_parse_args(
			$attr, array(
				'source' => '',
				'path' => $options['script_path']
			)
		);

		$path = $attr['path'] ? $attr['path'] : $default_script_path;

		wp_enqueue_script( 'nc_local_script_' . str_replace('/', '_', $path) . '_' . $attr['script'], get_stylesheet_directory_uri() . '/' . $path . '/' . $attr['script'] . '.js', '', true );
		return '';
	}

	function shortcode_ui_local_script() {
		$fields = array(
			array(
				'label'       => esc_html__( 'Script Name (without .js)', 'shortcode-ui-local-script', 'shortcode-ui' ),
				'attr'        => 'script',
				'type'        => 'text',
				'encode'	  => true
			),
		);

		$shortcode_ui_args = array(
			/*
			* How the shortcode should be labeled in the UI. Required argument.
			*/
			'label' => esc_html__( 'Enqueue Local Script File', 'shortcode-ui-local-script', 'shortcode-ui' ),
			/*
			* Include an icon with your shortcode. Optional.
			* Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
			*/
			'listItemImage' => 'dashicons-editor-code',
			/*
			* Define the UI for attributes of the shortcode. Optional.
			*
			* See above, to where the the assignment to the $fields variable was made.
			*/
			'attrs' => $fields,
		);

		shortcode_ui_register_for_shortcode( 'local_script', $shortcode_ui_args );
	}

}
