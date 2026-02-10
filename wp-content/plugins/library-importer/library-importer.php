<?php
/**
 * Plugin Name: Library CSV Importer
 * Description: Simple CSV importer to create `book` posts with meta. Admin-only.
 * Version: 1.0.0
 * Author: Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function() {
    add_management_page( 'Library Import', 'Library Import', 'manage_options', 'library-import', 'library_import_page' );
} );

function library_import_page() {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Insufficient privileges' );

    if ( isset( $_POST['library_import_nonce'] ) && wp_verify_nonce( $_POST['library_import_nonce'], 'library_import' ) ) {
        if ( ! empty( $_FILES['library_csv']['tmp_name'] ) ) {
            $csv = file_get_contents( $_FILES['library_csv']['tmp_name'] );
            $lines = array_filter( array_map( 'trim', explode( "\n", $csv ) ) );
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
                if ( is_wp_error( $post_id ) ) continue;

                // meta fields
                if ( ! empty( $data['short'] ) ) update_post_meta( $post_id, '_book_short', sanitize_text_field( $data['short'] ) );
                if ( ! empty( $data['year'] ) ) update_post_meta( $post_id, '_book_year', sanitize_text_field( $data['year'] ) );
                if ( ! empty( $data['isbn'] ) ) update_post_meta( $post_id, '_book_isbn', sanitize_text_field( $data['isbn'] ) );
                if ( ! empty( $data['pdf'] ) ) update_post_meta( $post_id, '_book_pdf', sanitize_text_field( $data['pdf'] ) );

                // taxonomies (comma separated)
                if ( ! empty( $data['authors'] ) ) {
                    $terms = array_map( 'trim', explode( ',', $data['authors'] ) );
                    wp_set_object_terms( $post_id, $terms, 'book_author', true );
                }
                if ( ! empty( $data['publishers'] ) ) {
                    $terms = array_map( 'trim', explode( ',', $data['publishers'] ) );
                    wp_set_object_terms( $post_id, $terms, 'publisher', true );
                }
                if ( ! empty( $data['genres'] ) ) {
                    $terms = array_map( 'trim', explode( ',', $data['genres'] ) );
                    wp_set_object_terms( $post_id, $terms, 'genre', true );
                }

                // cover image sideload if URL provided
                if ( ! empty( $data['cover_url'] ) ) {
                    require_once ABSPATH . 'wp-admin/includes/media.php';
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    $tmp = download_url( esc_url_raw( $data['cover_url'] ) );
                    if ( ! is_wp_error( $tmp ) ) {
                        $file_array = array();
                        $file_array['name'] = basename( $data['cover_url'] );
                        $file_array['tmp_name'] = $tmp;
                        $attach_id = media_handle_sideload( $file_array, $post_id );
                        if ( ! is_wp_error( $attach_id ) ) set_post_thumbnail( $post_id, $attach_id );
                    }
                }

                $count++;
            }
            echo '<div class="updated"><p>' . sprintf( esc_html__( 'Imported %d rows.', 'library-importer' ), $count ) . '</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Library CSV Importer', 'library-importer' ); ?></h1>
        <p><?php esc_html_e( 'Upload a CSV file with columns: title,content,short,year,isbn,pdf,authors,publishers,genres,cover_url', 'library-importer' ); ?></p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'library_import', 'library_import_nonce' ); ?>
            <input type="file" name="library_csv" accept=".csv" required />
            <p><input type="submit" class="button button-primary" value="Import" /></p>
        </form>
    </div>
    <?php
}
