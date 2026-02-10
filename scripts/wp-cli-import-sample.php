<?php
// WP-CLI import script for sample-books.csv
// Run with: php wp-cli.phar eval-file scripts/wp-cli-import-sample.php

$csv_file = __DIR__ . '/sample-books.csv';
if ( ! file_exists( $csv_file ) ) {
    WP_CLI::error( "CSV file not found: $csv_file" );
}

$lines = array_filter( array_map( 'trim', file( $csv_file ) ) );
$header = null;
$count = 0;
foreach ( $lines as $line ) {
    $row = str_getcsv( $line );
    if ( ! $header ) { $header = $row; continue; }
    $data = array_combine( $header, $row );
    if ( empty( $data['title'] ) ) continue;

    $post_id = wp_insert_post( array(
        'post_title' => sanitize_text_field( $data['title'] ),
        'post_content' => wp_kses_post( $data['content'] ?? '' ),
        'post_status' => 'publish',
        'post_type' => 'book',
    ) );
    if ( is_wp_error( $post_id ) ) {
        WP_CLI::warning( 'Failed to create post: ' . $data['title'] );
        continue;
    }

    if ( ! empty( $data['short'] ) ) update_post_meta( $post_id, '_book_short', sanitize_text_field( $data['short'] ) );
    if ( ! empty( $data['year'] ) ) update_post_meta( $post_id, '_book_year', sanitize_text_field( $data['year'] ) );
    if ( ! empty( $data['isbn'] ) ) update_post_meta( $post_id, '_book_isbn', sanitize_text_field( $data['isbn'] ) );
    if ( ! empty( $data['pdf'] ) ) update_post_meta( $post_id, '_book_pdf', sanitize_text_field( $data['pdf'] ) );

    if ( ! empty( $data['authors'] ) ) {
        $terms = preg_split('/[;,]+/', $data['authors']);
        $terms = array_map('trim', $terms);
        wp_set_object_terms( $post_id, $terms, 'book_author', true );
    }
    if ( ! empty( $data['publishers'] ) ) {
        $terms = preg_split('/[;,]+/', $data['publishers']);
        $terms = array_map('trim', $terms);
        wp_set_object_terms( $post_id, $terms, 'publisher', true );
    }
    if ( ! empty( $data['genres'] ) ) {
        $terms = preg_split('/[;,]+/', $data['genres']);
        $terms = array_map('trim', $terms);
        wp_set_object_terms( $post_id, $terms, 'genre', true );
    }

    if ( ! empty( $data['cover_url'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $tmp = download_url( esc_url_raw( $data['cover_url'] ) );
        if ( ! is_wp_error( $tmp ) ) {
            $file_array = array();
            $file_array['name'] = basename( $data['cover_url'] );
            $file_array['tmp_name'] = $tmp;
            $attach_id = media_handle_sideload( $file_array, $post_id );
            if ( ! is_wp_error( $attach_id ) ) set_post_thumbnail( $post_id, $attach_id );
            else WP_CLI::warning( 'Failed to sideload cover for: ' . $data['title'] );
        } else {
            WP_CLI::warning( 'Download failed for cover: ' . $data['cover_url'] );
        }
    }

    $count++;
}

WP_CLI::success( "Imported $count books from sample CSV." );
