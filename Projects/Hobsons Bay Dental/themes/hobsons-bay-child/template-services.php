<?php
/**
 * Template Name: Our Services
 */
get_header(); ?>

<?php get_template_part('partials/content', 'page-heading'); ?>

<div class="main-content-section container">
    <div class="row">

        <div class="main-content">
            <?php while( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <div class="entry-content"><?php the_content(); ?></div>

                    <?php
                    // 1. Get all Service Categories instead of individual services
                    $service_cats = get_terms( array(
                        'taxonomy'   => 'service_category',
                        'hide_empty' => false, // Set to true to hide categories with no services
                    ) );

                    if ( ! empty( $service_cats ) && ! is_wp_error( $service_cats ) ) : ?>
                        <div class="service-list block-grid">
                            
                            <?php foreach ( $service_cats as $cat ) : 
                                // 2. Fetch the ACF image from the taxonomy term
                                $cat_image = get_field('category_icon', 'service_category_' . $cat->term_id);
                                $cat_link  = get_term_link($cat);
                            ?>
                                
                                <div class="grid-block-item grid-block-item--fourth">
                                    <div class="videobox-block">
                                        <a href="<?php echo esc_url($cat_link); ?>">
                                            <div class="image-wrapper">
                                                <?php if($cat_image): ?>
                                                    <img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($cat->name); ?>">
                                                <?php else: ?>
                                                    <div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                                        <span>No Icon</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="video-title">
                                                <?php echo esc_html($cat->name); ?>
                                            </div>
                                            
                                        </a>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div><?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>