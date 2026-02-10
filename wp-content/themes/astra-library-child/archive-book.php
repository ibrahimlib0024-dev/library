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
                <?php $book_id = get_the_ID(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('book-card'); ?> style="margin-bottom:18px;display:flex;gap:12px;align-items:flex-start;padding:12px;border:1px solid #eee;border-radius:6px;">
                    <a href="<?php the_permalink(); ?>" style="flex:0 0 140px;">
                        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium' ); else: ?><img src="<?php echo get_stylesheet_directory_uri() . '/images/placeholder-book.png'; ?>" alt="" style="max-width:100%" /><?php endif; ?>
                    </a>
                    <div class="book-card-body" style="flex:1;">
                        <h2 style="margin:0 0 6px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="excerpt"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 28 ); ?></div>
                        <p style="margin-top:8px;font-size:0.95em;color:#666;"><?php the_terms( $book_id, 'book_author', '<strong>Authors:</strong> ', ', ', '' ); ?> <?php the_terms( $book_id, 'publisher', '<br><strong>Publisher:</strong> ', ', ', '' ); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php _e( 'No books found.', 'astra-library-child' ); ?></p>
    <?php endif; ?>
</main>

<?php get_footer();
