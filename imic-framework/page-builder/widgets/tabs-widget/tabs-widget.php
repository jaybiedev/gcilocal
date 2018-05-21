<?php

/*
Widget Name: Tabs Widget
Description: A widget to add tabs to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Tabs_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'tabs-widget',
			__('Tabs Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add tabs to your pages.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'tab_id' => array(
					'type' => 'number',
					'label' => __('Tabs ID', 'imic-framework'),
					'description' => __('Numeric only. Keep it unique if adding multiple tabs in a single page.', 'imic-framework'),
					
				),
				'tabs' => array(
					'type' => 'repeater',
					'label' => __('Tabs', 'imic-framework'),
					'item_name' => __('Tab', 'imic-framework'),
					'item_label' => array(
						'selector' => "[id*='tab-title']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'fields' => array(

						'tab_nav_title' => array(
							'type' => 'text',
							'label' => __('Tab title', 'imic-framework'),
						),

						'tab_nav_content' => array(
							'type' => 'tinymce',
							'label' => __('Tab Content', 'imic-framework'),
						),

					),
				),
				'display_type' => array(
					'type' => 'select',
					'state_name' => 'vertical',
					'label' => __( 'Choose View', 'imic-framework' ),
					'prompt' => __( 'Choose Display Style', 'framework' ),
					'options' => array(
						'horizontal' => __( 'Horizontal', 'framework' ),
						'vertical' => __( 'Vertical', 'framework' ),
					)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return $instance['display_type'] == 'vertical' ? 'vertical-view' : 'horizontal-view';
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

siteorigin_widget_register('tabs-widget', __FILE__, 'Tabs_Widget');