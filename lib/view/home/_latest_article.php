<?php
$show_recent_post_area = get_post_meta($home_id,'imic_imic_recent_posts',true);
if ($show_recent_post_area == '2')
    return;

$home_id = get_option( 'page_on_front' );

$post_category = get_post_meta($home_id,'imic_recent_post_taxonomy',true);
$posts_per_page = get_post_meta($home_id, 'imic_posts_to_show_on', true);
$posts_per_page = !empty($posts_per_page) ? $posts_per_page : 1;
$post_category = !empty($post_category) ? $post_category : 0;

$temp_wp_query = clone $wp_query;
$news_events = array('events'=>array(), 'news'=>array());

$articles = get_posts(
  array(
      'category'=>$post_category,
      'numberposts'=>$posts_per_page,
  )
);

?>

<div class="listing article-listing">
    <!-- Latest News -->
    <?php

    foreach ($articles as $item) {
        $custom_fields = get_post_custom($item->ID);
        $title = $item->post_title;
        $content = $item->post_content;
        
        if (!empty($custom_fields['imic_post_page_custom_title'][0]))
            $title = $custom_fields['imic_post_page_custom_title'][0];
        if (!empty($custom_fields['imic_post_custom_description'][0]))
            $content = $custom_fields['imic_post_custom_description'][0];
           
            $image_url = get_the_post_thumbnail_url($item->ID);
        ?>
            	<div class="col-lg-3">
			<div class="latest-article-img" 
				style="background:url('<?php echo $image_url;?>') no-repeat;background-size:contain;">
            		</div>
		</div>
            	<div class="col-lg-9">
            		<h1><?php echo $title;?></h1>
            		<div class="latest-article-body" style="height:165px; overflow:hidden; margin-bottom:20px;width:80%;">
            		<p><?php echo substr($content,0, 500) . "...";?></p>
            		</div>
					<a href=""  class="btn btn-primary">READ MORE</a>
            	</div>
        <?php
            break;
        }
   
    ?>
</div>
