<?php

/*
Widget Name: Animated Counter Widget
Description: A widget to add Animated Numbers Counter to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Counter_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'counter-widget',
			__('Counter Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add Animated Numbers Counter to your pages.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'icon' => array(
					'type' => 'icon',
					'label' => __('Icon', 'framework'),
				),
				'icon_color' => array(
					'type' => 'color',
					'label' => __('Icon color', 'framework'),
					'default' => '#999999',
				),
  				'icon_size' => array(
					'type' => 'number',
					'label' => __('Icon size', 'framework'),
					'default' => 48,
				),
				'icon_image' => array(
					'type' => 'media',
					'library' => 'image',
					'label' => __('Icon image', 'framework'),
					'description' => __('Use your own icon image.', 'framework'),
				),
				'number' => array(
					'type' => 'number',
					'label' => __('Number (To count to)', 'imic-framework'),
				),
				'number_color' => array(
					'type' => 'color',
					'label' => __('Number Color', 'imic-framework'),
					'default' => '#333333',
				),
				'text' => array(
					'type' => 'text',
					'label' => __('Text to show below number', 'imic-framework'),
				),
				'text_color' => array(
					'type' => 'color',
					'label' => __('Text Color', 'imic-framework'),
					'default' => '#999999',
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return 'template';
	}


	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return array();
	}
	function modify_instance($instance){
		return $instance;
	}


}

siteorigin_widget_register('counter-widget', __FILE__, 'Counter_Widget');