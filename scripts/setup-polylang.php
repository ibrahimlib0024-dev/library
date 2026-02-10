<?php
// Programmatically add Arabic and English languages and link the homepage translations
if ( ! function_exists( 'PLL' ) ) {
    echo "Polylang not active.\n";
    return;
}
$existing = pll_languages_list( array( 'fields' => 'slug' ) );
if ( ! in_array( 'ar', $existing, true ) ) {
    $r = PLL()->model->add_language( array(
        'name' => 'العربية',
        'slug' => 'ar',
        'locale' => 'ar',
        'rtl' => true,
        'term_group' => 1,
    ) );
    if ( is_wp_error( $r ) ) {
        echo "Failed to add Arabic: " . $r->get_error_message() . "\n";
    } else {
        echo "Arabic language added.\n";
    }
} else {
    echo "Arabic already present.\n";
}
if ( ! in_array( 'en', $existing, true ) ) {
    $r = PLL()->model->add_language( array(
        'name' => 'English',
        'slug' => 'en',
        'locale' => 'en_US',
        'rtl' => false,
        'term_group' => 2,
    ) );
    if ( is_wp_error( $r ) ) {
        echo "Failed to add English: " . $r->get_error_message() . "\n";
    } else {
        echo "English language added.\n";
    }
} else {
    echo "English already present.\n";
}
// Make Arabic default
PLL()->model->update_default_lang( 'ar' );
// Link the Arabic homepage (ID 15) with English draft (ID 17)
$arabic_home = 15;
$english_home = 17;
if ( get_post( $arabic_home ) && get_post( $english_home ) ) {
    pll_set_post_language( $arabic_home, 'ar' );
    pll_set_post_language( $english_home, 'en' );
    pll_save_post_translations( array( 'ar' => $arabic_home, 'en' => $english_home ) );
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $arabic_home );
    echo "Linked pages and set front page to Arabic (ID: $arabic_home).\n";
} else {
    echo "Could not find one of the pages to link.\n";
}
?>