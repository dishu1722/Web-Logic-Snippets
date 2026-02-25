<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>> <head <?php language_attributes(); ?>>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBV0TdX4fU5odrLaewslDeFutXGxaapjDs"></script>
    <script src="https://fast.wistia.com/assets/external/popover-v1.js" type="text/javascript" charset="ISO-8859-1"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php jcd_head(); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div class="outer-content-wrapper">

        <div class="fixed-topbar">

            <div class="header-section container">
                <div class="row">

                    <div class="main-menu-area antialiased">
                        <div class="main-navigation navigation-list clearfix">
                            <a href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo( 'description' ); ?>" class="logo">
                                <img src="http://www.hobsonsbaydental.com.au/wp-content/uploads/2017/10/Hobson-bay-dental.png">
                            </a>
                            
                            <div class="mobile-menu-wrapper">
                                <div class="mobile-menu">
                                    <a href="#">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </a>
                                </div>
                            </div>
                            <div class="mobile-menu-dropdown">
                                <?php
                                    wp_nav_menu( array(
                                        'theme_location' => 'main-menu',
                                        'container' => '',
                                        'container_class' => '',
                                        'menu_class' => 'menu',
                                        // 'depth' => 1
                                    ) );
                                ?>
                                <div class="social-links">
                                    <?php
                                        $page_id = 6;

                                        $fb_url = get_field('facebook_link', $page_id);
                                        $ig_url = get_field('instagram_link', $page_id);
                                        $yt_url = get_field('youtube_link', $page_id);
                                        if($fb_url) : ?>
                                    <a target="_blank" rel="noopener nofollow" href="<?php echo esc_url($fb_url); ?>"><i class="icon-facebook-with-circle"></i></a>
                                    <?php endif; ?>
                                    <?php if($ig_url) : ?>
                                    <a target="_blank" rel="noopener nofollow" href="<?php echo esc_url($ig_url); ?>" class="instagram-icon social-icon-new"></a>
                                   <?php endif; ?>
                                    <?php if($yt_url) : ?>
                                    <a target="_blank" rel="noopener nofollow" href="<?php echo esc_url($yt_url); ?>" class="youtube-icon social-icon-new"></a>
                                    <?php endif; ?>
                                </div> 
                                <div class="phone-number">
                                    <span class="mark"><i class="icon-phone"></i></span>
                                    <?php echo get_option('jcd_phone_number'); ?>

                                    <a href="<?php echo get_option('jcd_appointment_url'); ?>" class="button button-primary button-bold button-rounded"><?php _e('Request Appointment', 'jcd'); ?></a>
                                </div>
                            </div></div></div></div>
            </div>
            </div>

