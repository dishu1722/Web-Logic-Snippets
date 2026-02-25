<?php get_header(); ?>

<?php get_template_part('partials/content', 'page-heading'); ?>

<?php while( have_posts() ) : the_post(); ?>
    <div class="main-content-section container">
        <div class="row">

            <div class="main-content">
                <article <?php post_class(); ?>>
                    <div class="entry-content clearfix"><?php the_content(); ?></div>

                    <?php
                    $address = get_field('address');
                    $phone_number = get_field('phone_number');
                    $fax_number = get_field('fax_number');
                    $opening_hours = get_field('opening_hours');
                    $email = get_field('email');
                    if( $address || $phone_number || $fax_number || $opening_hours || $email ) : ?>
                        <div class="clinic-info clinic-opening-time row">
                            <?php if( $opening_hours ) : ?>
                                <div class="column col6 clinic-opening-hours">
                                    <h2 class="section-heading-title"><?php _e('Opening Hours', 'jcd'); ?></h2>
                                    <p><?php echo $opening_hours; ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if( $address || $phone_number || $fax_number || $email ) : ?>
                                <div class="column col6 clinic-address">
                                    <h2 class="section-heading-title"><?php _e('Location & Address Details', 'jcd'); ?></h2>
                                    <div class="row">
                                        <?php if( $address ) : ?>
                                            <div class="column col12">
                                                <i class="icon-location-pin"></i>
                                                <div class="inner"><?php echo $address; ?></div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if( $phone_number ) : ?>
                                            <div class="column col12">
                                                <i class="icon-phone"></i>
                                                <div class="inner"><?php echo $phone_number; ?></div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if( $fax_number ) : ?>
                                            <div class="column col12">
                                                <i class="icon-print"></i>
                                                <div class="inner"><?php echo $fax_number; ?></div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if( $email ) : ?>
                                            <div class="column col12">
                                                <i class="icon-envelope"></i>
                                                <div class="inner">
                                                    <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div><!-- .clinic-info -->
                    <?php endif; ?>
                </article>
            </div>

        </div>
    </div>
    <!-- .main-content-section -->

<?php
    $address = get_field('map_iframe'); 
    if( $address ) : 
        // urlencode turns "72 Electra St" into "72+Electra+St"
        $query_map = urlencode($address);
?>
    <div class="map-sec">
        <iframe 
            width="100%" 
            height="450" 
            style="border:0;" 
            loading="lazy" 
            allowfullscreen 
            src="https://www.google.com/maps?q=<?php echo $query_map; ?>&output=embed">
        </iframe>
    </div>
<?php endif; ?>
    
    <?php if( $getting_here = get_field('getting_here') ) : ?>
        <div class="container">
            <div class="clinic-info getting-here row">
                <div class="column col12">
                    <h2 class="section-heading-title"><?php _e('Getting Here', 'jcd'); ?></h2>
                    <p><?php echo apply_filters( 'the_content', $getting_here ); ?></p>
                </div>
            </div><!-- .getting-here -->
        </div>
    <?php endif; ?>

    <?php if( $practitioners = get_field('practitioners') ) : ?>
        <div class="container">
            <div class="clinic-info practitioners-section row">
                <div class="column col12">
                    <h2 class="section-heading-title"><?php _e('Practitioners at this location', 'jcd'); ?></h2>
                    <div class="team-list block-grid">
                        <?php foreach( $practitioners as $data ) : 
                            $practitioner = $data['practitioner']; ?>
                            
                            <div class="block-grid-item fourth-one team-item">
                                <a href="<?php echo get_permalink( $practitioner->ID ); ?>">
                                    <div class="block-grid-image">
                                        <?php 
                                        if( has_post_thumbnail( $practitioner->ID ) ) : 
                                            $thumb_id = get_post_thumbnail_id( $practitioner->ID );
                                            $image_resize = vt_resize( $thumb_id, '', 390, 390, true ); 
                                            if( $image_resize ) : ?>
                                                <img src="<?php echo $image_resize['url'];?>" width="390" height="390">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="block-grid-inner antialiased">
                                        <div class="centering">
                                            <div class="block-grid-title">
                                                <div class="title"><?php echo $practitioner->post_title; ?></div>
                                                <div class="block-desc">
                                                    <div class="opening-hours"><?php echo apply_filters( 'the_content', $data['working_hours'] ); ?></div>
                                                    <div class="text-center">
                                                        <div class="button button-ghost button-white button-rounded button-small">Further Information</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    </div><!-- .team-list -->
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endwhile; ?>
<?php get_footer(); ?>
