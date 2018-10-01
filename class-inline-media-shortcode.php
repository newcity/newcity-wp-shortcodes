<?php

/**
 *
 * Defines inline media shortcode
 *
 * @since      0.1.0
 *
 * @package    NewCityCustom
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NewCityInlineMediaShortcode {

	public function __construct() {
        add_action( 'init', array($this, 'shortcode_ui_detection' ));
		add_shortcode( 'inline_media', array( $this, 'inline_media_function' ), 7 );
		add_action( 'register_shortcode_ui', array($this, 'shortcode_ui_inline_media') );
    }

    /**
	 * If Shortcake isn't active, then add an administration notice.
	 *
	 * This check is optional. The addition of the shortcode UI is via an action hook that is only called in Shortcake.
	 * So if Shortcake isn't active, you won't be presented with errors.
	 *
	 * Here, we choose to tell users that Shortcake isn't active, but equally you could let it be silent.
	 *
	 * Why not just self-deactivate this plugin? Because then the shortcodes would not be registered either.
	 *
	 * @since 1.0.0
	 */
	function shortcode_ui_detection() {
		if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			add_action( 'admin_notices', array($this, 'shortcode_ui_notices') );
		}
	}
    
    public static function inline_media_function( $attr, $content = '') {
		if ( ! function_exists('custom_inline_media' ) ) {
			return self::inline_media( $attr, $content );
		} else {
			return custom_inline_media( $attr, $content );
		}
	}

    public static function inline_media( $attr ) {
		$attr = wp_parse_args(
			$attr, array(
				'source' => '',
			)
        );
        
        $display_width = in_array( $attr['alignment'], ['float--left', 'float--right'] ) ? 800 : 2400;

        $timber_image = new \Timber\Image( $attr['attachment'] );

        $resized = \Timber\ImageHelper::resize( $timber_image, $display_width );

        ob_start();
        ?>
        <div class="media <?php echo $attr['alignment'] ?>">
        <figure>
            <div class="media__wrapper">
                <img src="<?php echo $resized ?>" alt="<?php echo $timber_image->alt ?>" />
            </div>

            <?php
            if( $attr['caption'] ) {
                echo '<figcaption>' . $attr['caption'] . '</figcaption>';
            }
            ?>
        </figure>
        </div>
        <?php
        
		return ob_get_clean();
	}

	function shortcode_ui_inline_media() {
		$fields = array(
			array(
				'label'       => esc_html__( 'Attachment', 'shortcode-ui-example', 'shortcode-ui' ),
				'attr'        => 'attachment',
				'type'        => 'attachment',
				/*
				 * These arguments are passed to the instantiation of the media library:
				 * 'libraryType' - Type of media to make available.
				 * 'addButton'   - Text for the button to open media library.
				 * 'frameTitle'  - Title for the modal UI once the library is open.
				 */
				'libraryType' => array( 'image' ),
				'addButton'   => esc_html__( 'Select Image', 'shortcode-ui-example', 'shortcode-ui' ),
				'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui-example', 'shortcode-ui' ),
            ),
            array(
                'label'       => esc_html__( 'Alignment', 'shortcode-ui-example', 'shortcode-ui' ),
                'description' => esc_html__( 'How the image should be placed within content.', 'shortcode-ui-example', 'shortcode-ui' ),
                'attr'        => 'alignment',
                'type'        => 'select',
                'options'     => array(
                    array( 'value' => '', 'label' => esc_html__( 'Text Width (line up with edges of text column)', 'shortcode-ui-example', 'shortcode-ui' ) ),
                    array( 'value' => 'media--full-width', 'label' => esc_html__( 'Full Width (extend to edges of the screen)', 'shortcode-ui-example', 'shortcode-ui' ) ),
                    array( 'value' => 'float--left', 'label' => esc_html__( 'Float Left', 'shortcode-ui-example', 'shortcode-ui' ) ),
                    array( 'value' => 'float--right', 'label' => esc_html__( 'Float Right', 'shortcode-ui-example', 'shortcode-ui' ) ),
                ),
            ),
            array(
				'label'       => esc_html__( 'Caption', 'shortcode-ui-quote', 'shortcode-ui' ),
				'attr'        => 'caption',
				'type'        => 'text',
				'encode'	  => false
			),
		);

		$shortcode_ui_args = array(
			/*
			* How the shortcode should be labeled in the UI. Required argument.
			*/
			'label' => esc_html__( 'Embed Inline Media', 'shortcode-ui-inline-media', 'shortcode-ui' ),
			/*
			* Include an icon with your shortcode. Optional.
			* Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
			*/
			'listItemImage' => 'dashicons-format-image',
			/*
			* Define the UI for attributes of the shortcode. Optional.
			*
			* See above, to where the the assignment to the $fields variable was made.
			*/
			'attrs' => $fields,
		);

		shortcode_ui_register_for_shortcode( 'inline_media', $shortcode_ui_args );
	}

}
