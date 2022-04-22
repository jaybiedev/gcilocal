<?php
include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/Spol.php");
include_once(get_template_directory() . "/lib/classes/Sermon.php");

/**
 * Helper class
 */

class PostHelper {

    public static function getHomePageCards($section=array(), $page_id=null) {

        static $card_all = array();
        static $card_news = array();
        static $card_events = array();
        static $card_media = array();
        static $card_articles = array();
        static $card_gallery = array();
        
        if (empty($page_id))
            $page_id = get_option( 'page_on_front' );

	$date_format = get_option( 'date_format' );
        $time_format = get_option( 'time_format' );
        
	// news
        if (empty($card_news)) {
            $post_category = get_post_meta($page_id,'imic_recent_post_taxonomy',true);
            $posts_per_page = get_post_meta($page_id, 'imic_posts_to_show_on', true);
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
            
            $post_per_page = get_post_meta($page_id, 'imic_media_to_show_on', true);
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
			
			echo "\n <!-- ================================================================================ \nMedia=\n";
			print_r($media);
			echo "\n ================================================================================= -->\n";
			

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
            $events_per_page = get_post_meta($page_id, 'imic_events_to_show_on', true);
            $events_per_page = !empty($events_per_page) ? $events_per_page : 4;
            
            $recent_events_category = get_post_meta($page_id,'imic_recent_events_taxonomy',true);
            if(!empty($recent_events_category)){
                $events_categories= get_term_by('id',$recent_events_category,'event-category');
                $recent_events_category= $events_categories->slug;
            }
            
            $imic_events_to_show_on = get_post_meta($page_id,'imic_events_to_show_on',true);
            $imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
            $event_add_ids = imic_recur_events('future','nos', $recent_events_category,'', false);
            
            $google_events = getGoogleEvent();
            if(!empty($google_events))
                $recent_event_ids = $google_events + $event_add_ids;
            else
                $recent_event_ids = $event_add_ids;

            $list_counter = 0;
            foreach ($recent_event_ids as $key=>$post_id) {
                if ($list_counter >= $events_per_page)
                    break;
                    
                if(preg_match('/^[0-9]+$/', $post_id)) {
                    $item = get_post($post_id);                    
                    $eventStartTime =  get_post_meta($post_id, 'imic_event_start_tm', true);
                    $eventStartDate =  get_post_meta($post_id, 'imic_event_start_dt', true);
                    $eventEndTime   =  strtotime(get_post_meta($post_id, 'imic_event_end_tm', true));
                    $eventEndDate   =  strtotime(get_post_meta($post_id, 'imic_event_end_dt', true));                    
                    // override
                    $item->caption = date('F j, Y D.', strtotime($eventStartDate)) . " @" . self::getFormattedTime($eventStartTime);                    
                }
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $post_id; //$item->ID;
                $Featurebox->Post = $item;
                $card_events[] = $Featurebox;
                unset($Featurebox);
                $list_counter++;
            }
        }
            
        // articles
        if (empty($card_articles)) {
            $post_category = get_post_meta($page_id,'imic_recent_articles_taxonomy',true);
            $posts_per_page = get_post_meta($page_id, 'imic_post_artices_to_show_on', true);
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
        
        // gallery
        if (empty($card_gallery)) {
            $gallery_category = get_post_meta($page_id,'imic_home_gallery_taxonomy', true);
            
            if(!empty($gallery_category)){
                $gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
                $gallery_category= $gallery_categories->slug;
            }
            
            $posts_per_page = get_post_meta($page_id,'imic_galleries_to_show_on',true);
            $posts_per_page=!empty($posts_per_page)?$posts_per_page:9;
            
            $posts = get_posts(array(
                'post_type' => 'gallery',
                'gallery-category' => $gallery_category,
                'posts_per_page' => $posts_per_page,
            ));
            
            foreach ($posts as $item) {
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                $card_gallery[] = $Featurebox;
                unset($Featurebox);
            }
        }

        $cards = array();
        $card_all = array();
        foreach ((array)$section as $sec) {
            if ($sec == 'all') {
                $card_all = array_merge($card_news, $card_events, $card_media, $card_articles, $card_gallery);
                break;
            }
            elseif ($sec == 'news') {
                $card_all = array_merge($card_all, $card_news);
            }
            elseif ($sec == 'media') {
                $card_all = array_merge($card_all, $card_media);
            }
            elseif ($sec == 'events') {
                $card_all = array_merge($card_all, $card_events);
            }
            elseif ($sec == 'articles') {
                $card_all = array_merge($card_all, $card_articles);
            }
            elseif ($sec == 'gallery') {
                $card_all = array_merge($card_all, $card_gallery);
            }
        }
        
	/* 
        usort($card_all, function($previous, $next) {
            return ($previous->Post->post_date - $next->Post->post_date);
        });
	*/

	//usort($card_all, 'comparePostDate');
		$dates=array();
		foreach ($card_all as $card) {
			$dates[] = $card->Post->post_date;
		}
		array_multisort($dates, SORT_DESC, $card_all);
		
		 /* Debug
		echo "/n<!-- --------------------------------------------------------------------------------\n";
		echo "Dates:\n";
		print_r($dates);
		echo "\n\nCard_all:\n";
		print_r($card_all);
		echo "/n--------------------------------------------------------------------------------- -->\n";
		// */
		
		
        $cards = $card_all;
        
        /*
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
        */
                
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

    function getFormattedTime($_time)
    {
        static $time_format;

        if (empty($time_format))
           $time_format = get_option('time_format');

	return date($time_format, strtotime($_time)); 
    }

    function comparePostDate($PreviousPost, $NextPost) { 
        $datetime1 = strtotime($PreviousPost->post_date); 
        $datetime2 = strtotime($NextPost->post_date); 
        return $datetime1 - $datetime2; 
    }  
}
