<?php /** Title: Medicine search CTA / Slug: wp-bbtheme-child-medicine/search / Categories: wp-patterns-main, wp-theme-current */ ?>
<!-- wp:wpbb/row {"containerClass":"container","customClasses":"medicine-search-panel motion-fade-up align-items-center"} -->
<!-- wp:wpbb/column {"xs":12,"md":8} -->
<!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">Find care</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Search our clinical team.</h2><!-- /wp:heading -->
<!-- /wp:wpbb/column -->
<!-- wp:wpbb/column {"xs":12,"md":4,"horizontalAlign":"end"} -->
<?php echo '<!-- wp:wpbb/button ' . wp_json_encode( array( 'text' => 'Browse all doctors', 'url' => ( get_post_type_archive_link( 'doctor' ) ?: home_url( '/doctors/' ) ), 'btnClass' => 'btn btn-primary' ), JSON_UNESCAPED_SLASHES ) . ' /-->'; ?>
<!-- /wp:wpbb/column -->
<!-- /wp:wpbb/row -->
