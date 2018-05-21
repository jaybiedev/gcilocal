<?php

/*
Widget Name: Featured Block Widget
Description: A widget to show featured block to redirect users to some page.
Author: imithemes
Author URI: http://imithemes.com
*/

class Featured_Block_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'featured-block-widget',
			__('Featured Block Widget', 'imic-framework'),
			array(
				'description' => __('A widget to show featured block to redirect users to some page.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-star-filled',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'featured_image' => array(
					'type' => 'media',
					'label' => __('Image file', 'framework'),
					'library' => 'image',
					'fallback' => true,
				),

				'featured_size' => array(
					'type' => 'select',
					'label' => __('Image size', 'framework'),
					'options' => array(
						'full' => __('Full', 'framework'),
						'large' => __('Large', 'framework'),
						'medium' => __('Medium', 'framework'),
						'thumb' => __('Thumbnail', 'framework'),
					),
				),

				'featured_title' => array(
					'type' => 'text',
					'label' => __('Title text', 'framework'),
				),

				'featured_alt' => array(
					'type' => 'text',
					'label' => __('Alt text', 'framework'),
				),

				'featured_link_text' => array(
					'type' => 'text',
					'label' => __('Button text', 'framework'),
				),

				'featured_url' => array(
					'type' => 'link',
					'label' => __('Destination URL', 'framework'),
				),
				'featured_new_window' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Open in new window', 'framework'),
				),

				'featured_bound' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __('Bound', 'framework'),
					'description' => __("Make sure the image doesn't extend beyond its container.", 'framework'),
				),
				'featured_full_width' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Full Width', 'framework'),
					'description' => __("Resize image to fit its container.", 'framework'),
				),

			),
			plugin_dir_path(__FILE__)
		);
	}


	function get_style_hash($instance) {
		return substr( md5( serialize( $this->get_less_variables( $instance ) ) ), 0, 12 );
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return array();
	}
	
	function get_template_name($instance) {
		return 'featured-block-widget-template';
	}


}

siteorigin_widget_register('featured-block-widget', __FILE__, 'Featured_Block_Widget');