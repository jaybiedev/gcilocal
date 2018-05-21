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
if(have_posts()) : 
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } ?>
<div class="posts-archive">
<?php while(have_posts()) : the_post(); ?>
<article class="post">
  <div class="row">
<?php if( '' != get_the_post_thumbnail() ) {
	  $class = "col-md-8 col-sm-8";
	  } else {
	  $class = "col-md-12 col-sm-12";
}
if (has_post_thumbnail()):
	  echo '<div class="col-md-4 col-sm-4">
	  <a href="' . get_permalink() . '">';
	  the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
	  echo'</a></div>';
  endif;
  echo '<div class="'.$class.'">';
  echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>'; ?>
  <?php if($instance['show_post_meta']){
   echo '<span class="post-meta meta-data">
			<span><i class="fa fa-calendar"></i>' . get_the_time(get_option('date_format')) . '</span><span><i class="fa fa-user"></i>'. __(' Posted By: ','framework'). get_the_author_meta('display_name').'</span><span><i class="fa fa-archive"></i>'.imic_custom_taxonomies_terms_links().'</span> <span>';
	if ( comments_open() ) { echo comments_popup_link('<i class="fa fa-comment"></i>'.__('No comments yet','framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link',''); }
	echo'</span></span>'; }
	if($excerpt_length!=""){
	echo '<div class="page-content">';
	echo imic_excerpt($excerpt_length);
	echo '</div>';
	}
	if($read_more_text!=""){
	echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . $read_more_text . ' <i class="fa fa-long-arrow-right"></i></a></p>';
	}
	echo '</div></div>';
	echo '</article>';
 ?>
<?php endwhile; wp_reset_query(); ?>
</div>
<?php endif; ?>