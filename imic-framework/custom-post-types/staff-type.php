<?php
/* ==================================================
  Staff Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'staff_register');
function staff_register() {
       $args_c = array(
    "label" => __('Staff Categories', "imic-framework-admin"),
    "singular_label" => __('Staff Category', "imic-framework-admin"),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
    'query_var' => true,
	'show_admin_column' => true,
);
register_taxonomy('staff-category', 'staff', $args_c);
    $labels = array(
        'name' => __('Staff', 'framework'),
        'singular_name' => __('Staff', 'framework'),
        'all_items'=> __('Staff Members', 'framework'),
        'add_new' => __('Add New', 'framework'),
        'add_new_item' => __('Add New Staff', 'framework'),
        'edit_item' => __('Edit Staff', 'framework'),
        'new_item' => __('New Staff', 'framework'),
        'view_item' => __('View Staff', 'framework'),
        'search_items' => __('Search Staff', 'framework'),
        'not_found' => __('No staff have been added yet', 'framework'),
        'not_found_in_trash' => __('Nothing found in Trash', 'framework'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
		'capability_type' => 'page',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes','excerpt', 'author'),
        'has_archive' => true,
    );
    register_post_type('staff', $args);
}
?>