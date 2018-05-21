<?php

/*
Widget Name: Alert Box Widget
Description: A widget to add Alert Boxes to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Alert_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'alert-widget',
			__('Alert Widget', 'imic-framework'),
			array(
				'description' => __('A widget to add Alert Boxes to your pages.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'content' => array(
					'type' => 'textarea',
					'label' => __('Alert Box Content', 'imic-framework'),
					'description' => __('HTML tags are allowed in this.', 'imic-framework'),
					
				),
				'type' => array(
					'type' => 'select',
					'state_name' => 'standard',
					'label' => __( 'Choose Color Style', 'imic-framework' ),
					'prompt' => __( 'Choose Color Style', 'imic-framework' ),
					'options' => array(
						'standard' => __( 'Standard', 'imic-framework' ),
						'warning' => __( 'Warning', 'imic-framework' ),
						'error' => __( 'Error', 'imic-framework' ),
						'info' => __( 'Info', 'imic-framework' ),
						'success' => __( 'Success', 'imic-framework' ),
					)
				),
				'custom_color' => array(
					'type' => 'color',
					'label' => __('Custom background color', 'imic-framework'),
					'default' => '',
				),
				'custom_bcolor' => array(
					'type' => 'color',
					'label' => __('Custom border color', 'imic-framework'),
					'default' => '',
				),
				'custom_tcolor' => array(
					'type' => 'color',
					'label' => __('Custom text color', 'imic-framework'),
					'default' => '',
				),
				'close' => array(
					'type' => 'checkbox',
					'label' => __('Show close button', 'imic-framework'),
					'default' => true,
				),
				'animation' => array(
					'type' => 'select',
					'state_name' => 'fadeIn',
					'label' => __( 'Choose animation', 'imic-framework' ),
					'prompt' => __( 'Choose animation', 'imic-framework' ),
					'options' => array(
						'flash' => __( 'Flash', 'imic-framework' ),
						'shake' => __( 'Shake', 'imic-framework' ),
						'bounce' => __( 'Bounce', 'imic-framework' ),
						'tada' => __( 'Tada', 'imic-framework' ),
						'swing' => __( 'Swing', 'imic-framework' ),
						'wobble' => __( 'Wobble', 'imic-framework' ),
						'wiggle' => __( 'Wiggle', 'imic-framework' ),
						'pulse' => __( 'Pulse', 'imic-framework' ),
						'fadeIn' => __( 'FadeIn', 'imic-framework' ),
						'fadeInUp' => __( 'FadeInUp', 'imic-framework' ),
						'fadeInLeft' => __( 'FadeInLeft', 'imic-framework' ),
						'fadeInRight' => __( 'FadeInRight', 'imic-framework' ),
						'fadeInUpBig' => __( 'FadeInUpBig', 'imic-framework' ),
						'fadeInDownBig' => __( 'FadeInDownBig', 'imic-framework' ),
						'fadeInLeftBig' => __( 'FadeInDownBig', 'imic-framework' ),
						'fadeInRightBig' => __( 'FadeInRightBig', 'imic-framework' ),
						'bounceIn' => __( 'BounceIn', 'imic-framework' ),
						'bounceInUp' => __( 'BounceInUp', 'imic-framework' ),
						'bounceInDown' => __( 'BounceInDown', 'imic-framework' ),
						'bounceInLeft' => __( 'BounceInLeft', 'imic-framework' ),
						'bounceInRight' => __( 'BounceInRight', 'imic-framework' ),
						'rotateIn' => __( 'RotateIn', 'imic-framework' ),
						'rotateInUpLeft' => __( 'RotateInUpLeft', 'imic-framework' ),
						'rotateInDownLeft' => __( 'RotateInDownLeft', 'imic-framework' ),
						'rotateInUpRight' => __( 'RotateInUpRight', 'imic-framework' ),
						'rotateInDownRight' => __( 'RotateInDownRight', 'imic-framework' ),
					)
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

siteorigin_widget_register('alert-widget', __FILE__, 'Alert_Widget');