<?php

/*
Widget Name: Toggle Widget
Description: A widget to add Toggle/Accordion to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Toggle_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'toggle-widget',
			__('Toggle Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add Toggle/Accordion to your pages.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'tab_id' => array(
					'type' => 'text',
					'label' => __('Toggle Name', 'imic-framework'),
					'description' => __('Keep it unique if adding multiple tabs in a single page.(No spaces in name)', 'imic-framework'),
					
				),
				'tabs' => array(
					'type' => 'repeater',
					'label' => __('Toggles', 'imic-framework'),
					'item_name' => __('Toggle', 'imic-framework'),
					'item_label' => array(
						'selector' => "[id*='tab-title']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'fields' => array(

						'tab_nav_title' => array(
							'type' => 'text',
							'label' => __('Toggle title', 'imic-framework'),
						),

						'tab_nav_content' => array(
							'type' => 'tinymce',
							'label' => __('Toggle Content', 'imic-framework'),
						),

					),
				),
				'display_type' => array(
					'type' => 'select',
					'state_name' => 'togglize',
					'label' => __( 'Choose Type', 'imic-framework' ),
					'prompt' => __( 'Choose Type', 'framework' ),
					'options' => array(
						'togglize' => __( 'Toggles', 'framework' ),
						'accordionize' => __( 'Accordions', 'framework' ),
					)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return $instance['display_type'] == 'togglize' ? 'toggles' : 'accordions';
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

siteorigin_widget_register('toggle-widget', __FILE__, 'Toggle_Widget');