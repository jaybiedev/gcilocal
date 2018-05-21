<?php
$the_categories = wp_kses_post($instance['categories']);
$post_title = wp_kses_post($instance['title']);
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$grid_column = (!empty($instance['grid_column']))? $instance['grid_column'] : 4 ;
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 3 ;
$order = wp_kses_post($instance['orderby']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
if ($order == "no") {
	$orderby = "ID";
	$sort_order = "DESC";
} elseif ($order == "yes") {
	$orderby = "menu_order";
	$sort_order = "ASC";
}
?>
<?php query_posts( array ( 'post_type' => 'staff', 'staff-category' => ''. $the_categories .'', 'posts_per_page' => $numberPosts, 'orderby' => $orderby, 'order' => $sort_order ) );
if(have_posts()):
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } 
?>
<div class="row">
<?php while(have_posts()) : the_post();
$custom = get_post_custom(get_the_ID());
echo '<div class="col-md-' . $grid_column . ' col-sm-6">
	<div class="grid-item staff-item"> 
    	<div class="grid-item-inner format-standard">';
			if (has_post_thumbnail()):
                echo '<a href="' . get_permalink(get_the_ID()) . '" class="media-box">
                	'.get_the_post_thumbnail(get_the_ID(), 'full').'';
               	echo '</a>';
            endif;
			echo '<div class="grid-content">
              	<h3> <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h3>';
				if($instance['show_post_meta']){
            	$job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
				if (!empty($job_title)) {
					echo '<div class="meta-data">' . $job_title . '</div>';
				}
				echo imic_social_staff_icon();
			}
			if($excerpt_length!=""){
				echo '<div class="page-content">';
					echo imic_excerpt($excerpt_length);
				echo '</div>';
			}
			if($read_more_text!=""){
				echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . $read_more_text . '</a></p>';
			}
			echo '</div></div></div></div>';
?>
<?php endwhile; ?>
</div>
<?php endif; wp_reset_query(); ?>