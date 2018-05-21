<?php
/* ==================================================
  Sermons Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'sermons_register', 0);
function sermons_register() {
    $args_c = array(
    "label" => __('Sermons Categories','framework'),
    "singular_label" => __('Sermons Category','framework'),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
   'query_var' => true,
   'show_admin_column' => true,
);
register_taxonomy('sermons-category', 'sermons',$args_c);
$args_tag = array(
    "label" => __('Sermons Tag','framework'),
    "singular_label" => __('Sermons Tag','framework'),
    'public' => true,
    'hierarchical' => false,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
   'query_var' => true,
   'show_admin_column' => true,
);
register_taxonomy('sermons-tag', 'sermons', $args_tag);
$args_sermons_speaker = array(
    "label" => __('Sermons Speakers','framework'),
    "singular_label" => __('Sermons Speakers','framework'),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    //'args' => array('orderby' => 'term_order'),
    'rewrite' => true,
   'query_var' => true,
   'show_admin_column' => true,
);
register_taxonomy('sermons-speakers', 'sermons',$args_sermons_speaker);
    $labels = array(
        'name' => __('Sermons', 'framework'),
        'singular_name' => __('Sermons Item','framework'),
        'add_new' => __('Add New', 'framework'),
        'add_new_item' => __('Add New Sermons Item', 'framework'),
        'edit_item' => __('Edit Sermons Item', 'framework'),
        'new_item' => __('New Sermons Item', 'framework'),
        'view_item' => __('View Sermons Item', 'framework'),
        'search_items' => __('Search Sermons', 'framework'),
        'not_found' => __('No sermons items have been added yet', 'framework'),
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
        'supports' => array('title', 'editor', 'thumbnail','comments', 'author'),
        'has_archive' => false,
        'taxonomies' => array('sermons-tag','sermons-category','sermons-speakers')
    );
     register_post_type('sermons', $args);
     register_taxonomy_for_object_type('sermons-category','sermons');
     register_taxonomy_for_object_type('sermons-tag','sermons');
     register_taxonomy_for_object_type('sermons-speakers','sermons');
}
?>