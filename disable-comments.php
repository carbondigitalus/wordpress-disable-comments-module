<?php

function disable_comments_support() {
    // Post types for which comments should be disabled
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

function disable_comments_admin_menu() {
    // Remove comments from the admin menu
    remove_menu_page('edit-comments.php');
}

function disable_comments_admin_bar() {
    // Remove comments from the admin bar
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

function disable_comments_dashboard() {
    // Remove comments from the dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

function disable_comments_redirect() {
    // Redirect any user trying to access comments page
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}

// Hooks to disable comments
add_action('admin_init', 'disable_comments_support');
add_action('admin_menu', 'disable_comments_admin_menu');
add_action('wp_before_admin_bar_render', 'disable_comments_admin_bar');
add_action('wp_dashboard_setup', 'disable_comments_dashboard');
add_action('admin_init', 'disable_comments_redirect');

// Disable comments and pings on existing posts
function disable_comments_status() {
    return false;
}

add_filter('comments_open', 'disable_comments_status', 20, 2);
add_filter('pings_open', 'disable_comments_status', 20, 2);

// Hide existing comments
function disable_comments_hide_existing($comments) {
    $comments = array();
    return $comments;
}

add_filter('comments_array', 'disable_comments_hide_existing', 10, 2);

?>
