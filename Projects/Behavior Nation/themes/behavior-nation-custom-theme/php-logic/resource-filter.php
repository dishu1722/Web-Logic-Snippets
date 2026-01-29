<?php
/**
 * Resource Filter Component - Behavior Nation
 * * Logic: Fetches Custom Post Type 'resources' and groups them by 'resource_category' taxonomy.
 * Frontend: Works in conjunction with jQuery filter logic to toggle category views.
 * * @author Diksha Sharma
 * @version 1.0
 */

<?php
// Reconstructed loop for the Resources filter
$terms = get_terms('resource_category'); // Fetching the categories (ABCs, Insurance, etc.)

foreach( $terms as $term ) {
    $args = array(
        'post_type' => 'resources',
        'tax_query' => array(
            array(
                'taxonomy' => 'resource_category',
                'field'    => 'slug',
                'terms'    => $term->slug,
            ),
        ),
    );
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) : ?>
        <div class="box">
            <div class="two-block-content-outer">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="two-block-content-inner">
                        <h3><?php the_title(); ?></h3>
                        <img src="<?php the_post_thumbnail_url(); ?>">
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; wp_reset_postdata();
} ?>
