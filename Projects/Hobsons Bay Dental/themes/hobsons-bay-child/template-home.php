<?php
/**
 * Template Name: Home
 */

get_header();

// Get the hero background image from an ACF field, with a fallback to the page's Featured Image.
$bg_image_url = '';
if ( function_exists('get_field') && get_field('home_image') ) {
    $image = get_field('home_image');
    $bg_image_url = is_array($image) ? $image['url'] : $image;
} elseif ( has_post_thumbnail() ) {
    $bg_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
}

// Get hero text content from ACF fields, with sensible defaults.
$cta_title    = function_exists('get_field') && get_field('cta_title') ? get_field('cta_title') : get_the_title();
$cta_text     = function_exists('get_field') ? get_field('cta_text') : '';
$cta_btn_url  = function_exists('get_field') ? get_field('cta_button_url') : '#';
$cta_btn_text = function_exists('get_field') && get_field('cta_button_text') ? get_field('cta_button_text') : 'Learn More';

?>

<?php if ( $bg_image_url ) : ?>
    <div class="home-hero" style="background-image: url('<?php echo esc_url( $bg_image_url ); ?>');">
        <div class="overlay">
            <div class="hero-content">
                <h2><?php echo esc_html( $cta_title ); ?></h2>
                <?php if ( ! empty( $cta_text ) ) : ?>
                    <p><?php echo esc_html( $cta_text ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $cta_btn_url ) ) : ?>
                    <a href="<?php echo esc_url( $cta_btn_url ); ?>" class="button button-bold button-rounded button-primary">
                        <?php echo esc_html( $cta_btn_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="main-content-section container">
    <div class="row">
        <div class="main-content">
            <?php
            // Standard WordPress loop to display the page content from the editor.
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
get_footer();