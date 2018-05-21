<?php

/*
Widget Name: Progress Bar Widget
Description: A widget to add Progress bar to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Progressbar_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'progressbar-widget',
			__('Progressbar Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add Progress bar to your pages.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'title' => array(
					'type' => 'text',
					'label' => __('Title', 'imic-framework'),
					'description' => __('Title to show above the progress bar.', 'imic-framework'),
					
				),
				'percentile' => array(
					'type' => 'text',
					'label' => __('Progress Percentile', 'imic-framework'),
					'description' => __('Enter the progress percentile without %. For ex: 90', 'imic-framework'),
					
				),
				
				'type' => array(
					'type' => 'select',
					'state_name' => 'primary',
					'label' => __( 'Choose Color Style', 'imic-framework' ),
					'prompt' => __( 'Choose Color Style', 'imic-framework' ),
					'options' => array(
						'primary' => __( 'Primary', 'imic-framework' ),
						'warning' => __( 'Warning', 'imic-framework' ),
						'info' => __( 'Info', 'imic-framework' ),
						'danger' => __( 'Danger', 'imic-framework' ),
						'success' => __( 'Success', 'imic-framework' ),
					)
				),
				'custom_color' => array(
					'type' => 'color',
					'label' => __('Custom progress bar color', 'imic-framework'),
					'default' => '',
				),
				'stripped' => array(
					'type' => 'checkbox',
					'label' => __('Use stripped background animation', 'imic-framework'),
					'default' => false,
				),
				'tootltip' => array(
					'type' => 'checkbox',
					'label' => __('Show progress percentile tooltip', 'imic-framework'),
					'default' => false,
				),
				'animation' => array(
					'type' => 'text',
					'default' => 200,
					'label' => __('Delay animation', 'imic-framework'),
					'description' => __('Enter the delay animation time. 100 means 1 sec. Leave blank for no animation', 'imic-framework'),
					
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

siteorigin_widget_register('progressbar-widget', __FILE__, 'Progressbar_Widget');