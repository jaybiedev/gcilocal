<?php 
/*
  Template Name: Blog Masonry
 */
get_header();
global $imic_options;
if(is_home()) { $id = get_option('page_for_posts'); }
else { $id = get_the_ID(); }
$thumbnails_option = get_post_meta($id,'imic_blog_masonry_thumbnails',true);
$pageSidebar = get_post_meta($id,'imic_select_sidebar_from_list', true);
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
            if (have_posts()) : ?>
                <ul class="grid-holder col-3 events-grid">
                    <?php 
                    while (have_posts()):the_post();
                        $custom_post = get_post_custom(get_the_ID());
                        if($thumbnails_option==1) {
							echo '<li class="grid-item post format-standard">';
						} else {
							echo '<li class="grid-item post format-image">';
						}
                        echo '<div class="grid-item-inner">';
						
						if($thumbnails_option==1) {
								echo'<a href="' . get_permalink() . '" class="media-box">';
								the_post_thumbnail('full');
								echo'</a>';
						} else {
							if (has_post_thumbnail()):
								$src = wp_get_attachment_image_src($custom_post['_thumbnail_id'][0], 'full');
								if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
									$Lightbox_init = '<a href="'. $src[0] .'" data-rel="prettyPhoto" class="media-box">';
								}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
									$Lightbox_init = '<a href="'. $src[0] .'" title="'.get_the_title().'" class="media-box magnific-image">';
								}
								echo $Lightbox_init;
								the_post_thumbnail('full');
								echo'</a>';
							endif;
						}
                        echo '<div class="grid-content">';
                        echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                        $cats = get_the_category();
                        echo '<span class="meta-data"><span><i class="fa fa-calendar"></i>' . get_the_time(get_option('date_format')) . '</span><span><a href="'.get_category_link($cats[0]->term_id).'"><i class="fa fa-tag"></i>'.$cats[0]->name.'</a></span></span>';
						echo '<div class="page-content">';
                        echo imic_excerpt(50);
						echo '</div>';
                        echo '</div>';
                        echo ' </div>';
                        echo '</li>';
                        ?>
                        <?php
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
                    <li><?php next_posts_link(__('&larr;Older','framework')); ?></li>
                    <li><?php previous_posts_link(__(' Newer &rarr;','framework')); ?></li>
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