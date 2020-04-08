<?php
$home_page = get_option('page_on_front');
$featured_block = get_post_meta($home_page,'imic_home_featured_blocks1',true);
$featured_block2 = get_post_meta($home_page,'imic_home_featured_blocks2',true);
$featured_block3 = get_post_meta($home_page,'imic_home_featured_blocks3',true);
$all_blocks = array($featured_block,$featured_block2,$featured_block3);

if($featured_block!='') {
    update_post_meta($home_page,'imic_home_row_featured_blocks',$all_blocks);
    update_post_meta($home_page,'imic_home_featured_blocks1','');
    update_post_meta($home_page,'imic_home_featured_blocks2','');
    update_post_meta($home_page,'imic_home_featured_blocks3','');
}

function prefix_register_meta_boxes( $meta_boxes ) {
    $sermons_cats = apply_filters('nativechurch_get_terms', 'sermons-category');
    /* * ** Meta Box Functions **** */
    $prefix = 'imic_';
    global $meta_boxes;
    load_theme_textdomain('framework', IMIC_FILEPATH . '/language');
    $meta_boxes = array();
      /* Staff Meta Box
      ================================================== */
$meta_boxes[] = array(
    'id' => 'staff_meta_box',
    'title' => __('Staff Member Meta', 'framework'),
    'pages' => array('staff'),
    'fields' => array(
        // Staff MEMBER FACEBOOK
        array(
            'name' => __('Facebook', 'framework'),
            'id' => $prefix . 'staff_member_facebook',
            'desc' => __("Enter staff member's Facebook URL.", 'framework'),
            'clone' => false,
            'type' => 'hidden',
            'std' => '',
        ),
        // Staff MEMBER TWITTER
        array(
            'name' => __('Twitter', 'framework'),
            'id' => $prefix . 'staff_member_twitter',
            'desc' => __("Enter staff member's Twitter username.", 'framework'),
            'clone' => false,
            'type' => 'hidden',
            'std' => '',
        ),
        // Staff MEMBER Google+
        array(
            'name' => __('Google+', 'framework'),
            'id' => $prefix . 'staff_member_google_plus',
            'desc' => __("Enter staff member's Google+ URL.", 'framework'),
            'type' => 'hidden',
            'std' => '',
        ),
        // Staff MEMBER Pinterest+
        array(
            'name' => __('Pinterest', 'framework'),
            'id' => $prefix . 'staff_member_pinterest',
            'desc' => __("Enter staff member's Pinterest URL.", 'framework'),
            'type' => 'hidden',
            'std' => '',
        ),
      // Staff MEMBER Email
        array(
            'name' => __('Email', 'framework'),
            'id' => $prefix . 'staff_member_email',
            'desc' => __("Enter staff member's Email.", 'framework'),
            'type' => 'text',
            'std' => '',
        ),
      // Staff member Phone number
        array(
            'name' => __('Phone Number', 'framework'),
            'id' => $prefix . 'staff_member_phone',
            'desc' => __("Enter staff member's Phone Number.", 'framework'),
            'type' => 'text',
            'std' => '',
        ),
		array(
            'name' => __('Job Title', 'framework'),
            'id' => $prefix . 'staff_job_title',
            'desc' => __("Enter staff member's job title.", 'framework'),
            'type' => 'text',
            'std' => '',
        ),
 	  	array(
			'name'  => __('Social Icon', 'framework'),
			'id'    => $prefix."social_icon_list",
			'desc'  =>  __('Select Social Icons and enter URL.', 'framework'),
			'type'  => 'text_list',
			'clone' => true,
			'options' => array(
				'0' => __('Social', 'framework'),
				'1' => __('URL', 'framework')
			)
		),
    )
);
/* Causes Meta Box
  ================================================== */
/*** Causes Details Meta box ***/   
$meta_boxes[] = array(
    'id' => 'cause_meta_box',
    'title' => __('Cause Details', 'framework'),
    'pages' => array('causes'),
    'fields' => array( 
        //Cause End Date
        array(
            'name' => __('Cause End Date', 'framework'),
            'id' => $prefix . 'cause_end_dt',
            'desc' => __("Choose date when this cause will end and stop accepting donations.", 'framework'),
            'type' => 'date',
			'js_options' => array(
				'dateFormat'      =>'yy-mm-dd',
				'changeMonth'     => true,
				'changeYear'      => true,
				'showButtonPanel' => false,
			),
        ),
		//Frequency Count
		array(
            'name' => __('Cause Amount', 'framework'),
            'id' => $prefix . 'cause_amount',
            'desc' => __("Insert total amount required for cause.", 'framework'),
            'type' => 'text',
        ), 
		array(
            'name' => __('Cause Amount Received', 'framework'),
            'id' => $prefix . 'cause_amount_received',
            'desc' => __("Total amount received so far for this cause.", 'framework'),
            'type' => 'text',
        ),      
    )
);
/* Event Meta Box
  ================================================== */
/*** Event Details Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_meta_box',
    'title' => __('Event Details Meta Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
        // Event Start Date  
        array(
            'name' => __('Event Start Date', 'framework'),
            'id' => $prefix . 'event_start_dt',
            'desc' => __("Choose date when this event will start.", 'framework'),
            'type' => 'date',
			'js_options' => array(
	                'dateFormat'      => 'yy-mm-dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
        ),
        //Event End Date
        array(
            'name' => __(' Event End Date', 'framework'),
            'id' => $prefix . 'event_end_dt',
            'desc' => __("Choose date when this event will end.", 'framework'),
            'type' => 'date',
			'js_options' => array(
				'dateFormat'      =>'yy-mm-dd',
				'changeMonth'     => true,
				'changeYear'      => true,
				'showButtonPanel' => false,
			),
        ),
		#Event All Day Event
        array(
            'name' => __('All Day Event', 'framework'),
            'desc' => __("Check this option if this event will be happening for the day of the chosen start/end date. This will work for single day events only.", 'framework'),
            'id' => $prefix . 'event_all_day',
            'type' => 'checkbox',
        ),
        //Event Start Time
		array(
			'name' => __( 'Event Start Time', 'framework' ),
			'id' => $prefix.'event_start_tm',
			'type' => 'time',
			// jQuery datetime picker options. See here http://trentrichardson.com/examples/timepicker/
			'js_options' => array(
			'stepMinute' => 1,
			'showSecond' => false,
			'hourMax'=> 24,
			'stepSecond' => 1,
			),
			),
        //Event End Time
		array(
			'name' => __( 'Event End Time', 'framework' ),
			'id' => $prefix.'event_end_tm',
			'type' => 'time',
			// jQuery datetime picker options. See here http://trentrichardson.com/examples/timepicker/
			'js_options' => array(
			'stepMinute' => 1,
			'showSecond' => false,
			'hourMax'=> 24,
			'stepSecond' => 1,
			),
			),
         //Address
		array(
			'name'  => __('Address', 'framework'),
			'id'    => $prefix."event_address",
			'desc'  =>  __('Enter event\'s address.', 'framework'),
			'type' => 'textarea',
			'cols' => 20,
			'rows' => 3,
		),
            //Contact Number
		array(
			'name'  => __('Contact Number', 'framework'),
			'id'    => $prefix."event_contact",
			'desc'  =>  __('Enter event\'s manager contact number. This is a static value which you might want to show on single event page.', 'framework'),
			'type'  => 'text',
		), 
		array(
            'name' => __('Event Registration', 'framework'),
            'id' => $prefix . 'event_registration_status',
            'desc' => __("Select Enable to activate Event Registration.", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Disable', 'framework'),
				'1' => __('Enable','framework'),
            ),
			'std' => 0,
        ),
		array(
			'name'  => __('Event Registration Fee', 'framework'),
			'id'    => $prefix."event_registration_fee",
			'desc'  =>  __('Enter event\'s registration fee(This field will work only when imithemes payment plugin is active and above option Event Registration is enabled.) For multiple type tickets use the other metabox"Event Tickets Type" below and leave this field empty.', 'framework'),
			'type'  => 'text',
		),
		array(
            'name' => __('Guest Registration', 'framework'),
            'id' => $prefix . 'event_registration_required',
            'desc' => __("Select Enable to activate Guest Registration(When enabled it will not be mandatory for users to register on your website to be able to register for an event) Works only when above option Event Registration is enabled.", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Disable', 'framework'),
				'1' => __('Enable','framework'),
            ),
			'std' => 0,
        ), 
		array(
			'name' => __( 'Custom Registration Button URL', 'framework' ),
			'id' => $prefix.'custom_event_registration',
			'desc' => __("For example EventBrite Event page URL of yours. This URL will be used for the registration button on single event page when the above option Event Registration is enabled", 'framework'),
			'type' => 'text'
		),
		array(
			'name' => __( 'Open custom URL in new Tab/Window', 'framework' ),
			'id' => $prefix.'custom_event_registration_target',
			'type' => 'checkbox',
			// Value can be 0 or 1
			'std' => 1,
		),
    )
);
/*** Event Recurrence Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_recurring_box',
    'title' => __('Event Recurrence Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 		 
        //Frequency of Event
        array(
            'name' => __('Event Frequency', 'framework'),
            'id' => $prefix . 'event_frequency',
            'desc' => __("Select Frequency.", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Not Required','framework'),
                '1' => __('Every Day', 'framework'),
				'2' => __('Every Second Day', 'framework'),
				'3' => __('Every Third Day', 'framework'),
				'4' => __('Every Fourth Day', 'framework'),
				'5' => __('Every Fifth Day', 'framework'),
				'6' => __('Every Sixth Day', 'framework'),
                '7' => __('Every Week', 'framework'),
				'30' => __('Every Month', 'framework'),
				'35' => __('More Options', 'framework'),
				'32' => __('Multiple Dates Options', 'framework'),
            ),
        ),
		array(
            'name' => __('Event Week Day', 'framework'),
            'id' => $prefix . 'event_week_day',
            'desc' => __("Select Week Day.", 'framework'),
            'type' => 'select',
            'options' => array(
				'sunday' => __('Sunday','framework'),
                'monday' => __('Monday', 'framework'),
				'tuesday' => __('Tuesday', 'framework'),
				'wednesday' => __('Wednesday', 'framework'),
				'thursday' => __('Thursday', 'framework'),
				'friday' => __('Friday', 'framework'),
				'saturday' => __('Saturday', 'framework'),
        ),
		),
		array(
            'name' => __('Day of Month', 'framework'),
            'id' => $prefix . 'event_day_month',
            'desc' => __("Select Day of Month.", 'framework'),
            'type' => 'select',
            'options' => array(
				'first' => __('First','framework'),
                'second' => __('Second', 'framework'),
				'third' => __('Third', 'framework'),
				'fourth' => __('Fourth', 'framework'),
				'last' => __('Last', 'framework'),
        ),
		),
		
		array(
            'name' => __('Event Multiple Recurring Date', 'framework'),
            'id' => $prefix . 'event_recurring_dt',
            'desc' => __("Insert multiple dates for recurring event, this will work only with single day event.", 'framework'),
            'type' => 'date',
			'clone' => true,
			'js_options' => array(
	                'dateFormat'      => 'yy-mm-dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
        ),
		//Frequency Count
		array(
            'name' => __('Number of times to repeat event', 'framework'),
            'id' => $prefix . 'event_frequency_count',
            'desc' => __("Enter the number of how many time this event should repeat. max number of times an event can repeat is 999, events which have different start and end date could not recur.", 'framework'),
            'type' => 'text',
        ),    
		array(
            'name' => __('Do not change', 'framework'),
            'id' => $prefix . 'event_frequency_end',
            'desc' => __("If any changes done in this file, may your theme will not work like running now.", 'framework'),
            'type' => 'hidden',
        ),    
    )
);
/*** Total Persons Details Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_person_meta_box',
    'title' => __('Total Persons Details Meta Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
        //Attendees
       	array(
			'name'  => __('Attendees', 'framework'),
			'id'    => $prefix."event_attendees",
			'desc'  =>  __('Enter number of attendees. This is a static value which you might want to show on single event page.', 'framework'),
			'type'  => 'text',
		),
        //Staff Members
		array(
			'name'  => __('Staff Members', 'framework'),
			'id'    => $prefix."event_staff_members",
			'desc'  =>  __('Enter number of staff members. This is a static value which you might want to show on single event page.', 'framework'),
			'type'  => 'text',
		),
		array(
			'name'  => __('Email Address', 'framework'),
			'id'    => $prefix."event_email",
			'desc'  =>  __('Enter Email for Event. This email address is where theme will send event registrants info and can be used by event page visitors to contact directly', 'framework'),
			'type'  => 'text',
		),
    )
);
/*** Featured Event Meta box ***/   
$meta_boxes[] = array(
    'id' => 'featured_event_meta_box',
    'title' => __('Featured Event Meta Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
        //Attendees
       	array(
			'name'  => __('Featured Event', 'framework'),
			'id'    => $prefix."event_featured",
			'desc'  =>  __('Select for featured event. If this is set to Yes then this event will be available as an option to select for Featured Event Widget at Appearance > Widgets', 'framework'),
			'type'  => 'select',
                       'options' => array(
                       '0' => __('No', 'framework'),
                      '1' => __('Yes', 'framework'),
                     ),
		),
        )
);
/* Gallery Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'gallery_meta_box',
    'title' => __('Post Meta Box', 'framework'),
    'pages' => array('gallery', 'post'),
    'fields' => array(
        // Gallery Video Url
        array(
            'name' => __('Video Url', 'framework'),
            'id' => $prefix . 'gallery_video_url',
            'desc' => __("Enter the video URL.", 'framework'),
            'type' => 'url',
        ),
        // Gallery Link Url
        array(
            'name' => __('Link Url', 'framework'),
            'id' => $prefix . 'gallery_link_url',
            'desc' => __("Enter the URL for Link gallery type post.", 'framework'),
            'type' => 'url',
        ),
        // Gallery Images
        array(
            'name' => __('Gallery Image', 'framework'),
            'id' => $prefix . 'gallery_images',
            'desc' => __("Choose/Upload gallery images.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Images', 'framework'),
            'id' => $prefix . 'gallery_slider_image',
            'desc' => __("Enter slider images.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Speed', 'framework'),
            'id' => $prefix . 'gallery_slider_speed',
            'desc' => __("Default slider speed is 5000. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
            'type' => 'text',
        ),
       array(
            'name' => __('Slider Pagination', 'framework'),
            'id' => $prefix . 'gallery_slider_pagination',
            'desc' => __("Enable to show pagination for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Enable', 'framework'),
                'no' => __('Disable', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Auto Slide', 'framework'),
            'id' => $prefix . 'gallery_slider_auto_slide',
            'desc' => __("Select Yes to slide automatically.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Direction Arrows', 'framework'),
            'id' => $prefix . 'gallery_slider_direction_arrows',
            'desc' => __("Select Yes to show slider direction arrows.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'gallery_slider_effects',
            'desc' => __("Select effect for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'fade' => __('Fade', 'framework'),
                'slide' => __('Slide', 'framework'),
            ),
        ),
        //Audio Display
        array(
            'name' => __('Audio Display', 'framework'),
            'id' => $prefix . 'gallery_audio_display',
            'desc' => __("Select audio type.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('By iFrame', 'framework'),
                '2' => __('By Upload', 'framework'),
            ),
        ),
        array(
            'name' => __('SoundCloud Track', 'framework'),
            'id' => $prefix . 'gallery_audio',
            'desc' => __("Enter SoundCloud iframe code to show on post.", 'framework'),
            'type' => 'textarea',
            'std' => '',
        ),
        // Upload Audio
        array(
            'name' => __('Audio', 'framework'),
            'id' => $prefix . 'gallery_uploaded_audio',
            'desc' => __("Upload audio.", 'framework'),
            'type' => 'file_advanced',
            'max_file_uploads' => 1
        ),
    )
);
/* Post Page Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'post_page_meta_box',
    'title' => __('Page/Post Header Options', 'framework'),
   	'pages' => array('post','page','sermons','event','product', 'staff'),
    'fields' => array(
        
        // Custom title
        array(
            'name' => __('Custom Title', 'framework'),
            'id' => $prefix . 'post_page_custom_title',
            'desc' => __("Enter custom title for the page.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Custom SubTitle', 'framework'),
            'id' => $prefix . 'post_page_custom_subtitle',
            'desc' => __("Enter sub title for the page.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Custom Title Font Color', 'framework'),
            'id' => $prefix . 'post_page_custom_title_font_color',
            'desc' => __("Enter custom title color (default #fff / white).", 'framework'),
            'type' => 'text',
        ),
		array(
            'name' => __('Choose Banner Type', 'framework'),
            'id' => $prefix . 'pages_Choose_slider_display',
            'desc' => __("Select header type", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Image Banner', 'framework'),
                '1' => __('Slider', 'framework'),
                '2' => __('None', 'framework'),
            ),
        ),
		array(
            'name' => __('Banner Image', 'framework'),
            'id' => $prefix . 'header_image',
            'desc' => __("Upload banner image for header for this Page/Post.", 'framework'),
            'type' => 'image_advanced',
            'max_file_uploads' => 1
        ),
        array(
		   'name' => __('Select Revolution Slider from list','framework'),
			'id' => $prefix . 'pages_select_revolution_from_list',
			'desc' => __("Select Revolution Slider from the list", 'framework'),
			'type' => 'select',
			'options' => RevSliderShortCode(),
		),
		array(
            'name' => __('Header height in px', 'framework'),
            'id' => $prefix . 'pages_slider_height',
            'desc' => __("Default height is 150px.", 'framework'),
            'type' => 'text',
			'default' => '150',
        ),
        array(
            'name' => __('Slider Images', 'framework'),
            'id' => $prefix . 'pages_slider_image',
            'desc' => __("Upload/Choose slider images.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Speed', 'framework'),
            'id' => $prefix . 'pages_slider_speed',
            'desc' => __("Default slider speed is 5000. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
            'type' => 'text',
        ),
		array(
            'name' => __('Slider Pagination', 'framework'),
            'id' => $prefix . 'pages_slider_pagination',
            'desc' => __("Select Enable to show pagination for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Enable', 'framework'),
                'no' => __('Disable', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Auto Slide', 'framework'),
            'id' => $prefix . 'pages_slider_auto_slide',
            'desc' => __("Select Yes to slide automatically.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Direction Arrows', 'framework'),
            'id' => $prefix . 'pages_slider_direction_arrows',
            'desc' => __("Select Yes to show slider direction arrows.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'pages_slider_effects',
            'desc' => __("Select effects for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'fade' => __('Fade', 'framework'),
                'slide' => __('Slide', 'framework'),
            ),
        ),
		array(
			'name' => __( 'Banner Color', 'framework' ),
			'id' => $prefix.'pages_banner_color',
			'type' => 'color',
			),
        )
);
/* Post Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'post_meta_box',
    'title' => __('Custom Description  Meta Box', 'framework'),
    'pages' => array('post'),
    'fields' => array(
        // Custom Description
        array(
            'name' => __('Custom Description', 'framework'),
            'id' => $prefix . 'post_custom_description',
            'desc' => __("Enter Custom Description.", 'framework'),
            'type' => 'textarea',
        ),
     )
);
/* Sermon Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'sermons_meta_box',
    'title' => __('Sermons  Meta Box', 'framework'),
    'pages' => array('sermons'),
    'fields' => array(
        // HEADING
		array(
			'type' => 'heading',
			'name' => __( 'Audio for Sermon', 'framework' ),
			'id' => 'heading_id3', // Not used but needed for plugin
			'desc' => __( 'Add audio to your sermons either by direct URL or Upload here', 'framework' ),
		),
         //Audio Display
        array(
            'name' => __('Upload Audio', 'framework'),
            'id' => $prefix . 'sermons_audio_upload',
            'desc' => __("Select Audio Upload Option.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('By Upload', 'framework'),
                '2' => __('By URL', 'framework'),
            ),
        ),
        // Upload Audio
        array(
            'name' => __('Audio', 'framework'),
            'id' => $prefix . 'sermons_audio',
            'desc' => __("Upload Audio.", 'framework'),
            'type' => 'file_advanced',
            'max_file_uploads' => 1
        ),
        array(
            'name' => __('Audio', 'framework'),
            'id' => $prefix . 'sermons_url_audio',
            'desc' => __("Enter Audio Url for Sermons", 'framework'),
            'type' => 'url',
           
        ),
       
        // HEADING
		array(
			'type' => 'heading',
			'name' => __( 'Video for Sermon', 'framework' ),
			'id' => 'heading_id2', // Not used but needed for plugin
		),
		// Sermons Video
        array(
            'name' => __('Sermon Video', 'framework'),
            'id' => $prefix . 'sermons_video_upload_option',
            'desc' => __("Select Video Option.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('By Vimeo/Youtube URL/Facebook', 'framework'),
                '2' => __('Custom Video', 'framework'),
            ),
        ),
        // Sermons MP4
        array(
            'name' => __('Sermon Video .mp4', 'framework'),
            'id' => $prefix . 'sermons_video_mp4',
            'desc' => __("This is mandatory for custom video. MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
        // Sermons Webm
        array(
            'name' => __('Sermon Video .webm', 'framework'),
            'id' => $prefix . 'sermons_video_webm',
            'desc' => __("WebM/VP8 for Firefox4, Opera, and Chrome", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
        // Sermons OGV
        array(
            'name' => __('Sermon Video .ogv', 'framework'),
            'id' => $prefix . 'sermons_video_ogv',
            'desc' => __("Ogg/Vorbis for older Firefox and Opera versions", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
        // Sermons Poster
        array(
            'name' => __('Sermon Video Poster Image', 'framework'),
            'id' => $prefix . 'sermons_video_poster',
            'desc' => __("An image which will appear as a poster prior to video play start.", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
        // Sermons Url
        array(
            'name' => __('Sermon Url', 'framework'),
            'id' => $prefix . 'sermons_url',
            'desc' => __("Enter vimeo/youtube url/facebook video url for Sermons.", 'framework'),
            'type' => 'url',
        ),
         // HEADING
		array(
			'type' => 'heading',
			'name' => __( 'PDF for Sermon', 'meta-box' ),
			'id' => 'heading_id1', // Not used but needed for plugin
		),
         //Pdf
        array(
            'name' => __('Upload Pdf', 'framework'),
            'id' => $prefix . 'sermons_pdf_upload_option',
            'desc' => __("Select Pdf Upload Option.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('By Upload', 'framework'),
                '2' => __('By Url', 'framework'),
            ),
        ),
        // Upload Pdf
        array(
            'name' => __('Upload Pdf', 'framework'),
            'id' => $prefix . 'sermons_Pdf',
            'desc' => __("Upload PDF for sermons.", 'framework'),
            'type' => 'file_advanced',
            'max_file_uploads' => 1
        ),
        // Upload Pdf by url
        array(
            'name' => __('Upload Pdf', 'framework'),
            'id' => $prefix . 'sermons_pdf_by_url',
            'desc' => __("Enter PDF URL for sermons.", 'framework'),
            'type' => 'url',
            
        ),
        // HEADING
		array(
			'type' => 'heading',
			'name' => __( 'Additional Media Attachments', 'framework' ),
			'desc' => __('These media items will be displayed on single sermon page','framework'),
			'id' => 'heading_id4', // Not used but needed for plugin
		),
		// ADDITIONAL VIMEO VIDEO
        array(
            'name' => __('Additional Vimeo Video', 'framework'),
            'id' => $prefix . 'sermons_add_vimeo_url',
            'desc' => __("Enter Vimeo video URL", 'framework'),
            'type' => 'url',
           
        ),
		// ADDITIONAL YOUTUBE VIDEO
        array(
            'name' => __('Additional Youtube Video', 'framework'),
            'id' => $prefix . 'sermons_add_youtube_url',
            'desc' => __("Enter Youtube video URL", 'framework'),
            'type' => 'url',
           
        ),
		// ADDITIONAL SOUNDCLOUD AUDIO
        array(
            'name' => __('Additional Soundcloud Audio', 'framework'),
            'id' => $prefix . 'sermons_add_soundcloud_url',
            'desc' => __("Enter Soundcloud audio URL", 'framework'),
            'type' => 'url',
           
        ),
		// ADDITIONAL VIDEO MP4
        array(
            'name' => __('Additional Sermon Video .mp4', 'framework'),
            'id' => $prefix . 'sermons_add_video_mp4',
            'desc' => __("This is mandatory for custom video. MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
		// ADDITIONAL VIDEO WebM
        array(
            'name' => __('Additional Sermon Video .webm', 'framework'),
            'id' => $prefix . 'sermons_add_video_webm',
            'desc' => __("WebM/VP8 for Firefox4, Opera, and Chrome", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
		// ADDITIONAL VIDEO OGV
        array(
            'name' => __('Additional Sermon Video .ogv', 'framework'),
            'id' => $prefix . 'sermons_add_video_ogv',
            'desc' => __("Ogg/Vorbis for older Firefox and Opera versions", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
		// ADDITIONAL VIDEO POSTER
        array(
            'name' => __('Additional Sermon Video Poster Image', 'framework'),
            'id' => $prefix . 'sermons_add_video_poster',
            'desc' => __("An image which will appear as a poster prior to video play start.", 'framework'),
            'type' => 'file_input',
            'max_file_uploads' => 1
        ),
        
      )
);
/* Sermon Podcast Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'sermons_podcast',
    'title' => __('Sermon Podcast', 'framework'),
    'pages' => array('sermons'),
    'fields' => array(
         //Podcast Audio Length
        array(
            'name' => __('Sermon audio length', 'framework'),
            'id' => $prefix . 'sermon_duration',
            'desc' => __("Enter audio length in format hh:mm:ss", 'framework'),
            'type' => 'text'
        ),
         //Podcast Audio File Size
        array(
            'name' => __('Sermon audio file size', 'framework'),
            'id' => $prefix . 'sermon_size',
            'desc' => __("Enter file size for the uploaded audio file.", 'framework'),
            'type' => 'text'
        ),
         //Podcast Desciption
        array(
            'name' => __('Sermon short description', 'framework'),
            'id' => $prefix . 'sermons_podcast_description',
            'desc' => __("Enter short and sweet description for this sermon to show at podcast players.", 'framework'),
            'type' => 'textarea'
        ),
      )
);
/* * **Contact Page Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-contact1',
    'title' => __('Email & Subject', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-contact.php' ),
	), 
    'show_names' => true,
    'fields' => array(
            //Email
        array(
            'name' => __('Email', 'framework'),
            'id' => $prefix . 'contact_email',
            'desc' => __("Enter email address to use in contact form. By default admin email will be used.", 'framework'),
            'type' => 'text',
            'std' => get_option('admin_email')
        ),
        //Subject
        array(
            'name' => __('Subject', 'framework'),
            'id' => $prefix . 'contact_subject',
            'desc' => __("Enter subject to use in contact page.", 'framework'),
            'type' => 'textarea',
        ),
    )
);
/* * **Contact Page Meta Box 2 *** */
$meta_boxes[] = array(
    'id' => 'template-contact2',
    'title' => __('Map Box', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-contact.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        //Our Location Text
        array(
            'name' => __('Our Location Text', 'framework'),
            'id' => $prefix . 'our_location_text',
            'desc' => __("Enter the our location text to display on contact page.", 'framework'),
            'type' => 'text',
        ),
        //Map Display
        array(
            'name' => __('Map Display', 'framework'),
            'id' => $prefix . 'contact_map_display',
            'desc' => __("Display Map?", 'framework'),
            'type' => 'select',
            'options' => array(
                'no' => __('No', 'framework'),
                'yes' => __('Yes', 'framework'),
            ),
        ),
        //Map Box Code
        array(
            'name' => __('Map Box Code', 'framework'),
            'id' => $prefix . 'contact_map_box_code',
            'desc' => __("Enter the map iframe embed code to display on contact page. You can get your embed code from http://maps.google.com/", 'framework'),
            'type' => 'textarea',
        ),
    )
);
/* * **Home Page Meta Box1 *** */
/*
$meta_boxes[] = array(
    'id' => 'template-home1',
    'title' => __('Slider Metabox', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-home.php','template-home-pb.php','template-h-third.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'id' => $prefix . 'custom_homepage_message',
            'std' => __('<p style="background-color:red; color:#fff; padding:5px 20px">If you are setting this page as your front page at Settings > Reading then use this metabox options for the page header slider instead of page/post header options.</p>', 'framework'),
            'type' => 'custom_html',
		),
        array(
            'name' => __('Choose slider', 'framework'),
            'id' => $prefix . 'Choose_slider_display',
            'desc' => __("Select slider type for your homepage.", 'framework'),
            'type' => 'select',
            'options' => array(
                '0' => __('Flex Slider', 'framework'),
                '1' => __('Revolution Slider', 'framework'),
            ),
        ),
        array(
		   'name' => __("Select Revolution Slider from list","framework"),
			'id' => $prefix . 'select_revolution_from_list',
			'desc' => __("Select Revolution Slider from the list", 'framework'),
			'type' => 'select',
			'options' => RevSliderShortCode(),
		),
        //Slider Image
        array(
            'name' => __('Slider Image', 'framework'),
            'id' => $prefix . 'slider_image',
            'desc' => __("Choose/Upload slider images.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Speed', 'framework'),
            'id' => $prefix . 'slider_speed',
            'desc' => __("Default slider speed is 5000. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
            'type' => 'text',
        ),
		array(
            'name' => __('Slider Pagination', 'framework'),
            'id' => $prefix . 'slider_pagination',
            'desc' => __("Enable to show pagination for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Enable', 'framework'),
                'no' => __('Disable', 'framework'),
            ),
				'std' => 'yes',
        ),
		array(
            'name' => __('Slider Auto Slide', 'framework'),
            'id' => $prefix . 'slider_auto_slide',
            'desc' => __("Select Yes to slide automatically.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Direction Arrows', 'framework'),
            'id' => $prefix . 'slider_direction_arrows',
            'desc' => __("Select Yes to show slider direction arrows.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'slider_effects',
            'desc' => __("Select effect for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'fade' => __('Fade', 'framework'),
                'slide' => __('Slide', 'framework'),
            ),
        ),
        ));
*/        
/* * **Home Second Meta Box1 *** */
$meta_boxes[] = array(
    'id' => 'template-h-second-1',
    'title' => __('Categories Area', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-h-second.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for categories area', 'framework'),
            'id' => $prefix . 'switch_categories_post',
            'desc' => __("Select enable or disable to show/hide categories posts area.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
				'std' => '1',
        ),
        array(
            'name' => __('Category show on home page', 'framework'),
            'id' => $prefix . 'category_to_show_on_home',
            'desc' => __("Choose Category to show  on Home page", 'framework'),
            'clone' => true,
            //'clone-group' => 'imic-clone-group',
            'type' => 'select',
            'options' => imic_get_cat_list()
        ),
        array(
            'name' => __('Number of Post', 'framework'),
            'id' => $prefix . 'number_of_post_cat',
            'desc' => __("Enter Number of Post", 'framework'),
            'type' => 'text',
            'std' => '',
            'clone' => true,
            //'clone-group' => 'imic-clone-group',
        ),
    ),
);
/* * **Home Page Meta Box6 *** */
$meta_boxes[] = array(
    'id' => 'template-home6',
    'title' => __('Select option for Area Under Banner', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-home.php','template-home-pb.php','template-h-third.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for section under banner', 'framework'),
            'id' => $prefix . 'upcoming_area',
            'desc' => __("Select enable or disable to show/hide Event/Sermon under banner.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
			'std' => '1',
        ),
        array(
            'name' => __('Recent Event/Sermon', 'framework'),
            'id' => $prefix . 'latest_sermon_events_to_show_on',
            'desc' => __("Choose latest item to show under banner", 'framework'),
            'type' => 'select',
            'options' => array(
                'latest_event' => __('Latest event', 'framework'),
                'latest_sermon' => __('Latest Sermon', 'framework'),
				'text' => __('Custom message', 'framework'),
            ),
        ),
		array(
            'name' => __('Custom Text Message', 'framework'),
            'id' => $prefix . 'custom_text_message',
            'desc' => __("Enter custom message, this field accept shortcodes as well.", 'framework'),
            'type' => 'textarea',
        ),
        array(
        'name'    => __( 'Event Category', 'framework' ),
        'id'      => $prefix . 'advanced_event_taxonomy',
        'desc' => __("Choose event category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'multiple' =>true,
            ),
        array(
            'name' => __('Switch for Going on events', 'framework'),
            'id' => $prefix . 'going_on_events',
            'desc' => __("Select enable or disable to show/hide Going On Events under slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Disable', 'framework'),
                '2' => __('Enable', 'framework'),
            ),
			'std' => '1',
        ),
        	array(
            'name' => __('Custom Going On Events Title', 'framework'),
            'id' => $prefix . 'custom_going_on_events_title',
            'desc' => __("Enter Going On Events title.", 'framework'),
            'type' => 'text',
        ),
            array(
        'name'    => __( 'Sermons Category', 'framework' ),
        'id'      => $prefix . 'advanced_sermons_category',
        'desc' => __("Choose sermon category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'sermons-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'multiple' =>true,
            ),
        array(
            'name' => __('All Event Button Url', 'framework'),
            'id' => $prefix . 'all_event_url',
            'desc' => __("Enter Event button URL", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        /*
        array(
            'name' => __('All Sermon Button Url', 'framework'),
            'id' => $prefix . 'all_sermon_url',
            'desc' => __("Enter Sermon button URL", 'framework'),
            'type' => 'text',
            'std' => ''
        ),*/
        
    ));

/** Home Featured Article  **/
$meta_boxes[] = array(
    'id' => 'template-home4',
    'title' => __('Settings and Featured Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php' ),
    ),
    'show_names' => true,
    
    'fields' => array(
        array(
            'name' => __('Online Giving', 'framework'),
            'id' => $prefix . 'online_giving_method',
            'desc' => __("Select method for online giving.", 'framework'),
            'type' => 'select',
            'options' => array(
                'gci' => __('via GCI Home Office', 'framework'),
                'modal' => __('GivingFuel modal overlay', 'framework'),
                'other' => __('Other', 'framework'),
                'disable' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        
        array(
            'name' => __('Online Giving GCI Church ID', 'framework'),
            'id' => $prefix . 'online_giving_gci_church_id',
            'desc' => __("GCI Local Church ID", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('URL for online giving', 'framework'),
            'id' => $prefix . 'online_giving_other_url',
            'desc' => __("URL for online giving", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Show featured article', 'framework'),
            'id' => $prefix . 'featured_article',
            'desc' => __("Select enable or disable to show/hide featured article.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name' => __('Featured Article to show on home page', 'framework'),
            'id' => $prefix . 'home_featured_article',
            'desc' => __("Enter the Posts/Page ID or IDs (comma separated) to show featured article on Home page. example - 1,2,3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('Show featured media/sermon', 'framework'),
            'id' => $prefix . 'featured_media',
            'desc' => __("Select enable or disable to show/hide featured media or sermon.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name' => __('Featured Sermon or Media to show on home page.', 'framework'),
            'id' => $prefix . 'home_featured_media',
            'desc' => __("Enter the Posts ID to show featured sermon/media on Home page. example - 1,2,3 or keyword leave blank to make latest sermon as featured media", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('Additional Head Code', 'framework'),
            'id' => $prefix . 'additional_head_code',
            'desc' => __("Additional head code such as google analytics javascript", 'framework'),
            'type' => 'textarea',
        ),
    ));


/* * **Home Page Meta Box7 *** */
$meta_boxes[] = array(
    'id' => 'template-home7',
    'title' => __('Upcoming Events Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php','template-h-third.php' ),
    ),
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Switch for upcoming events', 'framework'),
            'id' => $prefix . 'imic_upcoming_events',
            'desc' => __("Select enable or disable to show/hide upcoming events.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name'    => __( 'Event Category', 'framework' ),
            'id'      => $prefix . 'upcoming_event_taxonomy',
            'desc' => __("Choose event category", 'framework'),
            'type'    => 'taxonomy_advanced',
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
            ),
            'multiple' =>true,
        ),
        //Custom More Upcoming Events Title
        array(
            'name' => __('Custom More Upcoming Events Title', 'framework'),
            'id' => $prefix . 'custom_upcoming_events_title',
            'desc' => __("Enter more upcoming events title.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Number of Events to show on home page', 'framework'),
            'id' => $prefix . 'events_to_show_on',
            'desc' => __("Enter the number of events to show on home page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
    ));

/* * **Home Page Meta Box4 *** */
/*
$meta_boxes[] = array(
    'id' => 'template-home4',
    'title' => __('Featured Blocks Area', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-home.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for featured blocks', 'framework'),
            'id' => $prefix . 'imic_featured_blocks',
            'desc' => __("Select enable or disable to show/hide featured blocks.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
			'std' => '1',
        ),
        array(
            'name' => __('Featured Blocks to show on home page', 'framework'),
            'id' => $prefix . 'home_featured_blocks',
            'desc' => __("Enter the Posts/Pages comma separated ID to show featured blocks on Home page. example - 1,2,3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
		array(
            'name' => __('Title for featured blocks', 'framework'),
            'id' => $prefix . 'home_row_featured_blocks',
            'desc' => __("Enter the title for featured blocks. Add more as per the entered page IDs", 'framework'),
            'type' => 'text',
			'clone' => true,
            'std' => ''
        ),
        array(
            'name' => __('Title for first featured block', 'framework'),
            'id' => $prefix . 'home_featured_blocks1',
            'desc' => __("Enter the title for first featured block area", 'framework'),
            'type' => 'hidden',
            'std' => ''
        ),
         array(
            'name' => __('Title for second featured block', 'framework'),
            'id' => $prefix . 'home_featured_blocks2',
            'desc' => __("Enter the title for second featured block area", 'framework'),
            'type' => 'hidden',
            'std' => ''
        ),
         array(
            'name' => __('Title for third featured block', 'framework'),
            'id' => $prefix .'home_featured_blocks3',
            'desc' => __("Enter the title for third featured block area", 'framework'),
            'type' => 'hidden',
            'std' => ''
        ),
        ));
*/

/* * **Home Page Meta Box7 *** */
$meta_boxes[] = array(
    'id' => 'template-home7',
   'title' => __('Upcoming Events Area', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-home.php','template-h-third.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for upcoming events', 'framework'),
            'id' => $prefix . 'imic_upcoming_events',
            'desc' => __("Select enable or disable to show/hide upcoming events.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
			'std' => '1',
        ),
		array(
        'name'    => __( 'Event Category', 'framework' ),
        'id'      => $prefix . 'upcoming_event_taxonomy',
        'desc' => __("Choose event category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'multiple' =>true,
            ),
		//Custom More Upcoming Events Title
		/*
		array(
            'name' => __('Custom More Upcoming Events Title', 'framework'),
            'id' => $prefix . 'custom_upcoming_events_title',
            'desc' => __("Enter more upcoming events title.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Number of Events to show on home page', 'framework'),
            'id' => $prefix . 'events_to_show_on',
            'desc' => __("Enter the number of events to show on home page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        */
        ));


/** Settings for News and Events **/
/* * **Home Page Meta Box5 *** */
$meta_boxes[] = array(
    'id' => 'template-home5',
    'title' => __('Recent News Posts', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php','template-h-third.php' ),
    ),
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Switch for recent news posts.', 'framework'),
            'id' => $prefix . 'imic_recent_posts',
            'desc' => __("Select enable or disable to show/hide news posts.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name'    => __( 'Post Category', 'framework' ),
            'id'      => $prefix . 'recent_post_taxonomy',
            'desc' => __("Choose post category", 'framework'),
            'type'    => 'taxonomy_advanced',
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
            ),
            'std' => '',
            'multiple' =>true,
        ),
        array(
            'name' => __('Number of Recent Posts to show on home page.', 'framework'),
            'id' => $prefix . 'posts_to_show_on',
            'desc' => __("Enter the number of recent posts to show on home page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        /*
         array(
         'name' => __('Show read more button', 'framework'),
         'id' => $prefix . 'recent_posts_rmbutton',
         'desc' => __("Show read more button for each recent post?", 'framework'),
         'type' => 'select',
         'options' => array(
         '1' => __('Yes', 'framework'),
         '2' => __('No', 'framework'),
         ),
         'std' => '2',
         ),
         array(
         'name' => __('Custom read more button text', 'framework'),
         'id' => $prefix . 'recent_posts_rmbutton_text',
         'desc' => __("Enter button text for read more button", 'framework'),
         'type' => 'text',
         'std' => ''
         ), */
    ));
/* * **Home Page Meta Box8 Media *** */

$meta_boxes[] = array(
    'id' => 'template-home8',
    'title' => __('Media and Sermons Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php','template-h-third.php' ),
    ),
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Show media list area (SPOL and sermons)', 'framework'),
            'id' => $prefix . 'imic_recent_media_area',
            'desc' => __("Select enable or disable to show/hide media list.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name' => __('Number of Media items to show on home page', 'framework'),
            'id' => $prefix . 'media_to_show_on',
            'desc' => __("Enter the number of media items to show on home page. Example: 9", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('URL of the page for SPOL', 'framework'),
            'id' => $prefix . 'media_spol_page_url',
            'desc' => __("Enter the page of for SPOL. Example (/spol). Default: Youtube.com", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
    ));


/** end of media **/
$meta_boxes[] = array(
    'id' => 'template-home7b',
    'title' => __('Featured Events Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php','template-h-third.php' ),
    ),
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Show upcoming featured events', 'framework'),
            'id' => $prefix . 'imic_recent_events_area',
            'desc' => __("Select enable or disable to show/hide events list.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name'    => __( 'Events Category', 'framework' ),
            'id'      => $prefix . 'recent_events_taxonomy',
            'desc' => __("Choose post category", 'framework'),
            'type'    => 'taxonomy_advanced',
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
            ),
            'std' => '',
            'multiple' =>true,
        ),
        array(
            'name' => __('Number of Events to show on home page', 'framework'),
            'id' => $prefix . 'events_to_show_on',
            'desc' => __("Enter the number of events to show on home page. Example: 9", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('URL of the page for more events', 'framework'),
            'id' => $prefix . 'events_page_url',
            'desc' => __("Enter the page of for all events. Example (/events).", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
    ));


/* * **Home Page Meta Box5 *** */
$meta_boxes[] = array(
    'id' => 'template-home-articles',
    'title' => __('Recent Articles', 'framework'),
    'pages' => array('page'),
    'show' => array(
        // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
        'relation' => 'OR',
        // List of page templates (used for page only). Array. Optional.
        'template' => array( 'template-home.php','template-h-third.php' ),
    ),
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Switch for recent articles.', 'framework'),
            'id' => $prefix . 'imic_recent_articles_area',
            'desc' => __("Select enable or disable to show/hide articles.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
            'std' => '1',
        ),
        array(
            'name'    => __( 'Post Category', 'framework' ),
            'id'      => $prefix . 'recent_articles_taxonomy',
            'desc' => __("Choose post category", 'framework'),
            'type'    => 'taxonomy_advanced',
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
            ),
            'std' => '',
            'multiple' =>true,
        ),
        array(
            'name' => __('Number of Recent Articles to show on home page.', 'framework'),
            'id' => $prefix . 'post_artices_to_show_on',
            'desc' => __("Enter the number of recent articles to show on home page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        /*
        array(
            'name' => __('Show read more button', 'framework'),
            'id' => $prefix . 'recent_articles_rmbutton',
            'desc' => __("Show read more button for each recent articles?", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Yes', 'framework'),
                '2' => __('No', 'framework'),
            ),
            'std' => '2',
        ),
        array(
            'name' => __('Custom read more button text', 'framework'),
            'id' => $prefix . 'recent_posts_rmbutton_text',
            'desc' => __("Enter button text for read more button", 'framework'),
            'type' => 'text',
            'std' => ''
        ), */
    ));
/* * **Home Page Meta Box3 *** */
$meta_boxes[] = array(
    'id' => 'template-home3',
    'title' => __('Recent Galleries Area', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-home.php','template-h-third.php','template-h-second.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for gallery', 'framework'),
            'id' => $prefix . 'imic_galleries',
            'desc' => __("Select enable or disable to show/hide galleries.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
			'std' => '1',
        ),
		array(
        'name'    => __( 'Gallery Categories', 'framework' ),
        'id'      => $prefix . 'home_gallery_taxonomy',
        'desc' => __("Choose gallery category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'gallery-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
				'multiple' =>true,
            ),
		//Custom Gallery Title
        array(
            'name' => __('Custom Gallery Title', 'framework'),
            'id' => $prefix . 'custom_gallery_title',
            'desc' => __("Enter custom gallery title.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Custom More Galleries Title', 'framework'),
            'id' => $prefix . 'custom_more_galleries_title',
            'desc' => __("Enter custom more galleries title.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Custom More Galleries Url', 'framework'),
            'id' => $prefix . 'custom_more_galleries_url',
            'desc' => __("Enter custom more galleries URL.", 'framework'),
            'type' => 'url',
        ),
        array(
            'name' => __('Number of Galleries to show on home page', 'framework'),
            'id' => $prefix . 'galleries_to_show_on',
            'desc' => __("Enter the number of gallery posts to show on home page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => __('Upload Background Image', 'framework'),
            'id' => $prefix.'galleries_background_image',
            'desc' => __("Upload background image for the latest gallery section of home page.", 'framework'),
            'type' => 'image_advanced',
            'max_file_uploads' => 1
        ),
        ));
/* * **Home third Meta Box1 *** */
$meta_boxes[] = array(
    'id' => 'template-h-third-1',
    'title' => __('Latest Sermon Albums', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-h-third.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Switch for Sermon Albums', 'framework'),
            'id' => $prefix . 'switch_sermon_album',
            'desc' => __("Select enable or disable to show/hide sermon albums posts area.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('Enable', 'framework'),
                '2' => __('Disable', 'framework'),
            ),
			'std' => '1',
        ),
		
        array(
            'name' => __('Custom Latest Sermon Albums Title', 'framework'),
            'id' => $prefix . 'custom_albums_title',
            'desc' => __("Enter custom latest sermon albums title", 'framework'),
            'type' => 'text',
            'std' => '',
           ),
        array(
            'name' => __('Number of Sermon Albums', 'framework'),
            'id' => $prefix . 'number_of_sermon_albums',
            'desc' => __("Enter number of sermon albums to show", 'framework'),
            'type' => 'text',
            'std' => '',
           ),
        //Custom All Sermon Albums Url
	array(
            'name' => __('All Sermon Albums Url', 'framework'),
            'id' => $prefix . 'sermon_albums_url',
            'desc' => __("Enter sermon albums URL", 'framework'),
            'type' => 'text',
        ),
    ),
);
/* * ** Gallery  Pagination Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-gallery-pagination1',
    'title' => __('Gallery Metabox', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-gallery-pagination.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        //Number of Gallery to show
        array(
            'name' => __('Number of gallery items', 'framework'),
            'id' => $prefix . 'gallery_pagination_to_show_on',
            'desc' => __("Enter the number of gallery items to show on a page. For example: 6", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => __('Gallery Columns Layout', 'framework'),
            'id' => $prefix . 'gallery_pagination_columns_layout',
            'desc' => __("Enter the number of columns for layout to show on gallery page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => __('Show gallery items title', 'framework'),
            'id' => $prefix . 'show_gallery_title',
            'desc' => __("Select enable if you need to show gallery posts title.", 'framework'),
            'type' => 'select',
            'options' => array(
        		'0' => __('Disable', 'framework'),
                '1' => __('Enable','framework'),
            ),
            'std' => 0,
        )
    )
);
/* * ** Gallery Masonry Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-gallery-masonry1',
    'title' => __('Gallery Metabox', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-gallery-masonry.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        //Number of Gallery to show
        array(
            'name' => __('Number of gallery items', 'framework'),
            'id' => $prefix . 'gallery_masonry_to_show_on',
            'desc' => __("Enter the number of gallery items to show on a page. For example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => __('Show gallery items title', 'framework'),
            'id' => $prefix . 'show_gallery_title_masonry',
            'desc' => __("Select enable if you need to show gallery items title.", 'framework'),
            'type' => 'select',
            'options' => array(
        		'0' => __('Disable', 'framework'),
                '1' => __('Enable','framework'),
            ),
            'std' => 0,
        )
       )
);
/* * ** Gallery  Filter Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-gallery-filter1',
    'title' => __('Gallery Metabox', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-gallery-filter.php' ),
	), 
    'show_names' => true,
    'fields' => array(
       array(
            'name' => __('Gallery Columns Layout', 'framework'),
            'id' => $prefix . 'gallery_filter_columns_layout',
            'desc' => __("Enter the number of columns for Layout to show on gallery filter page. Example: 3", 'framework'),
            'type' => 'select',
            'options' => array(
        		2 => __('2 Columns', 'framework'),
        		3 => __('3 Columns', 'framework'),
                4 => __('4 Columns','framework'),
                6 => __('6 Columns','framework'),
            ),
            'std' => 3
        ),
         array(
            'name' => __('Show gallery items title', 'framework'),
            'id' => $prefix . 'show_gallery_title_filter',
            'desc' => __("Select enable if you need to show gallery items title.", 'framework'),
            'type' => 'select',
            'options' => array(
        		'0' => __('Disable', 'framework'),
                '1' => __('Enable','framework'),
            ),
            'std' => 0,
        )
    )
);
/* * ** Gallery  Category Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'gallery-taxonomies',
    'title' => __('Gallery Categories', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-gallery-filter.php','template-gallery-masonry.php','template-gallery-pagination.php'),
	), 
    'show_names' => true,
    'fields' => array(
		array(
        'name'    => __( 'Gallery Category', 'framework' ),
        'id'      => $prefix . 'advanced_gallery_taxonomy',
        'desc' => __("Choose gallery category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'gallery-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
				'multiple' =>true,
            ),
			
    )
);
/* * ** Event  Category Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'events-taxonomies',
    'title' => __('Events Categories', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-event-category.php','template-events_grid.php','template-events-timeline.php','template-events.php' ),
	), 
    'show_names' => true,
    'fields' => array(
		array(
        'name'    => __( 'Event Category', 'framework' ),
        'id'      => $prefix . 'advanced_event_list_taxonomy',
        'desc' => __("Choose event category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
			'std' => '',
			'multiple' =>true,
            ),
    )
);
/* * ** Post  Category Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'post-taxonomies',
    'title' => __('Post Categories', 'framework'),
    'pages' => array('page'),
	'show' => array(
		// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
		'relation' => 'OR',
		// List of page templates (used for page only). Array. Optional.
		'template' => array( 'template-blog-full-width.php','template-blog-masonry.php','template-blog-medium-thumbnails.php','template-blog-timeline.php'),
	), 
    'show_names' => true,
    'fields' => array(
		array(
        'name'    => __( 'Post Category', 'framework' ),
        'id'      => $prefix . 'advanced_post_taxonomy',
        'desc' => __("Choose post category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
				'multiple' =>true,
				
            ),
    )
);
/* * ** Post  Category Meta Box 1 *** */
/*
$meta_boxes[] = array(
    'id' => 'post-taxonomies-blog',
    'title' => __('Blog Post Categories', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
		array(
            'id' => $prefix . 'custom_cats_blog_message',
            'std' => __('<p style="background-color:red; color:#fff; padding:5px 20px">If you are setting this page as your posts page at Settings > Reading then use this metabox options for the post categories.</p>', 'framework'),
            'type' => 'custom_html',
		),
		array(
			'name'    => __( 'Post Category', 'framework' ),
			'id'      => $prefix . 'advanced_blog_taxonomy',
			'desc' => __("Choose post category/categories", 'framework'),
			'type'    => 'taxonomy_advanced',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'category',
				'type' => 'select',
				// Additional arguments for get_terms() function. Optional
				'args' => array('orderby' => 'count', 'hide_empty' => true)
			),
			'std' => '',
			'multiple' =>true,
		),
    )
);
*/
/* * ** Staff Page Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-staff1',
    'title' => __('Staff to show', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-staff.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        //Number of Staff to show
        array(
            'name' => __('Number of Staff to show', 'framework'),
            'id' => $prefix . 'staff_to_show_on',
            'desc' => __("Enter the number of staff posts to show on staff page. Example: 3", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
		array(
        'name'    => __( 'Staff Category', 'framework' ),
        'id'      => $prefix . 'advanced_staff_taxonomy',
        'desc' => __("Choose staff category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'staff-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'multiple' =>true,
        ),
        array(
            'name' => __('Select orderby', 'framework'),
            'id' => $prefix . 'staff_select_orderby',
            'desc' => __("Select staff orderby.", 'framework'),
            'type' => 'select',
            'options' => array(
                'ID' => __('ID', 'framework'),
                'menu_order' => __('Menu Order', 'framework'),
            ),
        ),
        array(
            'name' => __('Length of Excerpt to show', 'framework'),
            'id' => $prefix . 'staff_excerpt_length',
            'desc' => __("Enter the number of words you would like to show from the staff posts content/excerpt. Enter 0 to completely hide the excerpt and read more button", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
       )
);

/* * ** Events Timeline Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-events-timeline',
    'title' => __('Event Timeline View', 'framework'),
    'pages' => array('page'),
    'show' => array(
		// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
		'relation' => 'OR',
		// List of page templates (used for page only). Array. Optional.
		'template' => array( 'template-events-timeline.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        array(
            'name' => __('Event type', 'framework'),
            'id' => $prefix . 'events_timeline_view',
            'desc' => __("Select events to show in timeline", 'framework'),
            'type' => 'select',
            'options' => array(
                'future' => __('Future', 'framework'),
                'past' => __('Past', 'framework'),
            ),
        ),
    )
);
/* * ** Blog Masonry Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-blog-masonry',
    'title' => __('Blog Masonry Metabox', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-blog-masonry.php','template-blog-medium-thumbnails.php' ),
	), 
    'show_names' => true,
    'fields' => array(
         array(
            'name' => __('What should thumbnails do?', 'framework'),
            'id' => $prefix . 'blog_masonry_thumbnails',
            'desc' => __("Select what you like for your blog thumbnails - Open in lightbox or redirect to single post page.", 'framework'),
            'type' => 'select',
            'options' => array(
        		'0' => __('Lightbox', 'framework'),
                '1' => __('Single Post','framework'),
            ),
            'std' => 0,
        )
    )
);
/* * ** Sermon Albums Template Meta Box 1 *** */
$meta_boxes[] = array(
    'id' => 'template-sermons-albums1',
    'title' => __('Show Sermon Categories/Albums', 'framework'),
    'pages' => array('page'),
	'show' => array(
	// With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
	'relation' => 'OR',
	// List of page templates (used for page only). Array. Optional.
	'template' => array( 'template-sermons-albums.php' ),
	), 
    'show_names' => true,
    'fields' => array(
        //Sort albums by
        array(
            'name' => __('Select Orderby', 'framework'),
            'id' => $prefix . 'albums_select_orderby',
            'desc' => __("Select how you want to sort albums by. Default is by count", 'framework'),
            'type' => 'select',
            'options' => array(
                'count' => __('Count', 'framework'),
                'ID' => __('ID', 'framework'),
                'name' => __('Name', 'framework'),
                'slug' => __('Slug', 'framework'),
                'include' => __('Custom order', 'framework'),
            ),
			'std' => 'count',
        ),
        array(
            'name' => __('Select Order', 'framework'),
            'id' => $prefix . 'albums_select_order',
            'desc' => __("Select the order of list. Default is by ASC", 'framework'),
            'type' => 'select',
            'options' => array(
                'ASC' => __('Ascending', 'framework'),
                'DESC' => __('Descending', 'framework'),
            ),
			'std' => 'ASC',
        ),
        array(
            'name' => __('Sermon Categories', 'framework'),
            'id' => $prefix . 'sermon_categories_custom_order',
            'desc' => __("Add categories to show them in given order.", 'framework'),
            'clone' => true,
            //'clone-group' => 'imic-clone-group',
            'type' => 'select',
            'options' => $sermons_cats,
        ),
       )
);
return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'prefix_register_meta_boxes' );
?>
