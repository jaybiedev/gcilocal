<?php

/*
Widget Name: Spacer Widget
Description: A widget to add blank spaces in between widgets.
Author: imithemes
Author URI: http://imithemes.com
*/

class Spacer_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'spacer-widget',
			__('Spacer Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add blank spaces in between widgets.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'height' => array(
					'type' => 'text',
					'default'=> '30px',
					'label' => __('Spacer height in pixels. Example: 30px', 'imic-framework'),
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

siteorigin_widget_register('spacer-widget', __FILE__, 'Spacer_Widget');