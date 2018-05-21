<?php 
global $imic_options;
$post_title = wp_kses_post($instance['title']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
$the_categories = wp_kses_post($instance['categories']);
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
?>
	<div class="posts-archive blog-full-width">
           <?php 
			$post_category = imic_get_term_category(get_the_ID(),'imic_advanced_post_taxonomy','category');	
						
            global $wp_query;
            $temp_wp_query = clone $wp_query;
            query_posts(array(
                'post_type' => 'post',
				'category_name' => $the_categories,
                'paged' => get_query_var('paged'),
				'posts_per_page' => $numberPosts
            ));
            if (have_posts()) :if(!empty($instance['title'])){
				 if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
                <h3 class="widgettitle"><?php echo $post_title; ?></h3>
                <?php }
                while (have_posts()):the_post();
                    $cat = get_the_category();
                    echo '<article class="post">
              <div class="row">';
			  if($instance['show_post_meta']){
                echo '<div class="col-md-3 col-sm-3">
                  <span class="post-meta meta-data"> <span><i class="fa fa-calendar"></i>' .get_the_time(get_option('date_format')). '</span><span><i class="fa fa-archive"></i><a href ="' . get_category_link($cat[0]->term_id) . '">' . $cat[0]->name . '</a></span><span>';
                    comments_popup_link('<i class="fa fa-comment"></i>' . __('No comments yet', 'framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link', '');
                    echo'</span></span>
                  </div>';
			  }
			  if($instance['show_post_meta']){
                echo '<div class="col-md-9 col-sm-9">';
			  } else {
                echo '<div class="col-md-12">';
			  }
			  echo '
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
					if($excerpt_length!=""){
						echo '<div class="page-content">';
						echo imic_excerpt($excerpt_length);
						echo '</div>';
					}
					if($read_more_text!=""){
                    	echo'<p><a href="' . get_permalink() . '" class="btn btn-primary">' . $read_more_text . ' <i class="fa fa-long-arrow-right"></i></a></p>';
					}
					echo '
                </div>
              </div>
            </article>';
                endwhile; ?>
            <?php
            endif; // end have_posts() check  
            // -- Pagination --
            if ($wp_query->max_num_pages > 1) :
                pagination();
            endif;
            $wp_query = clone $temp_wp_query;
            ?>
        </div>