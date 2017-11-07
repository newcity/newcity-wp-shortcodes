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
		add_shortcode( 'blockquote', array( 'NewCityShortcodes', 'newcity_blockquote' ), 7 );
		// add_filter( 'img_caption_shortcode', array( $this, 'update_caption_shortcode' ), 10, 3 );
		add_filter( 'mce_external_plugins', array( 'NewCityShortcodes', 'add_blockquote_mce' ) );
	}

	public static function add_blockquote_mce( $plugin_array ) {
		$plugin_array['newcity_blockquote'] = plugins_url( '/js/blockquote-shortcode.js', __FILE__ );
		return $plugin_array;
	}

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
