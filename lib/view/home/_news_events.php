<?php

$home_id = get_option( 'page_on_front' );
$show_recent_events_area = get_post_meta($home_id,'imic_imic_upcoming_events',true);
$events_per_page = get_post_meta($home_id, 'imic_events_to_show_on', true);
$events_per_page = !empty($events_per_page) ? $events_per_page : 2;

$show_recent_post_area = get_post_meta($home_id,'imic_imic_recent_posts',true);
$post_category = get_post_meta($home_id,'imic_recent_post_taxonomy',true);
$posts_per_page = get_post_meta($home_id, 'imic_posts_to_show_on', true);
$posts_per_page = !empty($posts_per_page) ? $posts_per_page : 2;
$post_category = !empty($post_category) ? $post_category : 0;

$grid_columns =  intval(12 / ($posts_per_page + $events_per_page));

$temp_wp_query = clone $wp_query;
$news_events = array('events'=>array(), 'news'=>array());

$news_events['news'] = get_posts(
  array(
      'category'=>$post_category,
      'numberposts'=>$posts_per_page,
  )
);

?>

<div class="listing events-listing">
    <?php
    if($show_recent_events_area == 1) { ?>
        <!-- Events Listing -->
        <div class="col-lg-3"><h5>No upcoming events.</h5></div>
    <?php } ?>

    <!-- Latest News -->
    <?php
    if($show_recent_post_area==1) {
        foreach ($news_events['news'] as $item) {
            ?>
            <div class="col-lg-3">
                <?php
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                echo $Featurebox->render();?>
            </div>
            <?php
        }
    }
    ?>
</div>
