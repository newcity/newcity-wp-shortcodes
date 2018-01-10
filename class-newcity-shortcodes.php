<?php

/**
 *
 * Defines custom shortcodes
 *
 * @since      0.1.0
 *
 * @package    NewCityCustom
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NewCityShortcodes {

	public function __construct() {
		add_shortcode( 'newcity_blockquote', array( 'NewCityShortcodes', 'newcity_blockquote' ), 7 );
		add_filter( 'mce_external_plugins', array( 'NewCityShortcodes', 'add_blockquote_mce' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_jquery_ui' ) );
	}

	public function enqueue_jquery_ui() {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_style( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'jquery-ui-dialog' );
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
}
