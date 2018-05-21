<?php

/*
Widget Name: imithemes - Event Grid and Timeline Style Widget
Description: A widget to show events in grid and timeline style.
Author: imithemes
Author URI: http://imithemes.com
*/

class Nativechurch_Event_Grid_Timeline_List extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'event-grid-timeline-list-widget',
			esc_html__('imithemes - Event Grid and Timeline Style Widget', 'imic-framework'),
			array(
				'description' => esc_html__('A widget to show events in grid and timeline style.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'categories' => array(
					'type' => 'text',
					'label' => __('Event Categories (Enter comma separated events category slugs)', 'imic-framework'),
				),
				'listing_layout' => array(
					'type' => 'section',
					'label' => esc_html__( 'Layout', 'imic-framework' ),
					'hide' => false,
					'description' => esc_html__( 'Choose listing layout.', 'imic-framework' ),
					'fields' => array(
						'layout_type'    => array(
							'type'    => 'radio',
							'default' => 'list',
							'label'   => esc_html__( 'Layout Type', 'imic-framework' ),
							'options' => array(
								'grid' => esc_html__( 'Grid Style', 'imic-framework' ),
								'timeline'      => esc_html__( 'Timeline Style', 'imic-framework' ),
							),
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'layout_type' )
							),
						),
						'number_of_posts' => array(
							'type' => 'slider',
							'label' => __( 'Number of events to show', 'imic-framework' ),
							'default' => 4,
							'min' => 1,
							'max' => 50,
							'integer' => true,
							'state_handler' => array(
								'layout_type[grid]' => array('show'),
								'layout_type[timeline]' => array('hide'),
				        )
						),
				        'show_pagination' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __('Show pagination', 'imic-framework'),
				           'state_handler' => array(
								'layout_type[grid]' => array('show'),
								'layout_type[timeline]' => array('hide'),
				        )
						),
						'grid_column' => array(
							'type' => 'select',
							'state_name' => 'grid',
							'label' => __( 'Choose Grid Column', 'framework' ),
							'options' => array(
								'1' => __( 'One', 'framework' ),
								'2' => __( 'Two', 'framework' ),
								'3' => __( 'Three', 'framework' ),
								'4' => __( 'Four', 'framework' ),
							),
							'state_handler' => array(
								'layout_type[grid]' => array('show'),
								'layout_type[timeline]' => array('hide'),
							),
						),
					),
				)),
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name( $instance ) {
		return $instance['listing_layout']['layout_type'] == 'grid' ? 'template-events_grid' : 'template-events-timeline';
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

siteorigin_widget_register('event-grid-timeline-list-widget', __FILE__, 'Nativechurch_Event_Grid_Timeline_List');