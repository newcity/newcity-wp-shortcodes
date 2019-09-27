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

	public static function safe_path( $string, $dir = true ) {
		// Remove leading and trailing whitespace, then remove
		// any periods or slashes at the beginning or end
		$path = trim(trim($string), '/.');

		// Remove any double slashes (`//`). This prevents attempts
		// to inject full URLs
		$path = preg_replace('/\/\/+/', '', $path);


		if (! $dir ) {
			// Slashes are allowed in directory values, but not in
			// file names
			$path = str_replace('/', '', $path);
		}

		return $path;
	}

    public static function local_script( $attr ) {
		$default_script_path = 'js';
		$options = get_option('newcity_shortcodes_options', false);
		$attr = wp_parse_args(
			$attr, array(
				'script' => '',
				'path' => $options['script_path'],
				'dependencies' => 'jquery',
			)
		);

		$path = $attr['path'] ? $attr['path'] : $default_script_path;
		$path = self::safe_path($path);

		$script = self::safe_path($attr['script'], false);

		$full_path = get_site_url( 1, '/wp-content/themes/' . basename( get_stylesheet_directory() ) ) . '/' . $path . '/' . $script;
		$full_path = esc_attr($full_path);
	    	if (! file_exists($full_path)) {
			$full_path = get_site_url( 1, '/wp-content/themes/' . basename( get_template_directory() ) ) . '/' . $path . '/' . $script;
			$full_path = esc_attr($full_path);
		}

		wp_enqueue_script( str_replace('/', '_', $full_path ), esc_attr($full_path . '.js'), $attr['dependencies'], false, true );
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
