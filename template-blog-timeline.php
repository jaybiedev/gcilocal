<?php
/*
Template Name: Blog Timeline
*/
get_header();
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$column_class = 9;  
}else{
$column_class = 12;  
}
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
?>
<div class="container">
        <div class="row">
        	<div class="col-md-<?php echo $column_class ?>" id="content-col">
    <?php 
            if(!is_home()){
           while(have_posts()):the_post();
			if($post->post_content!="") :
							echo '<div class="page-content">';
                              the_content();
							  echo '</div>';        
                              echo '<div class="spacer-20"></div>';
                      endif;	
			endwhile; 
            }
			
			/*$post_category = get_post_meta(get_the_ID(),'imic_advanced_post_taxonomy',true);
			if(!empty($post_category)){
			$post_categories= get_category($post_category);
			$post_category= $post_categories->slug; }*/
						
			$post_category = imic_get_term_category(get_the_ID(),'imic_advanced_post_taxonomy','category');	
            global $wp_query;
    query_posts(array(
        'post_type' => 'post',
		'category_name' => $post_category,
        'paged' => get_query_var('paged')
    ));
	$month_tag = '';
    if (have_posts()) :
        echo '<ul class="timeline">';
         $i=1;
        while (have_posts()):the_post();
		if($month_tag!=get_the_time('M')) { $month_check=1; }
		if($month_check==1) {
		$month_tag = get_the_time('M'); } if($month_check==1) { $tag = '<div class="timeline-badge">'.  __(get_the_time('M'),'framework').'<span>'.get_the_time('Y').'</span></div>'; } else { $tag = ''; } $month_check++;
         if($i%2==0){
             $class =" class='timeline-inverted'";
         }
         else{
             $class ='';
         }
            echo '<li'.$class.'>';
            echo $tag;
            echo'<div class="timeline-panel">
                <div class="timeline-heading">
                  <h3 class="timeline-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                  <p><small class="text-muted"><i class="fa fa-calendar"></i> '.get_the_time(get_option('date_format')).'</small></p>
                </div>
                <div class="timeline-body">';
					echo '<div class="page-content">';
                   echo imic_excerpt(50);
				   echo '</div>';
                echo'</div>
              </div>
            </li>';
                $i++;
        endwhile;
        echo '</ul>';
     else:
        echo '<ul><li>';
        if (current_user_can('edit_posts')) :
            ?>
            <h3><?php _e('No posts to display', 'framework'); ?></h3>
            <p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'framework'), admin_url('post-new.php')); ?></p>
    <?php else : ?>
            <h3><?php _e('Nothing Found', 'framework'); ?></h3>
            <p><?php printf(__('Apologies, but no results were found. Perhaps searching will help find a related post..', 'framework')); ?></p>
            <?php
            echo '</li></ul>';
        endif;
        ?>
    <?php
    endif; // end have_posts() check  
    // -- Pagination --
    if ($wp_query->max_num_pages > 1) :
        ?>
        <ul class="pager pull-right">
            <li><?php next_posts_link(__('&larr;Older', 'framework')); ?></li>
            <li><?php previous_posts_link(__(' Newer &rarr;', 'framework')); ?></li>
        </ul>
        <?php
    endif;
    ?>
</div>
            <?php if(is_active_sidebar($pageSidebar)) { ?>
    <!-- Start Sidebar -->
   	<div class="col-md-3 sidebar" id="sidebar-col">
    	<?php dynamic_sidebar($pageOptions['sidebar']); ?>
   	</div>
	<!-- End Sidebar -->
 <?php }
 echo '</div></div>';
get_footer(); ?>