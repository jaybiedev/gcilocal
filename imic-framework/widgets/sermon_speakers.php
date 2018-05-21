<?php
/*** Widget code for Sermon Speakers ***/
class sermon_speakers extends WP_Widget {
	// constructor
	public function __construct() {
		 $widget_ops = array('description' => __( "Display Sermon Speakers.", 'framework') );
         parent::__construct(false, $name = 'Sermon Speakers', $widget_ops);
	}
	// widget form creation
	public function form($instance) {
	
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
		} else {
			 $title = '';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'framework'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>        
	<?php
	}
	// update widget
	public function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  
		 return $instance;
	}
	// display widget
	public function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   	  
	   echo $args['before_widget'];
		if( !empty($instance['title']) ){
			echo '';
			echo $args['before_title'];
			echo apply_filters('widget_title',$post_title, $instance, $this->id_base);
			echo $args['after_title'];
			echo '';
		}
		$post_terms = get_terms('sermons-speakers');
		echo '<ul>';
		foreach ($post_terms as $term) {
			$term_name = $term->name;
			$term_link = get_term_link($term,'sermons-speakers');
			$count = $term->count;
			echo '<li><a href="' . $term_link . '">' . $term_name . '</a> (' . $count . ')</li>';
		}
		echo '</ul>';
          echo $args['after_widget'];
	}
}
// register widget
add_action( 'widgets_init', function(){
	register_widget( 'sermon_speakers' );
});
?>