<?php
/**
 * Custom Blog Loop with Featured Hero Post - Behavior Nation
 * * Logic: 
 * 1. Displays the latest post in a high-impact 'Hero' section.
 * 2. Uses an offset in the secondary query to prevent duplicate posts in the grid.
 * 3. Integrated with Search & Filter Pro for AJAX results.
 */

// --- 1. THE HERO POST (Latest Single Post) ---
$hero_query = new WP_Query(array(
    'posts_per_page' => 1,
    'post_status'    => 'publish'
));

if ($hero_query->have_posts()) : 
    while ($hero_query->have_posts()) : $hero_query->the_post(); ?>
        <div class="blog-single-post-v2">
            <div class="blog_divide">
                <div class="post-single-show-v2 box-shadow-v2">
                    <div class="post-inner-single">
                        <a href="<?php the_permalink(); ?>" class="post_link_v2">
                            <div class="featured-img-sm">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                            <div class="post-content-sm">
                                <h5><?php the_title(); ?></h5>
                                <div class="btn cta-btn">learn more</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <?php get_sidebar('blog-top'); ?>
            </div>
        </div>
    <?php endwhile; 
    wp_reset_postdata(); 
endif; ?>

<div class="blog_divide">
    <div class="blog_left_content2">
        <div class="category_post_outer">
            <?php
            // --- 2. THE MAIN GRID (Excluding the Hero Post) ---
            // Note: If using Search & Filter Pro, this query is often handled 
            // by the plugin's ID, but here is the manual logic for the portfolio:
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $main_args = array(
                'post_type'      => 'post',
                'posts_per_page' => 6,
                'offset'         => 1, // Skips the post already shown in Hero
                'paged'          => $paged
            );

            $main_query = new WP_Query($main_args);

            if ($main_query->have_posts()) : 
                while ($main_query->have_posts()) : $main_query->the_post(); ?>
                    
                    <div class="single_cat_post box-shadow-v2">
                        <div class="post">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                                <div class="post-content-btm2">
                                    <h3 class="post-title-v2"><?php the_title(); ?></h3>
                                    <div class="btn cta-btn">Read More</div>
                                </div>
                            </a>
                        </div>
                    </div>

                <?php endwhile;
            else :
                echo '<p>No more posts found.</p>';
            endif;
            wp_reset_postdata(); ?>
        </div>
    </div>
</div>
