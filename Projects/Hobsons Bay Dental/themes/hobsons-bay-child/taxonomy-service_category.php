<?php
/**
 * The template for displaying Service Category pages (Sub-services)
 */
get_header(); ?>

<div class="main-content-section container">
    <div class="row">

        <div class="main-content">
            <header class="entry-header" style="margin-bottom: 40px; color: #00A3DA;">
                <h1 class="entry-title"><?php single_term_title(); ?></h1>
                <?php if ( term_description() ) : ?>
                    <div class="taxonomy-description" style="max-width: 800px; margin: 0 auto;">
                        <?php echo term_description(); ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if ( have_posts() ) : ?>
                <div class="service-list block-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        
                        <?php 
                        // Use the native WordPress Featured Image URL
                        // 'large' or 'medium_large' usually works best for grids
                        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'large'); 
                        ?>

                        <div class="grid-block-item grid-block-item--fourth">
                            <div class="videobox-block">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="image-wrapper">
                                        <?php if($featured_img_url): ?>
                                            <img src="<?php echo esc_url($featured_img_url); ?>" alt="<?php the_title(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="video-title"><?php the_title(); ?></div>
                                </a>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </div><div class="pagination-wrapper">
                    <?php the_posts_navigation(); ?>
                </div>

            <?php else : ?>
                <div class="no-posts-found">
                    <p>No services found in this category. Please check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>