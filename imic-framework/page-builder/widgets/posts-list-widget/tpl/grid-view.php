<?php
$the_categories = wp_kses_post($instance['categories']);
$post_title = wp_kses_post($instance['title']);
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
?>
<?php query_posts( array ( 'post_type' => 'post', 'category_name' => ''. $the_categories .'', 'posts_per_page' => $numberPosts ) );
if(have_posts()):
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } ?>
<ul class="isotope-grid row">
<?php while(have_posts()) : the_post();
echo '<li class="grid-item col-md-'.$layout_type['column'].' col-sm-6 post format-standard">';
echo '<div class="grid-item-inner">';

	echo'<a href="' . get_permalink() . '" class="media-box">';
	the_post_thumbnail('full');
	echo'</a>';

echo '<div class="grid-content">';
echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
$cats = get_the_category();
if($instance['show_post_meta']){
echo '<span class="meta-data"><span><i class="fa fa-calendar"></i>' . get_the_time(get_option('date_format')) . '</span><span><a href="'.get_category_link($cats[0]->term_id).'"><i class="fa fa-tag"></i>'.$cats[0]->name.'</a></span>';
if ( comments_open() ) { echo comments_popup_link('<i class="fa fa-comment"></i>'.__('No comments yet','framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link',''); }
echo '</span>';
}
if($excerpt_length!=""){
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