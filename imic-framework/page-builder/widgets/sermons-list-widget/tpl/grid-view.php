<?php
$the_categories = wp_kses_post($instance['categories']);
$post_title = wp_kses_post($instance['title']);
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
?>
<?php query_posts( array ( 'post_type' => 'sermons', 'sermons-category' => ''. $the_categories .'', 'posts_per_page' => $numberPosts ) );
if(have_posts()):
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } ?>
<ul class="isotope-grid row">
<?php while(have_posts()) : the_post();
$custom = get_post_custom(get_the_ID());
$attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
$attach_pdf= imic_sermon_attach_full_pdf(get_the_ID());
echo '<li class="grid-item col-md-'.$layout_type['column'].' col-sm-6 sermon-post format-standard">';
echo '<div class="grid-item-inner">';

	echo'<a href="' . get_permalink() . '" class="media-box">';
	the_post_thumbnail('full');
	echo'</a>';

echo '<div class="grid-content">';
echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
if($instance['show_post_meta']){
echo'<span class="meta-data"><i class="fa fa-calendar"></i>' . __('Posted on ', 'framework') . get_the_time(get_option('date_format'));
echo get_the_term_list(get_the_ID(), 'sermons-speakers', ' | Pastor: ', ', ', '' );
echo'</span>';
}
echo'<div class="sermon-actions">';
if (!empty($custom['imic_sermons_url'][0])) {
	echo '<a href="' . get_permalink() . '#playvideo" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Video', 'framework') .'" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
}

 $attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
if (!empty($attach_full_audio)) {
	echo'<a href="' . get_permalink() . '#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Audio', 'framework') .'" rel="tooltip"><i class="fa fa-headphones"></i></a>';
}
echo '<a href="' . get_permalink() . '#read" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Read online', 'framework') .'" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
$attach_pdf= imic_sermon_attach_full_pdf(get_the_ID());
if (!empty($attach_pdf)) {
   
   echo '<a href="' . IMIC_THEME_PATH . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
}
echo '</div>';
if($excerpt_length!=""){
	echo '<div class="spacer-10"></div>';
	echo '<div class="page-content">';
	echo imic_excerpt($excerpt_length);
	echo '</div>';
}
if($read_more_text!=""){
	echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . $read_more_text . ' <i class="fa fa-long-arrow-right"></i></a></p>';
}
echo '</div>';
echo ' </div>';
echo '</li>';
 ?>
<?php endwhile; wp_reset_query(); ?>
</ul>
<?php endif; ?>