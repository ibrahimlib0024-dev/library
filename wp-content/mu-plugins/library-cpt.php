<?php
/**
 * Register Book CPT and taxonomies as an MU-plugin so endpoints exist regardless of theme.
 */

if ( ! function_exists( 'library_mu_register_book_cpt' ) ) {
    function library_mu_register_book_cpt() {
        if ( post_type_exists( 'book' ) ) {
            return;
        }

        $labels = array(
            'name'                  => _x( 'Books', 'Post Type General Name', 'astra-library-child' ),
            'singular_name'         => _x( 'Book', 'Post Type Singular Name', 'astra-library-child' ),
            'menu_name'             => __( 'Library Books', 'astra-library-child' ),
        );
        $args = array(
            'label'                 => __( 'Book', 'astra-library-child' ),
            'description'           => __( 'Digital Library Books', 'astra-library-child' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
            'taxonomies'            => array( 'genre', 'book_author', 'publisher' ),
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
            'show_in_rest'          => true,
        );
        register_post_type( 'book', $args );

        // Register taxonomies if not exist
        if ( ! taxonomy_exists( 'genre' ) ) {
            register_taxonomy( 'genre', array( 'book' ), array(
                'labels' => array('name' => __( 'Genres', 'astra-library-child' )),
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'rewrite' => array( 'slug' => 'genre' ),
            ) );
        }
        if ( ! taxonomy_exists( 'book_author' ) ) {
            register_taxonomy( 'book_author', array( 'book' ), array(
                'labels' => array('name' => __( 'Authors', 'astra-library-child' )),
                'hierarchical' => false,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'rewrite' => array( 'slug' => 'author' ),
            ) );
        }
        if ( ! taxonomy_exists( 'publisher' ) ) {
            register_taxonomy( 'publisher', array( 'book' ), array(
                'labels' => array('name' => __( 'Publishers', 'astra-library-child' )),
                'hierarchical' => false,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'rewrite' => array( 'slug' => 'publisher' ),
            ) );
        }
    }
    add_action( 'init', 'library_mu_register_book_cpt', 0 );
}
