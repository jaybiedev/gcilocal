<?php
/*** Widget code for Advertisement ***/
class Advertisement extends WP_Widget {
    public function __construct() {
        parent::__construct(
                'Advertisement', // Base ID
                __('Advertisement','framework'), // Name
                array('description' => __('Advertisement Widget', 'framework')) // Args
        );
    }
    public function form($instance) {
        if( $instance) {
			 $title = esc_attr($instance['title']);
			 $image = $instance['image'];
                         $url = esc_attr($instance['url']);
		} else {
			 $title = '';
                         $image='';
                         $url='';
			
		}
        wp_enqueue_media();
        echo '<p>';
        echo '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'framework') . ':</label>';
        echo '<input type="text" class="widefat" id="' . $this->get_field_id('title') . '"';
        echo ' value="' . $title . '" name="' . $this->get_field_name('title') . '"/>';
        echo '</p>';
        //image first
        echo '<p>';
        echo '<label for="' . $this->get_field_id('image') . '">' . __('Image', 'framework') . ': <span class="select-about-image" id="' . $this->get_field_id('select-image') . '" style="cursor: pointer;">' . __('Select an image', 'framework') . '</span></label>';
        echo '<input type="hidden" class="widefat" id="' . $this->get_field_id('image') . '"';
        echo ' value="' .$image. '" name="' . $this->get_field_name('image') . '"/>';
        echo '</p>';
        $img = '';
        if (!empty($image)) {
            $src = wp_get_attachment_image_src($instance['image'], array(226, 400));
            $img = '<img src="' . $src[0] . '" style="max-width: 226px;" />';
        }
        echo '<div id="' . $this->get_field_id('display-image') . '">' . $img . '</div>';
    
//end image first
     //Url   
        echo '<p>';
        echo '<label for="' . $this->get_field_id('url') . '">' . __('Url', 'framework') . ':</label>';
        echo '<input type="text" class="widefat" id="' . $this->get_field_id('url') . '"';
        echo ' value="' . $url . '" name="' . $this->get_field_name('url') . '"/>';
        echo '</p>';
    }
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image'] = $new_instance['image'];
        $instance['url'] = $new_instance['url'];
        return $instance;
    }
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if(!empty($instance['title'])){
        echo $args['before_title'] . $instance['title'] . $args['after_title'];
       }
       if(!empty($instance['url'])){
        echo '<a href ="'.$instance['url'].'">'.wp_get_attachment_image($instance['image'], array(250, 500)).'</a>';
       }
      
        echo $args['after_widget'];
    }
}
function init_widgets() {
    register_widget('Advertisement');
}
function add_media_scripts() {
    wp_enqueue_script('about-me-media-uploader', get_template_directory_uri() . '/js/media-uploader.js');
}
add_action('admin_enqueue_scripts', 'add_media_scripts');
// register widget
add_action( 'widgets_init', function(){
	register_widget( 'Advertisement' );
});
?>