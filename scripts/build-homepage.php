<?php
// Build a simple Arabic homepage using existing 'book' posts
$home_id = 15; // created earlier
$books = get_posts( array( 'post_type' => 'book', 'posts_per_page' => 4 ) );
$items_html = '';
foreach ( $books as $b ) {
    $thumb = get_the_post_thumbnail_url( $b->ID, 'medium' );
    if ( ! $thumb ) $thumb = get_stylesheet_directory_uri() . '/images/placeholder-book.png';
    $items_html .= '<div class="hp-book" style="width:220px;margin:8px;text-align:center;">';
    $items_html .= '<a href="' . get_permalink( $b->ID ) . '"><img src="' . esc_url( $thumb ) . '" style="width:100%;height:auto;border-radius:6px;"/></a>';
    $items_html .= '<h3 style="font-size:1rem;margin:8px 0;"><a href="' . get_permalink( $b->ID ) . '">' . esc_html( get_the_title( $b->ID ) ) . '</a></h3>';
    $items_html .= '</div>';
}

$html = '<div style="padding:40px 20px;background:linear-gradient(180deg, rgba(0,51,102,0.95), rgba(0,51,102,0.9));color:#fff;text-align:center;">';
$html .= '<h1 style="font-size:2.2rem;margin:0;">مكتبتي الرقمية</h1>';
$html .= '<p style="font-size:1.1rem;margin:12px 0;">مكتبة إلكترونية للكتب الجامعية والبحثية — تصفح، حمّل، واحفظ مفضلاتك</p>';
$html .= '<a href="/wp-admin/edit.php?post_type=book" class="button" style="background:#007a3d;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;">إدارة الكتب</a>';
$html .= '</div>';

$html .= '<div style="padding:24px;max-width:1100px;margin:0 auto;">';
$html .= '<h2 style="text-align:center;margin-bottom:12px;">كتب مميزة</h2>';
$html .= '<div style="display:flex;flex-wrap:wrap;justify-content:center;">' . $items_html . '</div>';
$html .= '<div style="text-align:center;margin-top:18px;"><a class="button library-download" href="/archive-book.php">عرض كل الكتب</a></div>';
$html .= '</div>';

wp_update_post( array( 'ID' => $home_id, 'post_content' => $html ) );

// Set site title and tagline in Arabic
update_option( 'blogname', 'مكتبتي الرقمية' );
update_option( 'blogdescription', 'مكتبة إلكترونية للكتب الجامعية' );

// Create English draft page for translation
$en = array(
    'post_title' => 'Home',
    'post_content' => '<p>Welcome to the Digital Library. Browse and download books.</p>',
    'post_status' => 'draft',
    'post_type' => 'page',
);
$en_id = wp_insert_post( $en );

if ( $en_id && ! is_wp_error( $en_id ) ) {
    echo "Created English draft page: $en_id\n";
}

echo "Homepage updated and site title set.\n";
