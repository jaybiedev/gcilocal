<?php
/*
Template Name: Blog Medium Thumbnails
*/
get_header(); 
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module(); ?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?> posts-archive" id="content-col">
            <?php 
            if(!is_home()){
            while(have_posts()):the_post();
            	if($post->post_content!="") :
                              the_content();        
                              echo '<div class="spacer-20"></div>';
                      endif;	
                endwhile; 
            }
			$post_category = get_post_meta(get_the_ID(),'imic_advanced_post_taxonomy',true);
						$post_new_category = explode(',', $post_category);
						if(!empty($post_new_category)){
							foreach($post_new_category as $category)
							{
								$post_categories = get_category($category);
								$post_category_make[] = $post_categories->slug; 
							}
						}
            query_posts(array(
                'post_type' => 'post',
								'tax_query' => array(array(
												'taxonomy' => 'category',
												'field' => 'slug',
												'terms' => $post_category_make,
												'operator' => 'IN')),
                'paged' => get_query_var('paged')
            ));
            if (have_posts()) :
                while (have_posts()):the_post();
                    $cat = get_the_category();
				if( '' != get_the_post_thumbnail() ) {
                                $class = "col-md-8 col-sm-8";
                                } else {
                                $class = "col-md-12 col-sm-12";
                                }
                          echo'<article class="post">
                            <div class="row">';
                    if (has_post_thumbnail()):
                        echo '<div class="col-md-4 col-sm-4">
                    	<a href="' . get_permalink() . '">';
                        the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
                        echo'</a></div>';
                    endif;
                    echo '<div class="'.$class.'">';
                    echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                   echo '<span class="post-meta meta-data">
                    		<span><i class="fa fa-calendar"></i>' . get_the_time(get_option('date_format')) . '</span><span><i class="fa fa-user"></i>'. __(' Posted By: ','framework'). get_the_author_meta('display_name').'</span><span><i class="fa fa-archive"></i>'.imic_custom_taxonomies_terms_links().'</span> <span>';
                    if ( comments_open() ) { echo comments_popup_link('<i class="fa fa-comment"></i>'.__('No comments yet','framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link',''); }
                    echo'</span></span>';
                    echo imic_excerpt(50);
                    echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . __('Continue reading', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>';
                    echo '</div></div>';
                    echo '</article>';
                endwhile;
				wp_reset_postdata();
            else:
                echo '<article class="post">';
                if (current_user_can('edit_posts')) :
                    ?>
                    <h3><?php _e('No posts to display', 'framework'); ?></h3>
                    <p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'framework'), admin_url('post-new.php')); ?></p>
                <?php else : ?>
                    <h3><?php _e('Nothing Found', 'framework'); ?></h3>
                    <p><?php printf(__('Apologies, but no results were found. Perhaps searching will help find a related post..', 'framework')); ?></p>
                    <?php
                    echo '</article>';
                endif;
                ?>
            <?php
            endif; // end have_posts() 
            pagination();
            ?>
        </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar('post-sidebar'); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>