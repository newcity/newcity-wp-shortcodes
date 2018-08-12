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

class NewCityShortcodes {

	public function __construct() {
		add_action( 'init', array($this, 'shortcode_ui_detection' ));
		add_shortcode( 'custom_blockquote', array( 'NewCityShortcodes', 'newcity_blockquote' ), 7 );
		// add_filter( 'img_caption_shortcode', array( $this, 'update_caption_shortcode' ), 10, 3 );
		// add_filter( 'mce_external_plugins', array( 'NewCityShortcodes', 'add_blockquote_mce' ) );
		// add_action( 'admin_init', array( $this, 'enqueue_jquery_ui' ) );
		add_action( 'register_shortcode_ui', array($this, 'shortcode_ui_custom_quote') );

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

	/**
	 * Display an administration notice if the user can activate plugins.
	 *
	 * If the user can't activate plugins, then it's poor UX to show a notice they can't do anything to fix.
	 *
	 * @since 1.0.0
	 */
	function shortcode_ui_notices() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
			<div class="error message">
				<p><?php esc_html_e( 'Shortcode UI plugin must be active for custom shortcode editors to function.', 'shortcode-ui-quote', 'shortcode-ui' ); ?></p>
			</div>
			<?php
		}
	}

	if ( ! function_exists( 'newcity_blockquote' ) {
		public static function newcity_blockquote( $attr, $content = '' ) {
			$attr = wp_parse_args(
				$attr, array(
					'source' => '',
				)
			);
			ob_start();

			?>

			<blockquote class="inline-wysiwyg quotation-marks-wrapper">
			   <p><?php echo ( $content ); ?></p>
				<?php if ( ! empty( $attr['cite'] ) ) : ?>
					<cite><?php echo esc_html( $attr['cite'] ); ?></cite>
				<?php endif; ?>
			</blockquote>

			<?php
			return ob_get_clean();
		}
	}

	function shortcode_ui_custom_quote() {
		$fields = array(
			array(
				'label'       => esc_html__( 'Citation', 'shortcode-ui-quote', 'shortcode-ui' ),
				'attr'        => 'cite',
				'type'        => 'text',
				'encode'	  => false
			),
		);

		$shortcode_ui_args = array(
			/*
			* How the shortcode should be labeled in the UI. Required argument.
			*/
			'label' => esc_html__( 'Custom Quote with optional citation', 'shortcode-ui-quote', 'shortcode-ui' ),
			/*
			* Include an icon with your shortcode. Optional.
			* Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
			*/
			'listItemImage' => 'dashicons-editor-quote',
			/*
			* Limit this shortcode UI to specific posts. Optional.
			*/
			/*
			* Register UI for the "inner content" of the shortcode. Optional.
			* If no UI is registered for the inner content, then any inner content
			* data present will be backed-up during editing.
			*/
			'inner_content' => array(
				'label'        => esc_html__( 'Quote', 'shortcode-ui-quote', 'shortcode-ui' ),
				'description'  => esc_html__( 'Body of the quote.', 'shortcode-ui-quote', 'shortcode-ui' ),
			),
			/*
			* Define the UI for attributes of the shortcode. Optional.
			*
			* See above, to where the the assignment to the $fields variable was made.
			*/
			'attrs' => $fields,
		);

		shortcode_ui_register_for_shortcode( 'custom_blockquote', $shortcode_ui_args );
	}
	

	function the_content_filter( $content ) {
		// array of custom shortcodes requiring the fix
		$block = join( '|',array( 'callout' ) );
		// opening tag
		$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/",'[$2$3]',$content );
		// closing tag
		$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/",'[/$2]',$rep );
		// $rep = preg_replace("/\[\/($block)\][\S]*/","[/$2]",$rep);
		// $rep = preg_replace( '/([\n\r][\s\t]*){3,}/', "\n\n", $rep );
		return $rep;
	}

	function strip_breaks( $content ) {
		$content = preg_replace( '/<br ?\/?>/', '', $content );

		return $content;
	}

	public static function inline_callout( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		$output = '<div class="callout callout--inline">' . trim( do_shortcode( $content ) ) . '</div>';

		return $output;
	}

	public static function callout_header( $atts, $content = null, $tag = '' ) {

		$atts = shortcode_atts(
			[
				'heading' => 'h3',
			], $atts, $tag
		);

		return '<div class="callout__header"><' . $atts['heading'] . '>' . trim( $content ) . '</' . $atts['heading'] . '></div>';
	}

	public static function callout_body( $atts, $content = null ) {

		extract( shortcode_atts( array(), $atts ) );

				 return '<div class="callout__body">' . trim( $content ) . '</div>';
	}

	function wrap_all_img_in_shortcode( $html, $id, $alt, $title, $align, $url, $size ) {
		   // $url = wp_get_attachment_url($id); // Grab the current image URL
		   $html = '[caption]' . $html . '[/caption]';
		   return $html;
	}

	function update_caption_shortcode( $val, $attr, $content = null ) {
		$a = shortcode_atts(
			array(
				'id' => '',
				'align' => 'alignnone',
				'width' => '',
				'caption' => '',
			), $attr
		);

		$image_id = '';
		if ( 'attachment_' === substr( $a['id'], 0, 11 ) ) {
			$image_id = substr( $a['id'], 11 );
		}

		if ( $image_id ) {
			$image_acf = get_fields( $image_id );
		}


		$html_parent = new DOMDocument;
		$html_parent->loadHTML( do_shortcode( $content ) );
		$html_image = $html_parent->getElementsByTagName( 'img' );

		$img_width = 640;
		// $img_height = $html_image[0]->getAttribute( 'height' );
		$img_alt = $html_image[0]->getAttribute( 'alt' );

		return Timber::compile(
			'figure.twig', array(
				'image' => $image_id,
				'caption' => $a['caption'],
				'alt' => $img_alt,
				'attribution' => $image_acf['attribution'],
				'figure_modifier' => $a['align'],
				'media_width' => $img_width,
				// 'media_height' => $img_height,
				'alt' => $img_alt,
				'figure_modifier' => 'article-image',
				'original_ratio' => true,
			)
		);
	}

}
