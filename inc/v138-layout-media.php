<?php
/**
 * WP BBTheme Medicine 3.8.11.38 — deterministic homepage layout and media repair.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Medicine already has a dedicated search panel. The older suite-level finder is
 * a duplicate and was also the source of the detached row visible below the hero.
 */
if ( function_exists( 'wpbb_child_v97_render_hero_finder' ) ) {
    remove_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 );
}

if ( ! function_exists( 'wpbb_medicine_v138_asset_url' ) ) {
    function wpbb_medicine_v138_asset_url( $relative ) {
        $relative = ltrim( (string) $relative, '/' );
        $path = get_stylesheet_directory() . '/' . $relative;
        $url  = get_stylesheet_directory_uri() . '/' . $relative;
        return is_file( $path ) ? $url . '?v=' . filemtime( $path ) : $url;
    }
}

/* WordPress does not always return a usable image size for programmatically
 * inserted SVG demo attachments. Supplying a size makes doctor/pharmacy
 * thumbnails render as normal images anywhere the parent theme asks for them.
 */
if ( ! function_exists( 'wpbb_medicine_v138_svg_downsize' ) ) {
    function wpbb_medicine_v138_svg_downsize( $downsize, $attachment_id, $size ) {
        if ( 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) return $downsize;
        $url = wp_get_attachment_url( $attachment_id );
        if ( ! $url ) return $downsize;
        return array( $url, 800, 620, false );
    }
}
add_filter( 'image_downsize', 'wpbb_medicine_v138_svg_downsize', 10, 3 );

if ( ! function_exists( 'wpbb_medicine_v138_enqueue' ) ) {
    function wpbb_medicine_v138_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v138.css';
        $js  = '/assets/suite-v138.js';

        wp_enqueue_style(
            'wpbb-medicine-v138',
            $uri . $css,
            array( 'wpbb-medicine-v137' ),
            is_file( $dir . $css ) ? filemtime( $dir . $css ) : '3.8.11.38'
        );
        wp_enqueue_script(
            'wpbb-medicine-v138',
            $uri . $js,
            array( 'wpbb-medicine-v137' ),
            is_file( $dir . $js ) ? filemtime( $dir . $js ) : '3.8.11.38',
            true
        );

        $doctor_images = array();
        foreach ( array(
            'amelia-hart', 'daniel-lee', 'sofia-martin', 'noah-williams',
            'elena-petrova', 'oliver-jensen', 'maija-ozola', 'erik-lindberg',
        ) as $slug ) {
            $doctor_images[ $slug ] = wpbb_medicine_v138_asset_url( 'assets/img/doctors/' . $slug . '.svg' );
        }

        $pharmacy_images = array();
        foreach ( array( 'vitamin-d', 'skin-care', 'travel-kit', 'allergy-relief', 'joint-support', 'first-aid' ) as $slug ) {
            $pharmacy_images[ $slug ] = wpbb_medicine_v138_asset_url( 'assets/img/pharmacy/' . $slug . '.svg' );
        }

        wp_add_inline_script(
            'wpbb-medicine-v138',
            'window.wpbbMedicineV138=' . wp_json_encode(
                array(
                    'version'        => '3.8.11.38',
                    'heroUrl'        => wpbb_medicine_v138_asset_url( 'assets/img/hero-v136/slide-1.jpg' ),
                    'aboutUrl'       => wpbb_medicine_v138_asset_url( 'assets/img/medical-photos/about-health.jpg' ),
                    'galleryUrls'    => array(
                        wpbb_medicine_v138_asset_url( 'assets/img/medical-photos/care-1.jpg' ),
                        wpbb_medicine_v138_asset_url( 'assets/img/medical-photos/care-3.jpg' ),
                        wpbb_medicine_v138_asset_url( 'assets/img/medical-photos/care-5.jpg' ),
                    ),
                    'blogUrls'       => array(
                        wpbb_medicine_v138_asset_url( 'assets/img/blog/blog-1.jpg' ),
                        wpbb_medicine_v138_asset_url( 'assets/img/blog/blog-3.jpg' ),
                        wpbb_medicine_v138_asset_url( 'assets/img/blog/blog-5.jpg' ),
                    ),
                    'doctorImages'   => $doctor_images,
                    'doctorUrls'     => array_values( $doctor_images ),
                    'pharmacyImages' => $pharmacy_images,
                    'pharmacyUrls'   => array_values( $pharmacy_images ),
                ),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_medicine_v138_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_medicine_v138_body_class' ) ) {
    function wpbb_medicine_v138_body_class( $classes ) {
        $classes[] = 'wpbb-medicine-v138';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_medicine_v138_body_class', PHP_INT_MAX );
