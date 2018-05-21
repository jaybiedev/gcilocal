<?php
/*
  Template Name: Sermon Albums
 */
$currentPageId = get_the_ID();
$orderby= get_post_meta(get_the_ID(),'imic_albums_select_orderby',true);
$order= get_post_meta(get_the_ID(),'imic_albums_select_order',true);
$custom_categories = get_post_meta(get_the_ID(), 'imic_sermon_categories_custom_order', true);
if($orderby != ''){
	$albumorderby = $orderby;
} else {
	$albumorderby = 'count';
}
if($order != ''){
	$albumorder = $order;
} else {
	$albumorder = 'ASC';
}
get_header();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
?>
<div class="container">
<div class="row">
<div class="<?php echo $pageOptions['class']; ?>" id="content-col">
	<?php 
        
        while(have_posts()):the_post();
        if($post->post_content!="") :
					echo '<div class="page-content">';
                              the_content();        
					echo '</div>';
                              echo '<div class="spacer-20"></div>';
                      endif;	
        endwhile;
    ?>
<div class="posts-archive">
<?php
$taxonomies = array('sermons-category');
$args = array('orderby' => $albumorderby, 'order' => $albumorder, 'hide_empty' => true);
if($albumorderby=="include")
{
	$args['include'] = $custom_categories;
}
$sermonterms = get_terms($taxonomies, $args);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count=$catValue=$paginate=1;
$perPage = get_option('posts_per_page');
if ($paged > 1) {
$paginate = ($paged - 1) * $perPage;
$paginate = $paginate + 1;
}
$TotalSermonCat = count($sermonterms);
if ($TotalSermonCat % $perPage == 0) {
$TotalPages = $TotalSermonCat / $perPage;
} else {
$TotalPages = $TotalSermonCat / $perPage;
$TotalPages = $TotalPages + 1;
}
if(!empty($sermonterms)){
foreach ($sermonterms as $sermonterms_data) {
if ($count == $paginate && $catValue <= $perPage) {
$paginate++;
$catValue++;
query_posts(array(
    'post_type' => 'sermons',
    'sermons-category' => $sermonterms_data->slug,
    'posts_per_page' => -1,
        ));
$imic_sermon_attach_full_audio_array =$imic_sermons_url_array = array();
while (have_posts()):the_post();
$imic_sermons_url = get_post_meta(get_the_ID(), 'imic_sermons_url', true);
if (!empty($imic_sermons_url)) {
array_push($imic_sermons_url_array, $imic_sermons_url);
}
$imic_sermon_attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
if (!empty($imic_sermon_attach_full_audio)) {
array_push($imic_sermon_attach_full_audio_array, $imic_sermon_attach_full_audio);
}
endwhile;
$term_link = get_term_link($sermonterms_data->slug, 'sermons-category');
$t_id = $sermonterms_data->term_id; // Get the ID of the term we're editing
$term_meta = get_option($sermonterms_data->taxonomy . $t_id . "_image_term_id"); // Do the check
echo '<div class="post">
<div class="row">
<div class="col-md-4 col-sm-4">';
echo'<a href="'.$term_link.'" class="album-cover">
<span class="album-image"><img src="'.$term_meta.'" alt=""></span>
</a>';
if(count($imic_sermons_url_array) > 0) {
echo '<div class="label label-default album-count">' . count($imic_sermons_url_array) . __(' videos', 'framework') . '</div>';
echo '&nbsp';
}
if (count($imic_sermon_attach_full_audio_array) > 0) {
    echo '<div class="label label-default album-count">' . count($imic_sermon_attach_full_audio_array) . __(' audios', 'framework') . '</div>';
}
echo'</div>
<div class="col-md-8 col-sm-8">';
// If there was an error, continue to the next term.
if (is_wp_error($term_link)) {
    continue;
} else {
echo '<h3><a href="' . $term_link . '">' . $sermonterms_data->name . '</a></h3>';
echo term_description($sermonterms_data->term_id, 'sermons-category');
echo'<p><a href="' . $term_link . '" class="btn btn-primary">' . __('Play ', 'framework') . '<i class="fa fa-play"></i></a></p>';
}
echo'</div>
</div>
</div>';
}
$count++; $total_pages = floor($TotalPages);
} if($total_pages>1) {
pagination($TotalPages, $perPage); }
}else{
echo '<article class="post">';
if (current_user_can('edit_posts')) :
?>
<h3><?php _e('No posts to display', 'framework'); ?></h3>
<p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'framework'), admin_url('post-new.php?post_type=sermons')); ?></p>
<?php else : ?>
<h3><?php _e('Nothing Found', 'framework'); ?></h3>
<p><?php printf(__('Apologies, but no results were found. Perhaps searching will help find a related post..', 'framework')); ?></p>
<?php
echo '</article>';
endif;
}
?>
</div>
</div>
<?php if(!empty($pageOptions['sidebar'])){ ?>
<!-- Start Sidebar -->
<div class="col-md-3 sidebar" id="sidebar-col">
    <?php dynamic_sidebar($pageOptions['sidebar']); ?>
</div>
<!-- End Sidebar -->
<?php } ?>
</div>
</div>
<?php get_footer(); ?>