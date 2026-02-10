<?php
/**
 * Theme customizations: enqueue Arabic fonts and university color scheme
 */

add_action( 'wp_enqueue_scripts', function() {
    // Google Fonts: Tajawal (Arabic) + Roboto (Latin)
    wp_enqueue_style( 'library-google-fonts', 'https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&family=Roboto:wght@300;400;700&display=swap', array(), null );
    // Custom overrides
    $custom_css = "
        body { font-family: 'Tajawal', 'Roboto', sans-serif; }
        :root { --uni-primary: #003366; --uni-accent: #007a3d; --uni-muted: #f3f3f3; --uni-gold: #cda434; }
        .site-header, .site-footer { background: var(--uni-primary); color: #fff; }
        a, .button, .library-fav-button { background: var(--uni-accent); color: #fff; }
        .library-download { background: var(--uni-gold); color: #000; }
    ";
    // Ensure a handle exists so inline styles are printed
    wp_register_style( 'library-styles', false );
    wp_enqueue_style( 'library-styles' );
    // Header/footer specific styles
    $custom_css .= "
        .library-univ-header { text-align: center; direction: rtl; padding: 18px 12px; background: var(--uni-primary); color: #fff; }
        .library-univ-header .library-univ-inner { max-width: 1100px; margin: 0 auto; }
        .library-univ-header .library-univ-inner .line1 { font-size: 20px; font-weight:700; }
        .library-univ-header .library-univ-inner .line2 { font-size: 18px; margin-top:4px; }
        .library-univ-header .library-univ-inner .line3 { font-size: 16px; margin-top:6px; font-weight:600; }
        .library-designer-credit { text-align: center; direction: rtl; padding: 18px 12px; color: #fff; background: rgba(0,0,0,0.04); }
        .library-designer-credit .credit-inner { max-width: 1100px; margin: 0 auto; font-size: 14px; }
    ";
    wp_add_inline_style( 'library-styles', $custom_css );
} );

/**
 * Render the three-line university header at the top of the page.
 */
function library_render_university_header() {
    echo "\n<!-- University header inserted by MU-plugin -->\n";
    echo '<div class="library-univ-header" role="banner" aria-label="رأسية الجامعة">';
    echo '<div class="library-univ-inner">';
    echo '<div class="line1">جامعة البحر الاحمر</div>';
    echo '<div class="line2">عمادة المكتبات</div>';
    echo '<div class="line3">المكتبة الرقمية</div>';
    echo '</div>';
    echo '</div>\n';
}
add_action( 'wp_body_open', 'library_render_university_header', 5 );

/**
 * Render footer designer credit.
 */
function library_render_designer_credit() {
    echo "\n<!-- Designer credit inserted by MU-plugin -->\n";
    echo '<div class="library-designer-credit" role="contentinfo">';
    echo '<div class="credit-inner">';
    echo 'المصمم: ابراهيم حامد موسي &nbsp; &nbsp; &middot; &nbsp; &nbsp; نوفمبر 2026';
    echo '</div>';
    echo '</div>\n';
}
add_action( 'wp_footer', 'library_render_designer_credit', 20 );
