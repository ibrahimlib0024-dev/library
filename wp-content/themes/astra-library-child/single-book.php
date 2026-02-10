<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
    $book_id = get_the_ID();
    $cover_id = get_post_meta( $book_id, '_book_cover_id', true );
    $pdf = get_post_meta( $book_id, '_book_pdf', true );
    $short = get_post_meta( $book_id, '_book_short', true );
    $year = get_post_meta( $book_id, '_book_year', true );
    $isbn = get_post_meta( $book_id, '_book_isbn', true );
    $authors = get_the_terms( $book_id, 'book_author' );
    $publishers = get_the_terms( $book_id, 'publisher' );
    ?>
    <main id="site-content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            <div class="entry-content">
                <div class="book-left" style="float:left;max-width:220px;margin-right:18px;">
                    <?php if ( $cover_id ) : ?>
                        <img src="<?php echo esc_url( wp_get_attachment_url( $cover_id ) ); ?>" alt="<?php the_title_attribute(); ?>" style="max-width:100%;" />
                    <?php else :
                        the_post_thumbnail( 'medium' );
                    endif; ?>
                    <div style="margin-top:8px;">
                        <?php echo library_favorite_button( $book_id ); ?>
                    </div>
                    <div style="margin-top:8px;">
                        <?php if ( $pdf ) : ?>
                            <a class="button" href="<?php echo esc_url( $pdf ); ?>" target="_blank"><?php _e( 'Download PDF', 'astra-library-child' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="book-right">
                    <p><strong><?php _e( 'Authors:', 'astra-library-child' ); ?></strong> <?php echo $authors ? esc_html( implode( ', ', wp_list_pluck( $authors, 'name' ) ) ) : '-'; ?></p>
                    <p><strong><?php _e( 'Publisher:', 'astra-library-child' ); ?></strong> <?php echo $publishers ? esc_html( implode( ', ', wp_list_pluck( $publishers, 'name' ) ) ) : '-'; ?></p>
                    <p><strong><?php _e( 'Year:', 'astra-library-child' ); ?></strong> <?php echo esc_html( $year ); ?></p>
                    <p><strong><?php _e( 'ISBN:', 'astra-library-child' ); ?></strong> <?php echo esc_html( $isbn ); ?></p>
                    <h2><?php _e( 'Summary', 'astra-library-child' ); ?></h2>
                    <p><?php echo nl2br( esc_html( $short ) ); ?></p>
                    <h2><?php _e( 'Description', 'astra-library-child' ); ?></h2>
                    <?php the_content(); ?>

                    <div class="library-rating" style="margin-top:16px;">
                        <?php
                        $count = intval( get_post_meta( $book_id, '_rating_count', true ) );
                        $sum = intval( get_post_meta( $book_id, '_rating_sum', true ) );
                        $avg = $count ? round( $sum / $count, 2 ) : 0;
                        ?>
                        <div class="library-rating-avg"><?php echo sprintf( __( 'Average: %s (%d)', 'astra-library-child' ), $avg, $count ); ?></div>
                        <div class="library-rating-stars">
                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                <a href="#" class="library-rate-star" data-book-id="<?php echo $book_id; ?>" data-value="<?php echo $i; ?>"><?php echo $i; ?>★</a>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </article>
    </main>
    <?php
endwhile; endif;
get_footer();
