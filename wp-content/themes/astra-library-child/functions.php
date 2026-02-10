<?php
/**
 * Astra Library Child Theme functions and definitions
 */

// Enqueue Parent Styles
function library_child_enqueue_styles() {
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'library_child_enqueue_styles' );

/**
 * Register Custom Post Type: Books
 */
function library_register_book_cpt() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post Type General Name', 'astra-library-child' ),
        'singular_name'         => _x( 'Book', 'Post Type Singular Name', 'astra-library-child' ),
        'menu_name'             => __( 'Library Books', 'astra-library-child' ),
        'name_admin_bar'        => __( 'Book', 'astra-library-child' ),
        'archives'              => __( 'Book Archives', 'astra-library-child' ),
        'attributes'            => __( 'Book Attributes', 'astra-library-child' ),
        'parent_item_colon'     => __( 'Parent Book:', 'astra-library-child' ),
        'all_items'             => __( 'All Books', 'astra-library-child' ),
        'add_new_item'          => __( 'Add New Book', 'astra-library-child' ),
        'add_new'               => __( 'Add New', 'astra-library-child' ),
        'new_item'              => __( 'New Book', 'astra-library-child' ),
        'edit_item'             => __( 'Edit Book', 'astra-library-child' ),
        'update_item'           => __( 'Update Book', 'astra-library-child' ),
        'view_item'             => __( 'View Book', 'astra-library-child' ),
        'view_items'            => __( 'View Books', 'astra-library-child' ),
        'search_items'          => __( 'Search Book', 'astra-library-child' ),
        'not_found'             => __( 'Not found', 'astra-library-child' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'astra-library-child' ),
        'featured_image'        => __( 'Book Cover', 'astra-library-child' ),
        'set_featured_image'    => __( 'Set book cover', 'astra-library-child' ),
        'remove_featured_image' => __( 'Remove book cover', 'astra-library-child' ),
        'use_featured_image'    => __( 'Use as book cover', 'astra-library-child' ),
        'insert_into_item'      => __( 'Insert into book', 'astra-library-child' ),
        'uploaded_to_this_item' => __( 'Uploaded to this book', 'astra-library-child' ),
        'items_list'            => __( 'Books list', 'astra-library-child' ),
        'items_list_navigation' => __( 'Books list navigation', 'astra-library-child' ),
        'filter_items_list'     => __( 'Filter books list', 'astra-library-child' ),
    );
    $args = array(
        'label'                 => __( 'Book', 'astra-library-child' ),
        'description'           => __( 'Digital Library Books', 'astra-library-child' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
        'taxonomies'            => array( 'genre', 'book_author', 'publisher' ), // Helper taxonomies
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enable Gutenberg editor
    );
    register_post_type( 'book', $args );
}
add_action( 'init', 'library_register_book_cpt', 0 );

/**
 * Register Taxonomies: Genre, Author, Publisher
 */
function library_register_taxonomies() {
    // Genre
    register_taxonomy( 'genre', array( 'book' ), array(
        'labels' => array(
            'name' => __( 'Genres', 'astra-library-child' ),
            'singular_name' => __( 'Genre', 'astra-library-child' ),
            'add_new_item' => __( 'Add New Genre', 'astra-library-child' ),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'genre' ),
    ) );

    // Author (Taxonomy, distinct from WP Users)
    register_taxonomy( 'book_author', array( 'book' ), array(
        'labels' => array(
            'name' => __( 'Authors', 'astra-library-child' ),
            'singular_name' => __( 'Author', 'astra-library-child' ),
            'add_new_item' => __( 'Add New Author', 'astra-library-child' ),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'author' ),
    ) );

    // Publisher
    register_taxonomy( 'publisher', array( 'book' ), array(
        'labels' => array(
            'name' => __( 'Publishers', 'astra-library-child' ),
            'singular_name' => __( 'Publisher', 'astra-library-child' ),
            'add_new_item' => __( 'Add New Publisher', 'astra-library-child' ),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'publisher' ),
    ) );
}
add_action( 'init', 'library_register_taxonomies', 0 );

/**
 * Meta boxes for Book custom fields
 */
function library_add_book_metaboxes() {
    add_meta_box( 'library_book_details', __( 'Book Details', 'astra-library-child' ), 'library_book_details_callback', 'book', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'library_add_book_metaboxes' );

function library_book_details_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'library_book_nonce' );
    $cover_id = get_post_meta( $post->ID, '_book_cover_id', true );
    $pdf = get_post_meta( $post->ID, '_book_pdf', true );
    $short = get_post_meta( $post->ID, '_book_short', true );
    $year = get_post_meta( $post->ID, '_book_year', true );
    $isbn = get_post_meta( $post->ID, '_book_isbn', true );
    ?>
    <p>
        <label><?php _e( 'Cover Image', 'astra-library-child' ); ?></label><br/>
        <input type="hidden" name="_book_cover_id" id="_book_cover_id" value="<?php echo esc_attr( $cover_id ); ?>" />
        <img id="_book_cover_preview" src="<?php echo $cover_id ? esc_url( wp_get_attachment_url( $cover_id ) ) : ''; ?>" style="max-width:150px;display:block;margin-bottom:8px;" />
        <button type="button" class="button" id="library_set_cover"><?php _e( 'Set cover', 'astra-library-child' ); ?></button>
        <button type="button" class="button" id="library_remove_cover"><?php _e( 'Remove', 'astra-library-child' ); ?></button>
    </p>
    <p>
        <label for="_book_pdf"><?php _e( 'PDF File URL (or Download Monitor ID)', 'astra-library-child' ); ?></label><br/>
        <input type="text" name="_book_pdf" id="_book_pdf" value="<?php echo esc_attr( $pdf ); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="_book_short"><?php _e( 'Short Description', 'astra-library-child' ); ?></label><br/>
        <textarea name="_book_short" id="_book_short" rows="4" style="width:100%;"><?php echo esc_textarea( $short ); ?></textarea>
    </p>
    <p>
        <label for="_book_year"><?php _e( 'Publication Year', 'astra-library-child' ); ?></label><br/>
        <input type="text" name="_book_year" id="_book_year" value="<?php echo esc_attr( $year ); ?>" />
    </p>
    <p>
        <label for="_book_isbn"><?php _e( 'Deposit Number / ISBN', 'astra-library-child' ); ?></label><br/>
        <input type="text" name="_book_isbn" id="_book_isbn" value="<?php echo esc_attr( $isbn ); ?>" style="width:100%;" />
    </p>
    <?php
}

function library_save_book_meta( $post_id ) {
    if ( ! isset( $_POST['library_book_nonce'] ) || ! wp_verify_nonce( $_POST['library_book_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( isset( $_POST['post_type'] ) && 'book' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
    }

    $fields = array( '_book_cover_id', '_book_pdf', '_book_short', '_book_year', '_book_isbn' );
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'library_save_book_meta' );

/**
 * Enqueue admin and frontend scripts for media uploader and AJAX
 */
function library_enqueue_scripts() {
    // Frontend script
    wp_enqueue_script( 'library-scripts', get_stylesheet_directory_uri() . '/js/library-scripts.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'library-scripts', 'LibraryAjax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'library-ajax-nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'library_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'library_enqueue_scripts' );

/**
 * AJAX: Toggle favorite for logged-in users
 */
function library_ajax_toggle_favorite() {
    check_ajax_referer( 'library-ajax-nonce', 'nonce' );
    if ( ! is_user_logged_in() ) wp_send_json_error( 'login_required' );
    $user_id = get_current_user_id();
    $book_id = isset( $_POST['book_id'] ) ? intval( $_POST['book_id'] ) : 0;
    if ( ! $book_id ) wp_send_json_error( 'invalid_book' );
    $favs = get_user_meta( $user_id, '_library_favorites', true );
    if ( ! is_array( $favs ) ) $favs = array();
    if ( in_array( $book_id, $favs ) ) {
        $favs = array_diff( $favs, array( $book_id ) );
        update_user_meta( $user_id, '_library_favorites', $favs );
        wp_send_json_success( array( 'action' => 'removed' ) );
    } else {
        $favs[] = $book_id;
        update_user_meta( $user_id, '_library_favorites', $favs );
        wp_send_json_success( array( 'action' => 'added' ) );
    }
}
add_action( 'wp_ajax_library_toggle_favorite', 'library_ajax_toggle_favorite' );

/**
 * AJAX: Submit rating (1-5)
 */
function library_ajax_rate_book() {
    check_ajax_referer( 'library-ajax-nonce', 'nonce' );
    if ( ! is_user_logged_in() ) wp_send_json_error( 'login_required' );
    $user_id = get_current_user_id();
    $book_id = isset( $_POST['book_id'] ) ? intval( $_POST['book_id'] ) : 0;
    $rating = isset( $_POST['rating'] ) ? intval( $_POST['rating'] ) : 0;
    if ( ! $book_id || $rating < 1 || $rating > 5 ) wp_send_json_error( 'invalid' );

    // prevent multiple ratings per user, store user rating in user meta
    $user_ratings = get_user_meta( $user_id, '_library_ratings', true );
    if ( ! is_array( $user_ratings ) ) $user_ratings = array();
    $previous = isset( $user_ratings[ $book_id ] ) ? intval( $user_ratings[ $book_id ] ) : 0;

    $count = intval( get_post_meta( $book_id, '_rating_count', true ) );
    $sum = intval( get_post_meta( $book_id, '_rating_sum', true ) );

    if ( $previous ) {
        // update
        $sum = $sum - $previous + $rating;
    } else {
        $count = $count + 1;
        $sum = $sum + $rating;
    }

    update_post_meta( $book_id, '_rating_count', $count );
    update_post_meta( $book_id, '_rating_sum', $sum );

    $user_ratings[ $book_id ] = $rating;
    update_user_meta( $user_id, '_library_ratings', $user_ratings );

    $avg = $count ? round( $sum / $count, 2 ) : 0;
    wp_send_json_success( array( 'avg' => $avg, 'count' => $count ) );
}
add_action( 'wp_ajax_library_rate_book', 'library_ajax_rate_book' );

/**
 * Helper: render favorite button (use in templates)
 */
function library_favorite_button( $book_id ) {
    $class = 'library-fav-button';
    $text = __( 'Add to My Library', 'astra-library-child' );
    if ( is_user_logged_in() ) {
        $favs = get_user_meta( get_current_user_id(), '_library_favorites', true );
        if ( is_array( $favs ) && in_array( $book_id, $favs ) ) {
            $class .= ' active';
            $text = __( 'Remove from My Library', 'astra-library-child' );
        }
    }
    return '<button data-book-id="' . intval( $book_id ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $text ) . '</button>';
}

/**
 * Include `book` CPT in regular search queries
 */
function library_include_books_in_search( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( $query->is_search() ) {
        $post_type = $query->get( 'post_type' );
        if ( empty( $post_type ) ) {
            $query->set( 'post_type', array( 'post', 'page', 'book' ) );
        } else {
            if ( is_array( $post_type ) ) {
                if ( ! in_array( 'book', $post_type, true ) ) {
                    $post_type[] = 'book';
                    $query->set( 'post_type', $post_type );
                }
            } else {
                if ( 'book' !== $post_type ) {
                    $query->set( 'post_type', array( $post_type, 'book' ) );
                }
            }
        }
    }
}
add_action( 'pre_get_posts', 'library_include_books_in_search' );

/**
 * Relevanssi: add book meta and taxonomies to indexed content
 */
function library_relevanssi_index_meta( $content, $post ) {
    if ( empty( $post ) || 'book' !== $post->post_type ) {
        return $content;
    }

    $meta_keys = array( '_book_short', '_book_year', '_book_isbn', '_book_pdf' );
    foreach ( $meta_keys as $key ) {
        $vals = get_post_meta( $post->ID, $key );
        if ( $vals ) {
            foreach ( $vals as $v ) {
                $content .= ' ' . wp_strip_all_tags( $v );
            }
        }
    }

    $taxonomies = array( 'book_author', 'publisher', 'genre' );
    foreach ( $taxonomies as $tax ) {
        $terms = get_the_terms( $post->ID, $tax );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $t ) {
                $content .= ' ' . $t->name;
            }
        }
    }

    return $content;
}
add_filter( 'relevanssi_content_to_index', 'library_relevanssi_index_meta', 10, 2 );

/**
 * Admin notice recommending Relevanssi if not installed
 */
function library_relevanssi_admin_notice() {
    if ( ! function_exists( 'relevanssi_do_query' ) && ! class_exists( 'Relevanssi' ) ) {
        echo '<div class="notice notice-info"><p>' . sprintf( __( 'For better search results including book fields, consider installing <a href="%s">Relevanssi</a>.', 'astra-library-child' ), admin_url( 'plugin-install.php?s=Relevanssi&tab=search' ) ) . '</p></div>';
    }
}
add_action( 'admin_notices', 'library_relevanssi_admin_notice' );

