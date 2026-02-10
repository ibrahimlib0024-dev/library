<?php
get_header();
?>
<main id="site-content" role="main">
    <header class="archive-header">
        <h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="book-archive">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom:18px;">
                    <a href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium' ); endif; ?>
                        <h2><?php the_title(); ?></h2>
                    </a>
                    <div class="excerpt"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 30 ); ?></div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php _e( 'No books found.', 'astra-library-child' ); ?></p>
    <?php endif; ?>
</main>

<?php get_footer();
