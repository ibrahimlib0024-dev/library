<?php
/**
 * Library Security MU-plugin
 * Basic hardening applied early via mu-plugins so it cannot be disabled from WP admin.
 */

// Prevent file editor in admin
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WP version generator tag
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Remove version query strings from static resources
function library_remove_script_version( $src ) {
    $parts = explode( '?', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'library_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'library_remove_script_version', 15, 1 );

// Block REST API for non-authenticated users from exposing user data (simple)
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );

// Admin notice recommending stronger DB password and SSL for production
add_action( 'admin_notices', function() {
    if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG ) return;
    $msg = 'Security: consider using a non-default DB password and enable SSL/HTTPS for admin area on production.';
    echo '<div class="notice notice-warning"><p>' . esc_html( $msg ) . '</p></div>';
} );
