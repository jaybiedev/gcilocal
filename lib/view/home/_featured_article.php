<?php
$image_url = get_the_post_thumbnail_url($item->ID);
$front_page_id = get_option( 'page_on_front' );
$featured_article_id = get_post_meta($front_page_id,'imic_home_featured_article', true);

$post =  get_post($featured_article_id);
$image_url = get_the_post_thumbnail_url($post->ID);
$permalink = get_permalink($post);
?>

<div class="container-fluid semi">
	<div class="col-lg-3">
    	<div class="latest-article-img" 
    			style="background:url('<?php echo $image_url;?>') no-repeat;background-size:contain;">
    	</div>
	</div>
	<div class="col-lg-9">
		<h1><?php echo $post->post_title;?></h1>
		<div class="latest-article-body" style="height:165px; overflow:hidden; margin-bottom:20px;">
    		<p><?php echo substr($post->post_content,0, 500) . "...";?></p>
		</div>
		<a href="<?php echo $permalink;?>"  class="btn btn-primary">READ MORE</a>
	</div>
</div>
