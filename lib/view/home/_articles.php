<?php

$home_id = get_option( 'page_on_front' );

$post_category = get_post_meta($home_id,'imic_recent_articles_taxonomy',true);
$posts_per_page = get_post_meta($home_id, 'imic_post_artices_to_show_on', true);
$posts_per_page = !empty($posts_per_page) ? $posts_per_page : 2;
$post_category = !empty($post_category) ? $post_category : 0;

$grid_columns =  intval(12 / ($posts_per_page + $events_per_page));

$temp_wp_query = clone $wp_query;
$news = array();

$news = get_posts(
  array(
      'category'=>$post_category,
      'numberposts'=>$posts_per_page,
  )
);

?>

<div class="listing events-listing">
    <!-- Latest News -->
    <?php
    $list_counter = 0;
    foreach ($news as $item) {
        if ($list_counter > $posts_per_page)
            break;
        ?>
        <div class="col-lg-4">
            <?php
            $Featurebox = new Featurebox();
            // inject Post
            $Featurebox->post_id = $item->ID;
            $Featurebox->Post = $item;
            echo $Featurebox->render();?>
        </div>
        <?php
        $list_counter++;
    }
    ?>
</div>
