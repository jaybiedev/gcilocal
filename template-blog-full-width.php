<?php
/*
  Template Name: Blog Full Width
 */
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 posts-archive blog-full-width">
           <?php 
            if(!is_home()){
            while(have_posts()):the_post();
            	if($post->post_content!="") :
                              the_content();        
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
            $temp_wp_query = clone $wp_query;
            query_posts(array(
                'post_type' => 'post',
				'category_name' => $post_category,
                'paged' => get_query_var('paged')
            ));
            if (have_posts()) :
                while (have_posts()):the_post();
                    $cat = get_the_category();
                    echo '<article class="post">
              <div class="row">
                <div class="col-md-3 col-sm-3">
                  <span class="post-meta meta-data"> <span><i class="fa fa-calendar"></i>' .get_the_time(get_option('date_format')). '</span><span><i class="fa fa-archive"></i><a href ="' . get_category_link($cat[0]->term_id) . '">' . $cat[0]->name . '</a></span><span>';
                    comments_popup_link('<i class="fa fa-comment"></i>' . __('No comments yet', 'framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link', '');
                    echo'</span></span>
                  </div>
                <div class="col-md-9 col-sm-9">
                  <h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                    switch (get_post_format()) {
                        case 'video':
                            $imic_gallery_video_url = get_post_meta(get_the_ID(), 'imic_gallery_video_url', true);
                            if (!empty($imic_gallery_video_url)) {
                                echo '<div class="fw-video">'.imic_video_embed($imic_gallery_video_url).'</div>';
                            }
                            break;
                        case 'audio':
                            $imic_gallery_audio_display = get_post_meta(get_the_ID(), 'imic_gallery_audio_display', true);
                            if ($imic_gallery_audio_display == 1) {
                                $imic_gallery_audio = get_post_meta(get_the_ID(), 'imic_gallery_audio', true);
                                if (!empty($imic_gallery_audio)) {
                                    echo $imic_gallery_audio;
                                }
                            } else {
                                $imic_gallery_uploaded_audio = get_post_meta(get_the_ID(), 'imic_gallery_uploaded_audio', true);
                                if (!empty($imic_gallery_uploaded_audio)) {
                                    $attach_full_audio = wp_get_attachment_url($imic_gallery_uploaded_audio);
                                    if (!empty($attach_full_audio)) {
                                        ?>
                                        <div class="audio-container">
                                            <audio class="audio-player" src="<?php echo $attach_full_audio; ?>" type="audio/mp3" controls></audio>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            break;
                        default:
                            if (has_post_thumbnail()) {
                                echo '<a href="' . get_permalink() . '">' .
                                get_the_post_thumbnail(get_the_ID(), '600x400', array('class' => "img-thumbnail")) .
                                '</a>';
                            }
                            break;
                    }
					echo '<div class="page-content">';
                    echo imic_excerpt(50);
					echo '</div>';
                    echo'<p><a href="' . get_permalink() . '" class="btn btn-primary">' . __('Continue reading', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>
                </div>
              </div>
            </article>';
                endwhile;
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
                    echo ' </article>';
                endif;
                ?>
            <?php
            endif; // end have_posts() check  
            // -- Pagination --
            if ($wp_query->max_num_pages > 1) :
                pagination();
            endif;
            $wp_query = clone $temp_wp_query;
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>