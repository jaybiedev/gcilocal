<?php
/*** Widget code for Recent Sermons ***/
class recent_sermons extends WP_Widget {
	// constructor
	public function __construct() {
		 $widget_ops = array('description' => __( 'Display recent sermons.', 'imic-framework-admin') );
         parent::__construct(false, $name = __('Recent Sermons','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	public function form($instance) {
	     $title = $number = $autoplay = $category = '';
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $number = esc_attr($instance['number']);
			 $category = esc_attr($instance['category']);
			 $autoplay = isset($instance['autoplay'])?$instance['autoplay']:0;
		} 
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of sermons to show', 'imic-framework-admin'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p> 
        
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category', 'imic-framework-admin'); ?></label>
            <select class="spType_sermons_cat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
            <option value=""><?php _e('All','imic-framework-admin'); ?></option>
                <?php
                $post_terms = get_terms('sermons-category');
                if(!empty($post_terms)){
                      foreach ($post_terms as $term) {
                         
                        $term_name = $term->name;
                        $term_id = $term->slug;
                        $activePost = ($term_id == $category)? 'selected' : '';
                        echo '<option value="'. $term_id .'" '.$activePost.'>' . $term_name . '</p>';
                    }
                }
                ?>
            </select> 
        </p> 
        
         <p>
            <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay sermons video', 'imic-framework-admin'); ?></label>
            
            <select class="spType_event_cat" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>">
            <option value="0" selected  <?php echo ($autoplay=='0')?"selected":''; ?>><?php _e('No','imic-framework-admin'); ?></option>
            <option value="1"  <?php echo ($autoplay=='1')?"selected":''; ?>><?php _e('Yes','imic-framework-admin'); ?></option>
            </select> 
        </p> 
	<?php
	}
	// update widget
	public function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['number'] = strip_tags($new_instance['number']);
		  $instance['category'] = strip_tags($new_instance['category']);
		  $instance['autoplay'] =isset($new_instance['autoplay'])?$new_instance['autoplay']:0;
		  
		 return $instance;
	}
	// display widget
	public function widget($args, $instance) {
            global $wp_query;
            $temp_wp_query = clone $wp_query;
	   extract( $args );
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $number = apply_filters('widget_number', $instance['number']);
       $category = apply_filters('widget-category', empty($instance['category']) ?'': $instance['category'], $instance, $this->id_base);
	   $autoplay = isset($instance['autoplay'])?$instance['autoplay']:0;
	   
	   $numberPost = (!empty($number))? $number : 3 ;	
	   	   
	   echo $args['before_widget'];
	   echo '<div class="listing sermons-listing">';
		if( !empty($instance['title']) ){
			echo '<header class="listing-header">';
			echo $args['before_title'];
			echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
			echo '</header>';
		}
$posts = query_posts(array('order'=>'DESC', 'post_type' => 'sermons', 'sermons-category' => $category, 'posts_per_page' => $numberPost, 'post_status' => 'publish'));
		if(!empty($posts)){ 
			echo '<section class="listing-cont">
					<ul>';
			 $liFirst = $liOthers = '';		
			 $flag = 0;
			 foreach($posts as $post){ 
			 /* check if content has sortcode video */
			if (has_shortcode($post->post_content, 'fullscreenvideo' ) )
			 {
				apply_filters( 'the_content', $post->post_content );
                $post->post_content = do_shortcode( $post->post_content );
			 }
			 else 
			 {
				  $post->post_content = wp_trim_words($post->post_content,30);
			 }
			 /* end check if content has sortcode video */

			 	$custom = get_post_custom($post->ID);
				if(!empty($custom['imic_sermons_url'][0]) && $flag==0){

                               $liFirst .='<li class="item sermon featured-sermon">
                                    <span class="date">'.get_the_time(get_option('date_format'),$post->ID).'</span>
                                    <h4><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h4>
                                    <div class="sermon-actions">';
									if (!empty($custom['imic_sermons_url'][0])) {
											$liFirst .= '<a href="' . get_permalink($post->ID) . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Video', 'imic-framework-admin') . '"><i class="fa fa-video-camera"></i></a>';
									}
									 $attach_full_audio= imic_sermon_attach_full_audio($post->ID);
								         if (!empty($attach_full_audio)) {
										$liFirst .= '<a href="' . get_permalink($post->ID) . '#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Audio', 'imic-framework-admin') . '"><i class="fa fa-headphones"></i></a>';
									}
									if (!empty($attach_full_audio)) {
										$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_full_audio . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download Audio', 'imic-framework-admin') . '"><i class="fa fa-download"></i></a>';
									}
									 $attach_pdf= imic_sermon_attach_full_pdf($post->ID);
									if (!empty($attach_pdf)) {
									$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'imic-framework-admin') . '"><i class="fa fa-book"></i></a>';
									}
                                       
                            $liFirst .= '</div>
                                </li>';
								
				}
				else if (!empty($custom['imic_sermons_video_mp4'][0]) && $flag==0) {

				               $liFirst .='<li class="item sermon featured-sermon">
								<span class="date">'.get_the_time(get_option('date_format'),$post->ID).'</span>
								<h4><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h4><div class="featured-sermon-video">';
								$poster='';
								if(isset($custom['imic_sermons_video_poster'][0]) && !empty($custom['imic_sermons_video_poster'][0]))
								$poster = $custom['imic_sermons_video_poster'][0];
								$liFirst .='<div class="video-container">';
								$liFirst .='<video width="320" height="240" poster="'.$poster.'" controls preload="none" class="custom-video">';
								if(isset($custom['imic_sermons_video_mp4'][0]) && !empty($custom['imic_sermons_video_mp4'][0]))
								$liFirst .='<source type="video/mp4" src="'.$custom['imic_sermons_video_mp4'][0].'" />';
								if(isset($custom['imic_sermons_video_webm'][0]) && !empty($custom['imic_sermons_video_webm'][0]))
								$liFirst .='<source type="video/webm" src="'.$custom['imic_sermons_video_webm'][0].'" />';
								if(isset($custom['imic_sermons_video_ogv'][0]) && !empty($custom['imic_sermons_video_ogv'][0]))
								$liFirst .='<source type="video/ogg" src="'.$custom['imic_sermons_video_ogv'][0].'" />';
								$liFirst .='<object width="320" height="240" type="application/x-shockwave-flash" data="flashmediaelement.swf">';
                                $liFirst .='<param name="movie" value="'.get_template_directory_uri().'/plugins/mediaelementflashmediaelement.swf" />';
								if(isset($custom['imic_sermons_video_poster'][0]) &&!empty($custom['imic_sermons_video_mp4'][0]))
							    $liFirst .='<param name="flashvars" value="controls=true&file='.$custom['imic_sermons_video_mp4'][0].'" />';
								$liFirst .='</object>';
								$liFirst .='</video>';
							    $liFirst .='</div>'.'</div><p>'. $post->post_content.'</p><div class="sermon-actions">';
								if (isset($custom['imic_sermons_url'][0]) && !empty($custom['imic_sermons_url'][0])) {
										$liFirst .= '<a href="' . get_permalink($post->ID) . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Video', 'imic-framework-admin') . '"><i class="fa fa-video-camera"></i></a>';
								}
								 $attach_full_audio= imic_sermon_attach_full_audio($post->ID);
									 if (!($attach_full_audio)) {
									$liFirst .= '<a href="' . get_permalink($post->ID) . '#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Audio', 'imic-framework-admin') . '"><i class="fa fa-headphones"></i></a>';
								}
								if (!empty($attach_full_audio)) {
									$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_full_audio . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download Audio', 'imic-framework-admin') . '"><i class="fa fa-download"></i></a>';
								}
								 $attach_pdf= imic_sermon_attach_full_pdf($post->ID);
								if (!empty($attach_pdf)) {
								$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'imic-framework-admin') . '"><i class="fa fa-book"></i></a>';
								}
										   
								$liFirst .= '</div>
									</li>';		
				}
				else if((has_post_thumbnail($post->ID))&&($flag==0)){
					//get the featured image
					$featured_image_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
					//end
				              $liFirst .='<li class="item sermon featured-sermon">
								<span class="date">'.get_the_time(get_option('date_format'),$post->ID).'</span>
								<h4><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h4>
								<div class="featured-sermon-video format-standard">' .'<a href="'.get_permalink($post->ID).'" class="media-box"><img src="'.$featured_image_url.'" />' . '</a></div><p>'.
								  $post->post_content
								.'</p><div class="sermon-actions">';
								if (!empty($custom['imic_sermons_url'][0])) {
										$liFirst .= '<a href="' . get_permalink($post->ID) . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Video', 'imic-framework-admin') . '"><i class="fa fa-video-camera"></i></a>';
								}
								 $attach_full_audio= imic_sermon_attach_full_audio($post->ID);
									 if (!empty($attach_full_audio)) {
									$liFirst .= '<a href="' . get_permalink($post->ID) . '#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Audio', 'imic-framework-admin') . '"><i class="fa fa-headphones"></i></a>';
								}
								if (!empty($attach_full_audio)) {
									$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_full_audio . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download Audio', 'imic-framework-admin') . '"><i class="fa fa-download"></i></a>';
								}
								 $attach_pdf= imic_sermon_attach_full_pdf($post->ID);
								if (!empty($attach_pdf)) {
								$liFirst .= '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'imic-framework-admin') . '"><i class="fa fa-book"></i></a>';
								}		   
								$liFirst .= '</div>
									</li>';			
					   } 
				else{
					$liOthers .= '<li class="item sermon">
                                    <h2 class="sermon-title"><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h2>
                                    <span class="meta-data"><i class="fa fa-calendar"></i>'.__(' on ','imic-framework-admin').get_the_time(get_option('date_format'),$post->ID).'</span>
                                </li>';
				}
                                $flag++;
			 }
			echo $liFirst.$liOthers; 
                        
			echo '</ul></section>';
		}else{
		   _e('No Sermon Found','imic-framework-admin');
		}
             echo '</div>';
	   echo $args['after_widget'];
           $wp_query = clone $temp_wp_query;
           
	}
}
// register widget
add_action( 'widgets_init', function(){
	register_widget( 'recent_sermons' );
});
?>