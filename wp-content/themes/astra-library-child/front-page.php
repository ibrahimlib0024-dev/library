<?php
/**
 * Front Page template for Astra Library Child
 * Displays an Arabic hero and featured books grid.
 */
get_header();
?>
<div class="library-hero" style="background:linear-gradient(180deg, rgba(0,51,102,0.95), rgba(0,51,102,0.85));color:#fff;padding:64px 12px;direction:rtl;text-align:right;">
    <div style="max-width:1100px;margin:0 auto;">
        <h1 style="font-size:2.6rem;margin:0;font-weight:700;">المكتبة الرقمية</h1>
        <div style="margin-top:8px;font-size:1.2rem;opacity:0.95;">عمادة المكتبات — جامعة البحر الاحمر</div>
        <p style="margin-top:18px;max-width:720px;line-height:1.6;">مرحباً بكم في مكتبتنا الرقمية: تصفح الكتب الجامعية، حمّل الملفات، واحفظ مفضلاتك للرجوع السريع.</p>
        <div style="margin-top:20px;">
            <a href="/index.php/book" class="library-hero-cta" role="button" aria-label="تصفح الكتب">تصفح الكتب</a>
            <a href="/wp-login.php?action=register" class="library-hero-cta" style="background:transparent;border:1px solid #fff;margin-right:12px;" role="button" aria-label="سجل الآن">سجل الآن</a>
        </div>
    </div>
</div>

<div style="max-width:1100px;margin:28px auto;padding:0 12px;direction:rtl;">
    <h2 style="text-align:right;margin-bottom:12px;">كتب مميزة</h2>
    <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:flex-end;">
    <?php
    $books = get_posts( array( 'post_type' => 'book', 'posts_per_page' => 8 ) );
    if ( $books ) {
        foreach ( $books as $b ) {
            $thumb = get_the_post_thumbnail_url( $b->ID, 'medium' );
            if ( ! $thumb ) $thumb = get_stylesheet_directory_uri() . '/images/placeholder-book.png';
            ?>
            <div class="book-card" style="width:220px;padding:12px;text-align:center;background:#fff;border-radius:8px;">
                <a href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>"><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $b->ID ) ); ?>" style="width:100%;height:auto;border-radius:6px;"/></a>
                <h3 style="font-size:1rem;margin:10px 0;"><?php echo esc_html( get_the_title( $b->ID ) ); ?></h3>
                <div style="font-size:0.9rem;color:#666;margin-bottom:8px;"><?php echo esc_html( get_post_meta( $b->ID, '_book_year', true ) ); ?></div>
                <div style="display:flex;gap:8px;justify-content:center;">
                    <?php echo library_favorite_button( $b->ID ); ?>
                    <?php echo library_render_pdf_button( get_post_meta( $b->ID, '_book_pdf', true ), $b->ID ); ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p style="text-align:right;">لا توجد كتب مميزة حالياً.</p>';
    }
    ?>
    </div>
</div>

<?php
get_footer();
