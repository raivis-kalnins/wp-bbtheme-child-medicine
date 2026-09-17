<?php
/**
 * WP BBTheme Medicine 3.8.11.40 - Events-parity frontend layout owner.
 *
 * The Events 3.8.11.40 recovery proved that retaining every incremental
 * emergency geometry layer makes otherwise-correct sections fight each other.
 * Medicine now follows the same architecture: v118 remains the stable base and
 * v140 is the only late frontend owner for hero, measured rails and card grids.
 */
defined( 'ABSPATH' ) || exit;

/* Retire the superseded shared emergency frontend owners before enqueue time. */
foreach ( range( 119, 139 ) as $wpbb_medicine_v140_retired_version ) {
    $wpbb_medicine_v140_retired_callback = 'wpbb_child_v' . $wpbb_medicine_v140_retired_version . '_enqueue';
    remove_action( 'wp_enqueue_scripts', $wpbb_medicine_v140_retired_callback, PHP_INT_MAX );
    remove_action( 'wp_enqueue_scripts', $wpbb_medicine_v140_retired_callback, 999 );

    $wpbb_medicine_v140_retired_body_callback = 'wpbb_child_v' . $wpbb_medicine_v140_retired_version . '_body_class';
    remove_filter( 'body_class', $wpbb_medicine_v140_retired_body_callback, PHP_INT_MAX );
}
unset( $wpbb_medicine_v140_retired_version, $wpbb_medicine_v140_retired_callback, $wpbb_medicine_v140_retired_body_callback );

/* v137/v138 were Medicine-only emergency layers and used their own callback names. */
remove_action( 'wp_enqueue_scripts', 'wpbb_medicine_v137_enqueue', PHP_INT_MAX );
remove_action( 'wp_enqueue_scripts', 'wpbb_medicine_v137_enqueue', 999 );
remove_action( 'wp_enqueue_scripts', 'wpbb_medicine_v138_enqueue', PHP_INT_MAX );
remove_action( 'wp_enqueue_scripts', 'wpbb_medicine_v138_enqueue', 999 );
remove_filter( 'body_class', 'wpbb_medicine_v137_body_class', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_medicine_v138_body_class', PHP_INT_MAX );

/* v138 removed the stable v97 hero finder at include time. Restore that filter;
 * the v140 runtime points it at the doctor archive and hides the duplicate CTA. */
if ( function_exists( 'wpbb_child_v97_render_hero_finder' ) && false === has_filter( 'render_block', 'wpbb_child_v97_render_hero_finder' ) ) {
    add_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180, 2 );
}

if ( ! function_exists( 'wpbb_medicine_v140_asset_url' ) ) {
    function wpbb_medicine_v140_asset_url( $relative ) {
        $relative = ltrim( (string) $relative, '/' );
        $path = get_stylesheet_directory() . '/' . $relative;
        $url  = get_stylesheet_directory_uri() . '/' . $relative;
        return is_file( $path ) ? add_query_arg( 'v', (string) filemtime( $path ), $url ) : $url;
    }
}

if ( ! function_exists( 'wpbb_medicine_v140_enqueue' ) ) {
    function wpbb_medicine_v140_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* Match the working Events theme: one stable base plus one layout owner. */
        foreach ( range( 119, 139 ) as $n ) {
            $handle = 'wpbb-suite-v' . $n;
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        foreach ( array( 'wpbb-medicine-v137', 'wpbb-medicine-v138' ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $base_css = $dir . '/assets/suite-v118.css';
        $base_js  = $dir . '/assets/suite-v118.js';
        if ( is_readable( $base_css ) ) {
            wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
        }
        if ( is_readable( $base_js ) ) {
            wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
        }

        $css = $dir . '/assets/suite-v140.css';
        $js  = $dir . '/assets/suite-v140.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v140',
                $uri . '/assets/suite-v140.css',
                is_readable( $base_css ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $css )
            );
        }

        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v140',
                $uri . '/assets/suite-v140.js',
                is_readable( $base_js ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $js ),
                true
            );

            $doctor_archive = get_post_type_archive_link( 'doctor' );
            if ( ! $doctor_archive ) {
                $doctor_archive = home_url( '/doctors/' );
            }

            wp_add_inline_script(
                'wpbb-suite-v140',
                'window.wpbbSuiteV140=' . wp_json_encode(
                    array(
                        'version'             => '3.8.11.40',
                        'themeSlug'           => basename( $dir ),
                        'doctorArchive'       => $doctor_archive,
                        'medicineHeroUrls'    => array(
                            wpbb_medicine_v140_asset_url( 'assets/img/hero-v118/slide-1.jpg' ),
                            wpbb_medicine_v140_asset_url( 'assets/img/hero-v118/slide-2.jpg' ),
                        ),
                        'medicineGalleryUrls' => array(
                            wpbb_medicine_v140_asset_url( 'assets/img/medical-photos/care-1.jpg' ),
                            wpbb_medicine_v140_asset_url( 'assets/img/medical-photos/care-3.jpg' ),
                            wpbb_medicine_v140_asset_url( 'assets/img/medical-photos/care-5.jpg' ),
                        ),
                    ),
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_medicine_v140_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_medicine_v140_body_class' ) ) {
    function wpbb_medicine_v140_body_class( $classes ) {
        $classes[] = 'wpbb-v140';
        $classes[] = 'wpbb-v140-theme-medicine';
        if ( is_front_page() ) {
            $classes[] = 'wpbb-v140-medicine-has-hero-finder';
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_medicine_v140_body_class', PHP_INT_MAX );
