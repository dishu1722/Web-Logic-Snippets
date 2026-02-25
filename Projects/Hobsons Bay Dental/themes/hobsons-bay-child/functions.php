<?php
/**
 * Hobsons Bay Child – theme hooks (v1.0.9 social-fix)
 * - Always load parent CSS explicitly, then child + our additions
 * - Load front.js (tabs etc.) on frontend
 * - Load Google Maps + acf-map.js on frontend; get API key from ACF or wp-config
 * - Social links: ensure new-tab + rel noopener on header social anchors
 */
if ( ! defined('ABSPATH') ) exit;

add_action('wp_enqueue_scripts', 'enqueue_font_awesome');
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
}


// Adding the "Categories" to the Service post-type
function hbd_register_service_taxonomy() {
    $labels = array(
        'name'              => 'Service Categories',
        'singular_name'     => 'Service Category',
        'menu_name'         => 'Service Categories',
    );

    $args = array(
        'hierarchical'      => true, 
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'service-category' ),
    );

    // We are linking it to 'service' here
    register_taxonomy( 'service_category', array( 'service' ), $args );
}
add_action( 'init', 'hbd_register_service_taxonomy' );