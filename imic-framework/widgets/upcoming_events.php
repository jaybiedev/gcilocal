<?php
/*** Widget code for Upcoming Events ***/
class upcoming_events extends WP_Widget {
	// constructor
	public function __construct() {
		 $widget_ops = array('description' => __( "Display Upcoming Events.", 'imic-framework-admin') );
         parent::__construct(false, $name = __('Upcoming Events','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	public function form($instance) {
	    // Check values
                if( $instance) {
			 $title = esc_attr($instance['title']);
			 $number = esc_attr($instance['number']);
			 $category = esc_attr($instance['category']);
		} else {
			 $title = '';
			 $number = '';
             $category='';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle_<?php echo $title; ?>" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        
        <p>
	            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of events to show', 'imic-framework-admin'); ?></label>
	            <input class="spNumber_<?php echo $number; ?>" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
       
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category', 'imic-framework-admin'); ?></label>
            <select class="spType_event_cat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
            <option value=""><?php _e('All','imic-framework-admin'); ?></option>
                <?php
                $post_terms = get_terms('event-category');
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
        
	<?php
	}
	// update widget
	public function update($new_instance, $old_instance) {
		  $instance = $old_instance;
                // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['number'] = strip_tags($new_instance['number']);
		  $instance['category'] = strip_tags($new_instance['category']);
		  return $instance;
	}
	// display widget
	public function widget($args, $instance) {
           
	   extract( $args );
           
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $number = apply_filters('widget_number', $instance['number']);
       $category = apply_filters('widget-category', empty($instance['category']) ?'': $instance['category'], $instance, $this->id_base);
	   $numberEvent = (!empty($number))? $number : 3 ;
	   $EventHeading = (!empty($post_title))? $post_title : __('Upcoming Events','imic-framework-admin') ;
	   $today = date('Y-m-d');
	   echo $args['before_widget'];
		if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo apply_filters('widget_title',$EventHeading, $instance, $this->id_base);
			echo $args['after_title'];
		}
				$event_add = imic_recur_events('future','',$category,'');
			  $nos_event = 1;
			   $google_events = getGoogleEvent();
                         if(!empty($google_events))
       $new_events = $google_events+$event_add;
	   else  $new_events = $event_add;
                        ksort($new_events);
                        if(!empty($new_events)){
                      echo '<ul>';
			  foreach($new_events as $key=>$value)
			  {     
                              if(preg_match('/^[0-9]+$/',$value)){
				  $eventTime = get_post_meta($value,'imic_event_start_tm',true);
				  if(!empty($eventTime)){
                                  $eventTime = strtotime($eventTime);
				  $eventTime = date_i18n(get_option('time_format'),$eventTime);
                                  }
                                  $date_converted=date('Y-m-d',$key );
                                  $custom_event_url= imic_query_arg($date_converted,$value);
                              $event_title=  get_the_title($value);
                                  }
                              else{
             $google_data =(explode('!',$value)); 
            $event_title=$google_data[0];
           $custom_event_url=$google_data[1];
           $eventTime='';
           if(!empty($key)){
           $eventTime = ' | ' . date(get_option('time_format'), $key);
           }
          }
                                  echo '<li class="item event-item clearfix">
							  <div class="event-date"> <span class="date">'.date_i18n('d',$key).'</span> <span class="month">'.imic_global_month_name($key).'</span> </div>
							  <div class="event-detail">
                                                       <h4><a href="'.$custom_event_url.'">'.$event_title.'</a>'.imicRecurrenceIcon($value).'</h4>';
							$stime = ''; if($eventTime!='') { $stime = ' | '.$eventTime; }
							echo '<span class="event-dayntime meta-data">'.date_i18n( 'l',$key ).$stime.'</span> </div>
							</li>';
							if (++$nos_event > $numberEvent) break; 
			  }
			echo '</ul>';
		}else{
			_e('No Upcoming Events Found','imic-framework-admin');		
		}
	   
	   echo $args['after_widget'];
	}
}
// register widget
add_action( 'widgets_init', function(){
	register_widget( 'upcoming_events' );
});
?>