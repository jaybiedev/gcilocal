<?php

/**
 * Helper class
 */

class PostHelper {

    function getEvents($limit=4) {

        $args = array(
            'post_type' => 'events',
            'orderby' => 'menu_order',
            'orderby' => 'meta_value',
            'meta_key' => 'start_date',
            'order' => 'ASC',
            'posts_per_page' => $limit,
        );

        $eventItems = new WP_Query( $args );
        return $eventItems;
    }


    function getVideos($limit=4) {
        $args = array(
            'post_type' => 'videos',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'posts_per_page' => $limit,
        );

        $videoItems = new WP_Query( $args );

        return $videoItems;
    }

    function getArticles($limit = 4) {
        $args = array(
            'post_type' => 'articles',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'posts_per_page' => $limit,
        );
        $articleItems = new WP_Query( $args );

        return $articleItems;
    }

    function getMenuItems($menu='Main', $args=array()) {
        $menu_items = wp_get_nav_menu_items( $menu, $args );

        if (!$menu_items)
            return array();

        return $menu_items;
    }
}