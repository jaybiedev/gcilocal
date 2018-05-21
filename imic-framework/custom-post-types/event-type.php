<?php
/* ==================================================
  Event Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'event_register');
function event_register() {
    $args_c = array(
    "label" => __('Event Categories', "imic-framework-admin"),
    "singular_label" => __('Event Category', "imic-framework-admin"),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
    'query_var' => true,
	'show_admin_column' => true,
);
register_taxonomy('event-category', 'event', $args_c);
    $labels = array(
        'name' => __('Events', 'framework'),
        'singular_name' => __('Event', 'framework'),
        'add_new' => __('Add New', 'framework'),
        'add_new_item' => __('Add New Event', 'framework'),
        'edit_item' => __('Edit Event', 'framework'),
        'new_item' => __('New Event', 'framework'),
        'view_item' => __('View Event', 'framework'),
        'search_items' => __('Search Event', 'framework'),
        'not_found' => __('No events have been added yet', 'framework'),
        'not_found_in_trash' => __('Nothing found in Trash', 'framework'),
        'parent_item_colon' => '',
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'author'),
        'has_archive' => true,
        'taxonomies' => array('event-category'),
	
    );
    register_post_type('event', $args);
    register_taxonomy_for_object_type('event-category','event');
}
?>