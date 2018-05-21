<?php

/*
Widget Name: Monthly Events Widget
Description: A widget to show list of events with month switcher.
Author: imithemes
Author URI: http://imithemes.com
*/

class Events_Listing_With_Filter_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'event-listing-with-filter-widget',
			__('Monthly Events Widget', 'imic-framework'),
			array(
				'description' => __('A widget to show list of events with month switcher.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-testimonial',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'widget_title' => array(
					'type' => 'text',
					'label' => __('Widget Title', 'imic-framework'),
					'default' => 'More coming events'
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => __('All events button text', 'imic-framework'),
					'default' => __('All Events', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All events button URL', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),
				'categories' => array(
					'type' => 'text',
					'label' => __('Event Categories (Enter comma separated events category slugs)', 'imic-framework'),
				),
			),
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name($instance) {
		return 'template';
	}

	function get_style_name($instance) {
		return false;
	}

}

siteorigin_widget_register('event-listing-with-filter-widget', __FILE__, 'Events_Listing_With_Filter_Widget');