<?php
/**
 * Medicine 3.8.11.42 - final sector finish on the exact Events v140 layout owner.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_medicine_v142_asset' ) ) {
    function wpbb_medicine_v142_asset( $relative ) {
        $relative = ltrim( (string) $relative, '/' );
        $path = get_stylesheet_directory() . '/' . $relative;
        $url  = get_stylesheet_directory_uri() . '/' . $relative;
        return is_file( $path ) ? add_query_arg( 'v', (string) filemtime( $path ), $url ) : $url;
    }
}

if ( ! function_exists( 'wpbb_medicine_v142_enqueue' ) ) {
    function wpbb_medicine_v142_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* v141 was an interim finish. Keep v140 as the sole shared layout owner. */
        foreach ( array( 'wpbb-medicine-v141' ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $css = $dir . '/assets/suite-v142.css';
        $js  = $dir . '/assets/suite-v142.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style( 'wpbb-medicine-v142', $uri . '/assets/suite-v142.css', array( 'wpbb-suite-v140' ), (string) filemtime( $css ) );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script( 'wpbb-medicine-v142', $uri . '/assets/suite-v142.js', array( 'wpbb-suite-v140' ), (string) filemtime( $js ), true );
            $many = static function( $pattern, $count ) {
                $out = array();
                for ( $i = 1; $i <= $count; $i++ ) $out[] = wpbb_medicine_v142_asset( sprintf( $pattern, $i ) );
                return $out;
            };
            wp_add_inline_script( 'wpbb-medicine-v142', 'window.wpbbMedicineV142=' . wp_json_encode( array(
                'version'  => '3.8.11.42',
                'hero'     => $many( 'assets/img/medicine-v142/hero-%d.avif', 3 ),
                'about'    => wpbb_medicine_v142_asset( 'assets/img/medicine-v142/about.avif' ),
                'doctors'  => $many( 'assets/img/medicine-v142/doctor-%d.avif', 8 ),
                'pharmacy' => $many( 'assets/img/medicine-v142/pharmacy-%d.avif', 6 ),
                'gallery'  => array(
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/care-1.avif' ),
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/care-3.avif' ),
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/care-5.avif' ),
                ),
                'blog'     => array(
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/blog-1.avif' ),
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/blog-3.avif' ),
                    wpbb_medicine_v142_asset( 'assets/img/medicine-v142/blog-5.avif' ),
                ),
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ';', 'before' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_medicine_v142_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_medicine_v142_body_class' ) ) {
    function wpbb_medicine_v142_body_class( $classes ) {
        $classes = array_values( array_diff( $classes, array( 'wpbb-v141-theme-medicine' ) ) );
        $classes[] = 'wpbb-v142-theme-medicine';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_medicine_v142_body_class', PHP_INT_MAX );

/* Future managed-demo rebuilds use the same AVIF set rather than legacy JPG/SVG demo media. */
if ( ! function_exists( 'wpbb_medicine_v142_profile_media' ) ) {
    function wpbb_medicine_v142_profile_media( $profile ) {
        if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return $profile;
        $base = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/medicine-v142/';
        $profile['hero_image'] = $base . 'hero-1.avif';
        if ( ! empty( $profile['hero_slides'] ) && is_array( $profile['hero_slides'] ) ) {
            foreach ( $profile['hero_slides'] as $i => $slide ) {
                if ( is_array( $slide ) ) $profile['hero_slides'][ $i ]['image'] = $base . 'hero-' . ( ( $i % 3 ) + 1 ) . '.avif';
            }
        }
        $profile['about_image'] = $base . 'about.avif';
        $profile['gallery_images'] = array( $base . 'care-1.avif', $base . 'care-3.avif', $base . 'care-5.avif' );
        return $profile;
    }
}
add_filter( 'wp_theme_demo_profile', 'wpbb_medicine_v142_profile_media', 2200 );
