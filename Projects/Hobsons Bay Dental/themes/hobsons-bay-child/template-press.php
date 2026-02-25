<?php
/**
 * Template Name: Press Child
 */
get_header(); ?>

<?php 
// Safely loads the heading partial from parent or child theme
if ( locate_template('partials/content-page-heading.php') ) {
    get_template_part('partials/content', 'page-heading'); 
}
?>

<div class="main-content-section container">
    <div class="row">

        <div class="main-content">
            <?php while( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <div class="entry-content"><?php the_content(); ?></div>
                    <div class="step-box-outer">    
                        <?php if( have_rows('videos_repeater') ): ?>
                            <?php while( have_rows('videos_repeater') ): the_row(); ?>  
                                <?php 
                                    // These variables handle the data safely
                                    $vdo_img_raw = get_sub_field('video_cover_image');
                                    $vdo_cover_image = is_array($vdo_img_raw) ? $vdo_img_raw['url'] : $vdo_img_raw;

                                    $vdocaption = get_sub_field('video_caption');
                                    $vdolink = get_sub_field('video_link');

                                    $pdf_file = get_sub_field('upload_pdf');
                                    $pdf_url = is_array($pdf_file) ? $pdf_file['url'] : $pdf_file;
                                ?>
                                <div class="single-step-box">
                                     <div class="videobox-block">
                                         
                                         <?php if (!empty($vdolink)): // ORIGINAL VIDEO HTML ?>
                                         <a href="<?php echo $vdolink; ?>" class="wistia-popover[height=600,playerColor=7b796a,width=700]">
                                            <img src="<?php echo $vdo_cover_image; ?>" alt="Video icon">
                                             <div class="video-title"><?php echo $vdocaption; ?></div>
                                              <i class="fa fa-play"></i>
                                         </a>

                                        <?php elseif($pdf_url): // ORIGINAL PDF HTML ?>
                                            <a href="<?php echo $pdf_url; ?>" target="_blank">
                                                <img src="<?php echo $vdo_cover_image; ?>" alt="PDF icon">
                                                <div class="video-title"><?php echo $vdocaption; ?></div>
                                            </a>

                                         <?php else: // ORIGINAL IMAGE/POPUP HTML ?>
                                            <img src="<?php echo $vdo_cover_image; ?>" class="cover-image" alt="Cover image">
                                            <div class="video-title"><?php echo $vdocaption; ?></div>

                                             <div class="custom-popup-box">
                                                 <div class="img-pop-outer">    
                                                     <div class="img-pop-inner">
                                                         <img src="<?php echo $vdo_cover_image; ?>" alt="Cover image">
                                                         <i class="fa fa-close close-btn-v"></i>
                                                     </div>
                                                  </div>
                                             </div>
                                        <?php endif; ?>
                                         
                                    </div>
                                </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>