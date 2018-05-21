<?php
$orderby = (!empty($instance['orderby']))? $instance['orderby'] : 'count' ;
$sortby = (!empty($instance['sortby']))? $instance['sortby'] : 'ASC' ;
$post_title = wp_kses_post($instance['title']);
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
?>
<div class="posts-archive"> 
<?php if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } ?>
<?php $taxonomies = array('sermons-category');
$argts = array('orderby' => $orderby, 'order' => $sortby, 'hide_empty' => true);
$sermonterms = get_terms($taxonomies, $argts);

if(!empty($sermonterms)){
foreach ($sermonterms as $sermonterms_data) {

query_posts(array(
    'post_type' => 'sermons',
    'sermons-category' => $sermonterms_data->slug,
    'posts_per_page' => $numberPosts,
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
endwhile; wp_reset_query();
$term_link = get_term_link($sermonterms_data->slug, 'sermons-category');
$t_id = $sermonterms_data->term_id; // Get the ID of the term we're editing
$term_meta = get_option($sermonterms_data->taxonomy . $t_id . "_image_term_id"); // Do the check
echo '<div class="post">
<div class="row">
<div class="col-md-4 col-sm-4">';
echo'<a href="'.$term_link.'" class="album-cover">
<span class="album-image"><img src="'.$term_meta.'" alt=""></span>
</a>';
if($instance['show_post_meta']){
if(count($imic_sermons_url_array) > 0) {
echo '<div class="label label-default album-count">' . count($imic_sermons_url_array) . __(' videos', 'framework') . '</div>';
echo '&nbsp';
}
if (count($imic_sermon_attach_full_audio_array) > 0) {
    echo '<div class="label label-default album-count">' . count($imic_sermon_attach_full_audio_array) . __(' audios', 'framework') . '</div>';
}
}
echo'</div>
<div class="col-md-8 col-sm-8">';
// If there was an error, continue to the next term.
if (is_wp_error($term_link)) {
    continue;
} else {
echo '<h3><a href="' . $term_link . '">' . $sermonterms_data->name . '</a></h3>';
if($excerpt_length!=""){
echo term_description($sermonterms_data->term_id, 'sermons-category');
}
if($read_more_text!=""){
echo'<p><a href="' . $term_link . '" class="btn btn-primary">' . $read_more_text . ' <i class="fa fa-play"></i></a></p>';
}
}
echo'</div>
</div>
</div>';
}
} ?>
</div>