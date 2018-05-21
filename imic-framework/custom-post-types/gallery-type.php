<?php
/* ==================================================
  Gallery Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'gallery_register');
function gallery_register() {
	$args_c = array(
    "label" => __('Gallery Categories', "imic-framework-admin"),
    "singular_label" => __('Gallery Category', "imic-framework-admin"),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
    'query_var' => true,
	'show_admin_column' => true,
);
register_taxonomy('gallery-category', 'gallery', $args_c);
    $labels = array(
        'name' => __('Gallery', 'framework'),
        'singular_name' => __('Gallery Item', 'framework'),
        'add_new' => __('Add New', 'framework'),
        'all_items'=> __('Gallery items', 'framework'),
        'add_new_item' => __('Add New Gallery Item', 'framework'),
        'edit_item' => __('Edit Gallery Item', 'framework'),
        'new_item' => __('New Gallery Item', 'framework'),
        'view_item' => __('View Gallery Item', 'framework'),
        'search_items' => __('Search Gallery', 'framework'),
        'not_found' => __('No gallery items have been added yet', 'framework'),
        'not_found_in_trash' => __('Nothing found in Trash', 'framework'),
        'parent_item_colon' => '',
    );
   $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array('title', 'thumbnail','post-formats', 'author'),
        'has_archive' => true,
       );
    register_post_type('gallery', $args);
	register_taxonomy_for_object_type('gallery-category','gallery');
}
?>