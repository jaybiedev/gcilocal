<?php

/*
Widget Name: Staff Grid Widget
Description: A widget to show Staff members in grid view.
Author: imithemes
Author URI: http://imithemes.com
*/

class Staff_Grid_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'staff-grid-widget',
			__('Staff Grid Widget', 'imic-framework'),
			array(
				'description' => __('A widget to show Staff members in grid view.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-format-gallery',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'title' => array(
					'type' => 'text',
					'label' => __('Title', 'framework'),
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => __('All staff button text', 'imic-framework'),
					'default' => __('All Staff', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All staff button URL', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'categories' => array(
					'type' => 'text',
					'label' => __('Categories (Enter comma separated sermon category slugs)', 'imic-framework'),
				),
				'orderby' => array(
					'type' => 'select',
					'label' => __('Order by Page ID', 'imic-framework'),
					'state_name' => 'no',
					'prompt' => __( 'Order By Page ID', 'framework' ),
					'options' => array(
						'no' => __( 'No', 'framework' ),
						'yes' => __( 'yes', 'framework' ),
					)
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Staff Members to show', 'imic-framework' ),
					'default' => 3,
					'min' => 1,
					'max' => 50,
					'integer' => true,
				),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show social profile icons, member role/position', 'imic-framework'),
				),
				'excerpt_length' => array(
					'type' => 'text',
					'default' => 50,
					'label' => __('Length of excerpt(Enter the number of words to show)? Leave blank to hide - Default is: 50', 'imic-framework'),
				),
				'read_more_text' => array(
					'type' => 'text',
					'default' => 'Read More',
					'label' => __('Read More, Leave blank to hide button - Default is Read More', 'imic-framework'),
				),
				'grid_column' => array(
					'type' => 'select',
					'state_name' => 'grid',
					'prompt' => __( 'Choose Grid Column', 'framework' ),
					'options' => array(
						'6' => __( 'Two', 'framework' ),
						'4' => __( 'Three', 'framework' ),
						'3' => __( 'Four', 'framework' ),
					)
				),
				
			),
			plugin_dir_path(__FILE__)
		);
	}


	
	function get_template_name( $instance ) {
		return 'grid-view';
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return false;
	}


}

siteorigin_widget_register('staff-grid-widget', __FILE__, 'Staff_Grid_Widget');