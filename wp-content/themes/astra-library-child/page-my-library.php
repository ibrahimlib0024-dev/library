<?php
/*
Template Name: My Library
Description: Page that shows the logged-in user's favorite books.
*/
get_header();
if ( ! is_user_logged_in() ) {
    echo '<p>' . __( 'Please log in to view your library.', 'astra-library-child' ) . '</p>';
    get_footer();
    exit;
}
$user_id = get_current_user_id();
$favs = get_user_meta( $user_id, '_library_favorites', true );
?>
<main id="site-content" role="main">
    <h1><?php _e( 'My Library', 'astra-library-child' ); ?></h1>
    <?php if ( ! is_array( $favs ) || empty( $favs ) ) : ?>
        <p><?php _e( 'You have no saved books yet.', 'astra-library-child' ); ?></p>
    <?php else : ?>
        <?php
        $q = new WP_Query( array( 'post_type' => 'book', 'post__in' => $favs, 'posts_per_page' => -1 ) );
        if ( $q->have_posts() ) :
            echo '<ul class="my-library-list">';
            while ( $q->have_posts() ) : $q->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        ?>
    <?php endif; ?>
</main>

<?php get_footer();
