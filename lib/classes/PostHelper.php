<?php
include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/Spol.php");
include_once(get_template_directory() . "/lib/classes/Sermon.php");

/**
 * Helper class
 */

class PostHelper {

    public static function getHomePageCards($section=null) {

        static $card_all = array();
        static $card_news = array();
        static $card_events = array();
        static $card_media = array();
        static $card_articles = array();
        static $card_home_id = null;
        
        if (empty($card_home_id))
            $card_home_id = get_option( 'page_on_front' );
        
        // news
        if (empty($card_news)) {
            $post_category = get_post_meta($home_id,'imic_recent_post_taxonomy',true);
            $posts_per_page = get_post_meta($home_id, 'imic_posts_to_show_on', true);
            $posts_per_page = !empty($posts_per_page) ? $posts_per_page : 2;
            $post_category = !empty($post_category) ? $post_category : 0;            
            $news = get_posts(array(
                    'category'=>$post_category,
                    'numberposts'=>$posts_per_page,
                ));
            foreach ($news as $item) {
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                $card_news[] = $Featurebox;
                unset($Featurebox);                
            }
        }

        // media
        if (empty($card_media)) {
            
            $Spol = new Spol();
            $media = array();            
            $spols = $Spol->getAll();
            
            $Sermon = new Sermon();
            $sermons = $Sermon->getAll();
            
            $post_per_page = get_post_meta($home_id, 'imic_media_to_show_on', true);
            if (empty($post_per_page))
                $post_per_page = 9;
                
            if (!empty($sermons)) {
                $start_sermon = 1;  // skip the first
                array_walk($sermons, function($item, $key) {
                    $item->post_type = 'media-sermon';
                    $item->permalink = get_permalink($item);
                    $item->author = get_the_term_list($item->ID, 'sermons-speakers', '', ', ', '' );
                });
                    
                $media = array_slice($sermons, $start_sermon, abs($post_per_page/2));
            }
            
            // merge sermons and spols
            $media = array_merge($media, array_slice($spols, 0, $post_per_page - count($media)));

            foreach ($media as $item) {
                $Featurebox = new Featurebox();
                // inject Post
                $item->post_date = date('Y-m-d g:i:s', strtotime($item->post_date));
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                $card_media[] = $Featurebox;
                unset($Featurebox);
            }                
        }
            
        // events
        if (empty($card_events)) {
            $events_per_page = get_post_meta($home_id, 'imic_events_to_show_on', true);
            $events_per_page = !empty($events_per_page) ? $events_per_page : 4;
            
            $recent_events_category = get_post_meta($home_id,'imic_recent_events_taxonomy',true);
            
            if(!empty($recent_events_category)){
                $events_categories= get_term_by('id',$recent_events_category,'event-category');
                $recent_events_category= $events_categories->slug;
            }
            $imic_events_to_show_on = get_post_meta($home_id,'imic_events_to_show_on',true);
            $imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
            $event_add_ids = imic_recur_events('future','nos', $recent_events_category,'');
            
            $google_events = getGoogleEvent();
            if(!empty($google_events))
                $recent_event_ids = $google_events + $event_add_ids;
            else
                $recent_event_ids = $event_add_ids;

            $list_counter = 0;
            foreach ($recent_event_ids as $key=>$post_id) {
                if ($list_counter > $events_per_page)
                    break;
                    
                if(preg_match('/^[0-9]+$/', $post_id)) {
                    $item = get_post($post_id);                    
                    $eventStartTime =  get_post_meta($post_id, 'imic_event_start_tm', true);
                    $eventStartDate =  get_post_meta($post_id, 'imic_event_start_dt', true);
                    $eventEndTime   =  strtotime(get_post_meta($post_id, 'imic_event_end_tm', true));
                    $eventEndDate   =  strtotime(get_post_meta($post_id, 'imic_event_end_dt', true));                    
                    // override
                    $item->caption = date('F j, Y D.', strtotime($eventStartDate)) . " @{$eventStartTime}";                    
                }
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                $card_events[] = $Featurebox;
                unset($Featurebox);
                $list_counter++;
            }
        }
            
        // articles
        if (empty($card_articles)) {
            $post_category = get_post_meta($home_id,'imic_recent_articles_taxonomy',true);
            $posts_per_page = get_post_meta($home_id, 'imic_post_artices_to_show_on', true);
            $posts_per_page = !empty($posts_per_page) ? $posts_per_page : 2;
            $post_category = !empty($post_category) ? $post_category : 0;
            
            $articles = get_posts(array(
                    'category'=>$post_category,
                    'numberposts'=>$posts_per_page,
                ));
            foreach ($articles as $item) {
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                $card_articles[] = $Featurebox;
                unset($Featurebox);
            }
        }
        
        $cards = array();
        if (empty($section) || $section == 'all') {
            
            if (empty($card_all)) {
                $card_all = array_merge($card_news, $card_events, $card_media, $card_articles);
                
                // sort
                usort($card_all, function($previous, $next) {
                    return ($next->Post->post_date > $previous->Post->post_date);
                });
            }
            $cards = $card_all;
        }
        elseif ($section == 'news') {
            $cards = $card_news;
        }
        elseif ($section == 'media') {
            $cards = $card_media;
        }
        elseif ($section == 'events') {
            $cards = $card_events;
        }
        elseif ($section == 'articles') {
            $cards = $card_articles;
        }
                
        return $cards;
    }
    
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