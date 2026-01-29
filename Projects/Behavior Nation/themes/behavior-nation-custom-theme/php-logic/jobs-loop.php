<?php
/**
 * Dynamic Job Board Loop - Behavior Nation
 * Logic: Loops through 'vacancies' CPT and generates a two-sided card UI.
 */

$job_args = array(
    'post_type' => 'vacancies',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
);

$job_query = new WP_Query($job_args);

if ($job_query->have_posts()) : 
    while ($job_query->have_posts()) : $job_query->the_post(); 
        // Get custom fields for job details
        $job_location = get_post_meta(get_the_ID(), 'job_location', true);
        $job_type = get_post_meta(get_the_ID(), 'job_type', true);
    ?>
    <div class="job-single-box">
        <div class="job-front">
            <div class="job-title"><?php the_title(); ?></div>
            <div class="job-description"><?php echo get_the_excerpt(); ?></div>
            <div class="buttons">
                <a class="btn cta-btn" href="#">Apply Now</a>
                <div class="btn cta-btn outline-btn job-desc-btn">Job Description</div>
            </div>
        </div>

        <div class="job-back" style="display: none;">
            <div class="job-close"> </div>
            <div class="job-title"><?php the_title(); ?></div>
            <div class="content-box">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <?php endwhile;
    wp_reset_postdata();
endif;
?>
