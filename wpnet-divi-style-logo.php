<?php
/**
 * Plugin Name: WPnet.pl DIVI style logo
 * Plugin URI: https://wpnet.pl/blog/wtyczki/wtyczka-wpnet-pl-divi-style-logo/
 * Version: 1.0
 * Author: wpnet.pl
 * Author URI: https://wpnet.pl
 * Description: WPnet.pl DIVI style logo plugin. Just activate.
 * License: GPL2
 *
 * @package WordPress
 */

/* Make sure we don't expose any info if called directly */
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Generate svg logo if divi_logo option is empty
 */
function wpnet_divi_logo_style_generate_svg_if_empty_logo() {
	if ( function_exists( 'et_get_option' ) && empty( et_get_option( 'divi_logo' ) ) ) {
		wpnet_divi_logo_style_generate_svg();
	}
}
add_action( 'wp_head', 'wpnet_divi_logo_style_generate_svg_if_empty_logo', 10 );

/**
 * Regenerate svg logo on update blogname
 */
function wpnet_divi_logo_style_regenerate_svg() {
	if ( function_exists( 'et_update_option' ) && 'data:image/svg+xml;base64' === substr( et_get_option( 'divi_logo' ), 0, 25 ) ) {
		wpnet_divi_logo_style_generate_svg();
	}
}
add_action( 'update_option_blogname', 'wpnet_divi_logo_style_regenerate_svg', 10 );

/**
 * Generate svg logo
 */
function wpnet_divi_logo_style_generate_svg() {
	$blog_name = get_bloginfo( 'name' );
	$letter    = mb_strtoupper( mb_substr( $blog_name, 0, 1 ) );

	$svg_logo = 'data:image/svg+xml;base64,' . base64_encode( '<svg width="600" height="100" version="1.1" viewBox="0 0 600 100" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><metadata><rdf:RDF><cc:Work rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/><dc:title/></cc:Work></rdf:RDF></metadata><text x="115" y="78" fill="#716f6e" font-family="Raleway" font-size="60px" font-weight="400" letter-spacing="0px" stroke-width=".25" word-spacing="0px" style="line-height:125%" xml:space="preserve">' . $blog_name . '</text><text x="50" y="78" fill="#716f6e" font-family="Raleway" font-size="80px" font-weight="400" letter-spacing="0px" stroke-width=".20" word-spacing="0px" xml:space="preserve" text-anchor="middle">' . $letter . '</text><ellipse cx="50" cy="50" rx="48" ry="48" fill="none" stroke="#716f6e" stroke-width="4"/></svg>' );

	et_update_option( 'divi_logo', $svg_logo );
}

/**
 * Enqueue google font Raleway
 */
function wpnet_divi_logo_style_enqueue_font() {
	wp_enqueue_style( 'font_raleway', 'https://fonts.googleapis.com/css?family=Raleway&amp;subset=latin-ext', false );
}
add_action( 'wp_enqueue_scripts', 'wpnet_divi_logo_style_enqueue_font' );

/**
 * Admin notice when not Divi or Divi child theme
 */
function wpnet_divi_logo_style_admin_notice() {
	if ( ! function_exists( 'et_update_option' ) ) {
		?>
		<div class="notice notice-error">
			<p><?php _e( '<strong>WPnet.pl DIVI style logo plugin</strong> works only when you use DIVI or DIVI Child theme. You cat deactivate it on ', 'wpnet_divi_logo_style' ); ?>
				<a href="<?php echo admin_url( 'plugins.php' ); ?>"><?php _e( 'plugins page', 'wpnet_divi_logo_style' ); ?></a>.
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wpnet_divi_logo_style_admin_notice' );

/**
 * Load plugin textdomain.
 */
function wpnet_divi_logo_style_load_textdomain() {
	load_plugin_textdomain( 'wpnet_divi_logo_style', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'admin_init', 'wpnet_divi_logo_style_load_textdomain' );
