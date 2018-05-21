<?php
/*** Widget code for Selected Post ***/
class selected_post extends WP_Widget {
	// constructor
	public function __construct() {
		 $widget_ops = array('description' => __( 'Display latest and selected post of different post type.','imic-framework-admin') );
         parent::__construct(false, $name = __('Selected Post','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	public function form($instance) {
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $type = esc_attr($instance['type']);
			 $number = esc_attr($instance['number']);
			 $category     = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
		} else {
			 $title = '';
			 $type = '';
			 $number = '';
			  $category  = '';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
       <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Select Post Type', 'imic-framework-admin'); ?></label>
            <select class="spType dynamic_cpt" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
            <option value="post"><?php _e('Post','imic-framework-admin'); ?></option>
                <?php
           $post_types = get_post_types( array('_builtin' => false,'public'=> true), 'names' ); 
               if(($key = array_search('post', $post_types)) !== false){
				unset($post_types[$key]);
				}
             
                if(!empty($post_types)){
                    foreach ( $post_types as $post_type ) {
                        $activePost = ($type == $post_type)? 'selected' : '';
                       echo '<option value="'. $post_type .'" '.$activePost.'>' . $post_type . '</p>';
                    }
                }else{
                    echo '<option value="no">'.__('No Post Type Found.','imic-framework-admin').'</option>';
                }
                ?>
            </select> 
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Post Category', 'imic-framework-admin'); ?></label>
        <input type="hidden" id="selected_cat" value="<?php echo $category; ?>">
            <select class="post_cat dynamic_cat" id="<?php echo $this->get_field_id('event'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
                <option value=""><?php _e('Select Post Category','imic-framework-admin'); ?></option>
                <?php
								switch($type)
								{
									case 'product':
									$cat = 'product_cat';
									break;
									case 'causes':
									$cat = 'causes-category';
									break;
									case 'gallery':
									$cat = 'gallery-category';
									break;
									case 'staff':
									$cat = 'staff-category';
									break;
									case 'sermons':
									$cat = 'sermons-category';
									break;
									case 'event':
									$cat = 'event-category';
									break;
									default:
									$cat = 'category';
								}
                 $post_cats = get_terms($cat);
                if(!empty($post_cats)){
                      foreach ($post_cats as $post_cat) {                        
                        $name = $post_cat->name;
                        $id = $post_cat->term_id;
                        $activePost = ($id == $category)? 'selected' : '';
                        echo '<option value="'. $id .'" '.$activePost.'>' . $name . '</option>';
                    }
                }
                ?>
            </select>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show', 'imic-framework-admin'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p> 
	<?php
	}
	// update widget
	public function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['type'] = strip_tags($new_instance['type']);
		  $instance['number'] = strip_tags($new_instance['number']);
		  $instance['category'] = (int) $new_instance['category'];
		  
		 return $instance;
	}
	// display widget
	public function widget($args, $instance) {
             global $wp_query;
            $temp_wp_query = clone $wp_query;
	   extract( $args );
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $type = apply_filters('widget_type', $instance['type']);
	   $number = apply_filters('widget_number', $instance['number']);
	   $category = ( ! empty( $instance['category'] ) ) ? $instance['category']  : '';
	   
	   $numberPost = (!empty($number))? $number : 3 ;	
	   	   
	   echo $args['before_widget'];
		
		if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
		}
		switch($type)
		{
			case 'product':
			$cat = 'product_cat';
			break;
			case 'causes':
			$cat = 'causes-category';
			break;
			case 'gallery':
			$cat = 'gallery-category';
			break;
			case 'staff':
			$cat = 'staff-category';
			break;
			case 'sermons':
			$cat = 'sermons-category';
			break;
			case 'event':
			$cat = 'event-category';
			break;
			default:
			$cat = 'category';
		}
		
		$post_args = array('post_type'=>$type, 'posts_per_page'=>$number, 'paged' => get_query_var('paged'));
		if($category!='')
		{
			$post_args['tax_query'] = [
				[
					'taxonomy' => $cat,
					'terms' => $category,
					'field' => 'term_id',
					'operator'=>'IN'
				]
			];
		}
		$posts = new WP_Query($post_args);
		echo '<ul>';
		if($posts->have_posts()):while($posts->have_posts()):$posts->the_post();
			$id = get_the_ID();
			if($type!='event'){
			 	$postDate =  esc_html(get_the_date(get_option('date_format'), $id));
			}else{
				$event_post = get_post_custom($id);
				$postDateRaw = strtotime($event_post['imic_event_start_dt'][0]);
				$postDate = date(get_option('date_format'),$postDateRaw);
			}
			$postImage = get_the_post_thumbnail( $id, 'full', array('class' => "img-thumbnail") );
				echo '<li class="clearfix">';
							if ( !empty($postImage)) :
						  		echo '<a href="'.get_permalink($id).'" class="media-box post-image">';
						  		echo $postImage;
							echo '</a>';
						 	endif;
						echo '<div class="widget-blog-content"><a href="'.get_permalink($id).'">'.get_the_title($id).'</a>
						<span class="meta-data"><i class="fa fa-calendar"></i>'.__(' on ', 'imic-framework-admin') . $postDate .'</span>
						</div>
					</li>';					
					
		endwhile;
		echo '</ul>';
		else:
			echo __('No ','imic-framework-admin').$type.__(' Found','imic-framework-admin');		
		endif;
   
	   echo $args['after_widget'];
	}
}
// register widget
add_action( 'widgets_init', function(){
	register_widget( 'selected_post' );
});
?>