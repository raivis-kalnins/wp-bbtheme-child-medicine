<?php
/**
 * WP BBTheme Medicine 3.8.11.37 - section alignment, grid and media uniformity.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'wpbb_medicine_v137_asset_url' ) ) {
    function wpbb_medicine_v137_asset_url( $relative ) {
        $relative = ltrim( (string) $relative, '/' );
        $path = get_stylesheet_directory() . '/' . $relative;
        $url  = get_stylesheet_directory_uri() . '/' . $relative;
        return is_file( $path ) ? $url . '?v=' . filemtime( $path ) : $url;
    }
}

if ( ! function_exists( 'wpbb_medicine_v137_enqueue' ) ) {
    function wpbb_medicine_v137_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v137.css';
        $js  = '/assets/suite-v137.js';

        wp_enqueue_style(
            'wpbb-medicine-v137',
            $uri . $css,
            array( 'wpbb-suite-v136' ),
            is_file( $dir . $css ) ? filemtime( $dir . $css ) : '3.8.11.37'
        );
        wp_enqueue_script(
            'wpbb-medicine-v137',
            $uri . $js,
            array( 'wpbb-suite-v136' ),
            is_file( $dir . $js ) ? filemtime( $dir . $js ) : '3.8.11.37',
            true
        );

        wp_add_inline_script(
            'wpbb-medicine-v137',
            'window.wpbbMedicineV137=' . wp_json_encode(
                array(
                    'version' => '3.8.11.37',
                    'aboutUrl' => wpbb_medicine_v137_asset_url( 'assets/img/medical-photos/about-health.jpg' ),
                    'galleryUrls' => array(
                        wpbb_medicine_v137_asset_url( 'assets/img/medical-photos/care-1.jpg' ),
                        wpbb_medicine_v137_asset_url( 'assets/img/medical-photos/care-2.jpg' ),
                        wpbb_medicine_v137_asset_url( 'assets/img/medical-photos/care-3.jpg' ),
                        wpbb_medicine_v137_asset_url( 'assets/img/medical-photos/care-4.jpg' ),
                    ),
                ),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_medicine_v137_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_medicine_v137_body_class' ) ) {
    function wpbb_medicine_v137_body_class( $classes ) {
        $classes[] = 'wpbb-medicine-v137';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_medicine_v137_body_class', PHP_INT_MAX );
