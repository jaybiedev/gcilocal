<?php
load_theme_textdomain('imic-framework-admin', IMIC_FILEPATH . '/language/');
// Create TinyMCE's editor button & plugin for IMIC Framework Shortcodes
add_action('init', 'imic_sc_button');
function imic_sc_button() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'imic_add_tinymce_plugin');
        add_filter('mce_buttons', 'imic_register_shortcode_button');
    }
}
function imic_register_shortcode_button($button) {
    array_push($button, 'separator', 'imicframework_shortcodes');
    return $button;
}
function imic_add_tinymce_plugin($plugins) {
    $plugins['imicframework_shortcodes'] = get_template_directory_uri() . '/imic-framework/imic-shortcodes/imic-tinymce.editor.plugin.js';
    return $plugins;
}
/* ==================================================
  SHORTCODES OUTPUT
  ================================================== */
/* BUTTON SHORTCODE
  ================================================== */
function imic_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "colour" => "",
        "type" => "",
        "link" => "#",
        "target" => '_self',
        "size" => '',
        "extraclass" => ''
                    ), $atts));
    $button_output = "";
    $button_class = 'btn ' . $colour . ' ' . $extraclass . ' ' . $size;
    $buttonType = ($type == 'disabled') ? 'disabled="disabled"' : '';
    $button_output .= '<a class="' . $button_class . '" href="' . $link . '" target="' . $target . '" ' . $buttonType . '>' . do_shortcode($content) . '</a>';
    return $button_output;
}
add_shortcode('imic_button', 'imic_button');
/* ICON SHORTCODE
  ================================================== */
function imic_icon($atts, $content = null) {
    extract(shortcode_atts(array(
        "image" => ""
                    ), $atts));
    return '<i class="fa ' . $image . '"></i>';
}
add_shortcode('icon', 'imic_icon');
/* STAFF SHORTCODE
  ================================================== */
function imic_staff($atts, $content = null) {
    extract(shortcode_atts(array(
        "number" => "",
        "order" => "",
        "category" => "",
        "column" => "",
        "excerpt_length" => ""
                    ), $atts));
    $output = '';
   if ($order == "no") {
        $orderby = "ID";
        $sort_order = "DESC";
    } else {
        $orderby = "menu_order";
        $sort_order = "ASC";
    }
	if($excerpt_length == ''){
		$excerpt_length = 30;
	}
	if($column == 3){
		  $column = 4;	
	  }elseif($column == 4){
		  $column = 3;	
	  }elseif($column == 2){
		  $column = 6;	
	  }elseif($column == 1){
		  $column = 12;	
	  }else{
		  $column = 4;
	  }
    query_posts(array(
        'post_type' => 'staff',
        'staff-category' => $category,
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $sort_order,
    ));
    if (have_posts()):
	$output .='<div class="row">';
        while (have_posts()):the_post();
            $custom = get_post_custom(get_the_ID());
            $output .='<div class="col-md-' . $column . ' col-sm-' . $column . '">
                    <div class="grid-item staff-item"> 
                        <div class="grid-item-inner">';
            if (has_post_thumbnail()):
                $output .='<div class="media-box"><a href="' . get_permalink(get_the_ID()) . '">';
                $output .= get_the_post_thumbnail(get_the_ID(), 'full');
                $output .= '</a></div>';
            endif;
            $job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
            $job = '';
            if (!empty($job_title)) {
                $job = '<div class="meta-data">' . $job_title . '</div>';
            }
            $output .= '<div class="grid-content">
                                <h3> <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h3>';
            $output .= $job;
             $output .= imic_social_staff_icon(); $excerpt_length;
            $description = imic_excerpt($excerpt_length);
			if($excerpt_length != 0){
				if (!empty($description)) {
					$output .= $description;
				}
			}
			global $imic_options;
			if($excerpt_length != 0){
				$staff_read_more_text = $imic_options['staff_read_more_text'];
				if ($imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '0') {
					$output .='<p><a href="' . get_permalink() . '" class="btn btn-default">' . $staff_read_more_text . '</a></p>';
				} elseif ($imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '1') {
					$output .='<p><a href="' . get_permalink() . '">' . $staff_read_more_text . '</a></p>';
				}
			}
            $output .='</div></div>
                    </div>
                </div>';
        endwhile;
	$output .='</div>';
    endif;
    wp_reset_query();
    return $output;
}
add_shortcode('staff', 'imic_staff');
/* Sermon SHORTCODE
  ================================================== */
function imic_sermon($atts, $content = null) {
    extract(shortcode_atts(array(
        "number" => "",
        "title" => "",
        "category" => "",
        "column" => "",
                    ), $atts));
    $output = '';
    query_posts(array(
        'post_type' => 'sermons',
        'sermons-category' => $category,
        'posts_per_page' => $number,
        'orderby' => 'ID',
        'order' => 'DESC',
    ));
    if (have_posts()):
        $output .='<div class="col-md-' . $column . ' sermon-archive">';
    if(!empty($title)):
        $output .='<h2>'.$title.'</h2>';
    endif;
        ?>
        <!-- Sermons Listing -->
        <?php
        while (have_posts()):the_post();
            if ('' != get_the_post_thumbnail()) {
                $class = "col-md-8";
            } else {
                $class = "col-md-12";
            }
            $custom = get_post_custom(get_the_ID());
            $output .='<article class="post sermon">
                        <header class="post-title">';
            $output.='<div class="row">
      					<div class="col-md-9 col-sm-9">
            				<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .='<span class="meta-data"><i class="fa fa-calendar"></i>' . __('Posted on ', 'framework') . get_the_time(get_option('date_format'));
            $output .= get_the_term_list(get_the_ID(), 'sermons-speakers', ' | Pastor: ', ', ', '');
            $output .='</span>
                                         </div>';
            $output .='<div class="col-md-3 col-sm-3 sermon-actions">';
            if (!empty($custom['imic_sermons_url'][0])) {
                $output .='<a href="' . get_permalink() . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Video', 'framework') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
            }
            $attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
            if (!empty($attach_full_audio)) {
                $output .='<a href="' . get_permalink() . '/#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Audio', 'framework') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
            }
            $output .='<a href="' . get_permalink() . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Read online', 'framework') . '" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
            $attach_pdf = imic_sermon_attach_full_pdf(get_the_ID());
            if (!empty($attach_pdf)) {
                $output .='<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
            }
            $output .='</div>
                 	</div>';
            $output .='</header>
                        <div class="post-content">
                            <div class="row">';
            if (has_post_thumbnail()):
                $output.='<div class="col-md-4">
                                    <a href="' . get_permalink(get_the_ID()) . '" class="media-box">';
                $output .= get_the_post_thumbnail(get_the_ID(), 'full', array('class' => "img-thumbnail"));
                $output .= '</a></div>';
            endif;
            $output .= '<div class="' . $class . '">';
            $description = imic_excerpt(100);
            if (!empty($description)) {
                $output.= $description;
            }
            $output .= '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . __('Continue reading ', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>';
            $output .= '</div>
                            </div>
                        </div>
                    </article>';
        endwhile;
        $output.='</div>';
    endif;
    wp_reset_query();
    return $output;
}
add_shortcode('sermon', 'imic_sermon');
/* Event SHORTCODE
  ================================================== */
function imic_event($atts, $content = null) {
    extract(shortcode_atts(array(
        "number" => 10,
        "title" => "",
        "category" => "",
        "style" => "",
        "type" => "",
                    ), $atts));
    $output = '';
		$number = ($number=='')?10:$number;
if($type=='future')
{
	$future_events = imic_recur_events("future", "", $category, "");
	$google_events = getGoogleEvent();
	$events = $future_events+$google_events;
	ksort($events);
}
else
{
	$events = imic_recur_events("past", "", $category, "");
	krsort($events);
}
if($style=="list")
{
	$count = 1;
	$output .= '<div class="listing events-listing">
	<header class="listing-header">
            	<div class="row">
                	<div class="col-md-12 col-sm-12">
          				<h3>'.esc_attr($title).'</h3>
                  </div>
							</div>
							</header>';
		$output .= '<section class="listing-cont">
              <ul>';
	if(!empty($events))
	{
	foreach($events as $key=>$value)
	{
		if(preg_match('/^[0-9]+$/',$value))
		{
			$eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
			$eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
			$eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
			$eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
			$evstendtime = $eventStartTime.'|'.$eventEndTime;
			$evstenddate = $eventStartDate.'|'.$eventEndDate;
			$date_converted = date('Y-m-d',$key );
			$custom_event_url = imic_query_arg($date_converted,$value);
			$event_dt_out = imic_get_event_timeformate( $evstendtime,$evstenddate,$value,$key);
			$event_dt_out = explode('BR',$event_dt_out);
			if($eventStartTime!='') 
			{ 
				$eventStartTime = date(get_option('time_format'),$eventStartTime);
			}
			$custom_event_url = imic_query_arg($date_converted,$value);
			$event_title=get_the_title($value);
			$stime = ''; 
			if($eventStartTime!='') 
			{ 
				$stime = ' | '.$eventStartTime;
			}
		}
		else
		{
			$google_data =(explode('!',$value));
			$event_title=$google_data[0];
			$custom_event_url=$google_data[1];
			$options = get_option('imic_options');
    	
			$eventTime =$key;
			if($eventTime!='') 
			{ 
				$eventTime = date_i18n( get_option( 'time_format' ),$key); 
			}
			$eventEndTime =$google_data[2];
			if($eventEndTime!='')
			{
				$eventEndTime = ' - '.date_i18n( get_option( 'time_format' ),strtotime($eventEndTime));
			}
			$eventAddress=$google_data[3];
			$event_dt_out = imic_get_event_timeformate($key.'|'.strtotime($google_data[2]),$key.'|'.$key,$value,$key);
			$event_dt_out = explode('BR',$event_dt_out);
		}
		$output .= '<li class="item event-item">	
             			<div class="event-date"> 
										<span class="date">'.date_i18n('d',$key).'</span>
                       <span class="month">'.imic_global_month_name($key).'</span>
									</div>
                	<div class="event-detail">
            				<h4>
                			<a href="'.$custom_event_url.'">
					   '.$event_title.' </a>'.imicRecurrenceIcon($value).'
                 		</h4>
                  	<span class="event-dayntime meta-data">
					   				'.$event_dt_out[1].',&nbsp;&nbsp;'.$event_dt_out[0]
                     .'</span> 
									</div>
                	<div class="to-event-url">
              	<div>
								<a href="'.$custom_event_url.'" class="btn btn-default btn-sm">'.__('Details','framework').'</a></div>
                      </div>
                    </li>';
		if($count++>=$number)
		{
			break;
		}
	}
	}
	$output .= '</ul>
				</section></div>';
}
else
{
	$output .= '<header class="listing-header">
            	<div class="row">
                	<div class="col-md-12 col-sm-12">
          				<h3>'.esc_attr($title).'</h3>
                  </div>
							</div>
							</header>';
	$output .= '<div class="container"><div class="row">';
	$output .= '<ul class="grid-holder col-3 events-grid">';
		
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$count = 1;
	$grid_item = 1;
	$perPage = get_option('posts_per_page');
	$paginate = 1;
	if($paged>1) 
	{
		$paginate = ($paged-1)*$perPage; $paginate = $paginate+1; 
	}
	$TotalEvents = count($events);
	if($TotalEvents%$perPage==0) 
	{
		$TotalPages = $TotalEvents/$perPage;
	}
	else 
	{
		$TotalPages = $TotalEvents/$perPage;
		$TotalPages = $TotalPages+1;
	}
	foreach($events as $key=>$value)
	{
		if(preg_match('/^[0-9]+$/',$value))
		{
			$google_flag =1;
		}
		else
		{
			$google_flag =2;
		}
		if($google_flag==1)
		{
			setup_postdata(get_post($value));
			$eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
			$eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
			$eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
			$eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
			$event_dt_out = imic_get_event_timeformate($eventStartTime.'|'.$eventEndTime,$eventStartDate.'|'.$eventEndDate,$value,$key);
			$event_dt_out = explode('BR',$event_dt_out);
			$registration_status = get_post_meta($value,'imic_event_registration_status',true);
			/** Event Details Manage **/
			if($registration_status==1&&(function_exists('imic_get_currency_symbol'))) 
			{
				$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker','fa-money');	
			}
			else 
			{
				$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker'); 
			}
			$stime = ""; $etime = "";
			if($eventStartTime!='') 
			{ 
				$stime = ' | ' .date_i18n(get_option('time_format'), $eventStartTime) ; 
			}
			if($eventEndTime!='') 
			{ 
				$etime =  ' - '. date_i18n(get_option('time_format'),$eventEndTime); 
			}
		if($registration_status==1&&(function_exists('imic_get_currency_symbol'))) 
		{
			$event_registration_fee = get_post_meta($value,'imic_event_registration_fee',true);
			$registration_charge = ($event_registration_fee=='')?'Free':imic_get_currency_symbol(get_option('paypal_currency_options')).get_post_meta($value,'imic_event_registration_fee',true);
			$eventDetailsData = array($event_dt_out[1],$event_dt_out[0], get_post_meta($value,'imic_event_address',true),$registration_charge);	
			/*
			$eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true),$registration_charge);
			*/
		}
		else 
		{
			/*$eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true));*/
			$eventDetailsData = array($event_dt_out[1],$event_dt_out[0], get_post_meta($value,'imic_event_address',true));
		}
		$eventValues = array_filter($eventDetailsData, 'strlen');
	}
	if($count==$paginate&&$grid_item<=$perPage) 
	{ 
		$paginate++; $grid_item++;
		if($google_flag==1)
		{
			$frequency = get_post_meta($value,'imic_event_frequency',true); 
		}
		//if ('' != get_the_post_thumbnail($value)) {
		$output .= '<li class="grid-item format-standard">';
		if($google_flag==1)
		{
			$date_converted=date('Y-m-d',$key );
			$custom_event_url =imic_query_arg($date_converted,$value); 
		}
		if($google_flag==2)
		{
    	$google_data =(explode('!',$value)); 
    	$event_title=$google_data[0];
    	$custom_event_url=$google_data[1];
    	$stime = ""; $etime = "";
   		$etime=$google_data[2];
     	if($key!='') 
			{ 
				$stime = ' | ' .date_i18n(get_option('time_format'), $key) ; 
			}
			if($etime!='') 
			{ 
				$etime =  ' - '. date_i18n(get_option('time_format'),strtotime($etime)); 
			}
      $eventAddress=$google_data[3];
     	/* $eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime,$eventAddress);*/ 
	  	$event_dt_out = imic_get_event_timeformate($key.'|'.$google_data[2],$key.'|'.$key,$value,$key);
      $event_dt_out = explode('BR',$event_dt_out);
			$eventDetailsData = array($event_dt_out[1],$event_dt_out[0],$eventAddress);
			$eventValues = array_filter($eventDetailsData, 'strlen');
			$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker'); 
		}
	$output .= '<div class="grid-item-inner">';
	if($google_flag==1)
	{
		$output .= '<a href="'.$custom_event_url.'" class="media-box">';
		$output .= get_the_post_thumbnail($value, 'full');
		$output .= '</a>';
		$event_title=get_the_title($value);
	}
	$output .= '<div class="grid-content">';
	$output .= '<h3><a href="' . $custom_event_url. '">'.$event_title.'</a>'.imicRecurrenceIcon($value).'</h3>';
	if($google_flag==1)
	{
		$output .= '<div class="page-content">';
		$output .= imic_excerpt(25);
		$output .= '</div>';
	}
	$output .= '</div>';
	if(!empty($eventValues))
	{ 
		$output .= '<ul class="info-table">';
		$flag = 0;
		foreach($eventDetailsData as $edata)
		{
			if(!empty($edata))
			{
				$output .= '<li><i class="fa '.$eventDetailIcons[$flag].'"></i> '.$edata.' </li>';
			}				
			$flag++;	
		}
		$output .= '</ul>';
		//}
		$output .= '</div>
		</li>';
 	}
} $count++;
	}
	$output .= '</ul></div></div>';
}
    return $output;
}
add_shortcode('event', 'imic_event');
/* IMAGE SHORTCODE
  ================================================== */
function imic_imagebanner($atts, $content = null) {
    extract(shortcode_atts(array(
        "image" => "",
        "width" => "",
        "height" => "",
        "extraclass" => ""
                    ), $atts));
    $imgWidth = (!empty($width)) ? 'width="' . $width . '"' : '';
    $imgHeight = (!empty($height)) ? ' height="' . $height . '"' : '';
    $image_banner = '';
    $image_banner .= '<div class="post-image">
			<figure class="post-thumbnail"><img src="' . $image . '" alt="" class="thumbnail" ' . $imgWidth . $imgHeight . '></figure>
	  	</div>';
    return $image_banner;
}
add_shortcode('imic_image', 'imic_imagebanner');
/* COLUMN SHORTCODES
  ================================================== */
function imic_one_full($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
                    ), $atts));
    $animation = (!empty($anim)) ? 'data-appear-animation="' . $anim . '"' : '';
    return '<div class="col-md-12 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('one_full', 'imic_one_full');
function imic_one_half($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
                    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    return '<div class="col-md-6 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('one_half', 'imic_one_half');
function imic_one_third($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => ''
                    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    return '<div class="col-md-4 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third', 'imic_one_third');
function imic_one_fourth($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => ''
                    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    return '<div class="col-md-3 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth', 'imic_one_fourth');
function imic_one_sixth($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => ''
                    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    return '<div class="col-md-2 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('one_sixth', 'imic_one_sixth');
function imic_two_third($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => ''
                    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    return '<div class="col-md-8 ' . $extra . '" ' . $animation . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('two_third', 'imic_two_third');
/* TABLE SHORTCODES
  ================================================= */
function imic_table_wrap($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => ''
                    ), $atts));
    $output = '<table class="table ' . $type . '">';
    $output .= do_shortcode($content) . '</table>';
    return $output;
}
add_shortcode('htable', 'imic_table_wrap');
function imic_table_headtag($atts, $content = null) {
    $output = '<thead>' . do_shortcode($content) . '</thead>';
    return $output;
}
add_shortcode('thead', 'imic_table_headtag');
function imic_table_body($atts, $content = null) {
    $output = '<tbody>' . do_shortcode($content) . '</tbody>';
    return $output;
}
add_shortcode('tbody', 'imic_table_body');
function imic_table_row($atts, $content = null) {
    $output = '<tr>';
    $output .= do_shortcode($content) . '</tr>';
    return $output;
}
add_shortcode('trow', 'imic_table_row');
function imic_table_column($atts, $content = null) {
    $output = '<td>';
    $output .= do_shortcode($content) . '</td>';
    return $output;
}
add_shortcode('tcol', 'imic_table_column');
function imic_table_head($atts, $content = null) {
    $output = '<th>';
    $output .= do_shortcode($content) . '</th>';
    return $output;
}
add_shortcode('thcol', 'imic_table_head');
/* TYPOGRAPHY SHORTCODES
  ================================================= */
// Anchor tag
function imic_anchor($atts, $content = null) {
    extract(shortcode_atts(array(
        "href" => '',
        "extra" => ''
                    ), $atts));
    return '<a href="' . $href . '" class="' . $extra . '" >' . do_shortcode($content) . ' </a>';
}
add_shortcode('anchor', 'imic_anchor');
// Alert tag
function imic_alert($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => '',
        "close" => ''
                    ), $atts));
    $closeButton = ($close == 'yes') ? '<a class="close" data-dismiss="alert" href="#">&times;</a>' : '';
    return '<div class="alert ' . $type . ' fade in">  ' . $closeButton . do_shortcode($content) . ' </div>';
}
add_shortcode('alert', 'imic_alert');
// Heading Tag
function imic_heading_tag($atts, $content = null) {
    extract(shortcode_atts(array(
        "size" => '',
        "extra" => '',
                    ), $atts));
    $output = '<' . $size . ' class="' . $extra . '">' . do_shortcode($content) . '</' . $size . '>';
    return $output;
}
add_shortcode("heading", "imic_heading_tag");
// Divider Tag
function imic_divider_tag($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
                    ), $atts));
    return '<hr class="' . $extra . '">';
}
add_shortcode("divider", "imic_divider_tag");
// Paragraph type 
function imic_paragraph($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
                    ), $atts));
    return '<p class="' . $extra . '">' . do_shortcode($content) . '</p>';
}
add_shortcode("paragraph", "imic_paragraph");
// Span type 
function imic_span($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
                    ), $atts));
    return '<span class="' . $extra . '">' . do_shortcode($content) . '</span>';
}
add_shortcode("span", "imic_span");
// Container type 
function imic_container($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
                    ), $atts));
    return '<div class="' . $extra . '">' . do_shortcode($content) . '</div>';
}
add_shortcode("container", "imic_container");
// Section type 
function imic_section($atts, $content = null) {
    extract(shortcode_atts(array(
        "extra" => '',
                    ), $atts));
    return '<section class="' . $extra . '">' . do_shortcode($content) . '</section>';
}
add_shortcode("section", "imic_section");
// Dropcap type 
function imic_dropcap($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => '',
                    ), $atts));
    return '<p class="drop-caps ' . $type . '">' . do_shortcode($content) . '</p>';
}
add_shortcode("dropcap", "imic_dropcap");
// Blockquote type
function imic_blockquote($atts, $content = null) {
    extract(shortcode_atts(array(
        "name" => '',
                    ), $atts));
    if (!empty($name)) {
        $authorName = '<cite>- ' . $name . '</cite>';
    } else {
        $authorName = '';
    }
    return '<blockquote><p>' . do_shortcode($content) . '</p>' . $authorName . '</blockquote>';
}
add_shortcode("blockquote", "imic_blockquote");
// Code type
function imic_code($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => '',
                    ), $atts));
    if ($type == 'inline') {
        return '<code>' . do_shortcode($content) . '</code>';
    } else {
        return '<pre>' . do_shortcode($content) . '</pre>';
    }
}
add_shortcode("code", "imic_code");
// Label Tag
function imic_label_tag($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => '',
                    ), $atts));
    $output = '<span class="label ' . $type . '">' . do_shortcode($content) . '</span>';
    return $output;
}
add_shortcode("label", "imic_label_tag");
// Spacer Tag
function imic_spacer_tag($atts, $content = null) {
    extract(shortcode_atts(array(
        "size" => '',
                    ), $atts));
    $output = '<div class="' . $size . '"></div>';
    return $output;
}
add_shortcode("spacer", "imic_spacer_tag");
/* LISTS SHORTCODES
  ================================================= */
function imic_list($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => '',
        "extra" => '',
        "icon" => ''
                    ), $atts));
    if ($type == 'ordered') {
        $output = '<ol>' . do_shortcode($content) . '</ol>';
    } else if ($type == 'desc') {
        $output = '<dl>' . do_shortcode($content) . '</dl>';
    } else {
        $output = '<ul class="chevrons ' . $type . ' ' . $extra . '">' . do_shortcode($content) . '</ul>';
    }
    return $output;
}
add_shortcode('list', 'imic_list');
function imic_list_item($atts, $content = null) {
    extract(shortcode_atts(array(
        "icon" => '',
        "type" => ''
                    ), $atts));
    if (($type == 'icon') || ($type == 'inline')) {
        $output = '<li><i class="fa ' . $icon . '"></i> ' . do_shortcode($content) . '</li>';
    } else {
        $output = '<li>' . do_shortcode($content) . '</li>';
    }
    return $output;
}
add_shortcode('list_item', 'imic_list_item');
function imic_list_item_dt($atts, $content = null) {
    $output = '<dt>' . do_shortcode($content) . '</dt>';
    return $output;
}
add_shortcode('list_item_dt', 'imic_list_item_dt');
function imic_list_item_dd($atts, $content = null) {
    $output = '<dd>' . do_shortcode($content) . '</dd>';
    return $output;
}
add_shortcode('list_item_dd', 'imic_list_item_dd');
function imic_page_first($atts, $content = null) {
    return '<li><a href="#"><i class="fa fa-chevron-left"></i></a></li>';
}
add_shortcode('page_first', 'imic_page_first');
function imic_page_last($atts, $content = null) {
    return '<li><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
}
add_shortcode('page_last', 'imic_page_last');
function imic_page($atts, $content = null) {
    extract(shortcode_atts(array(
        "class" => ''
                    ), $atts));
    return '<li class="' . $class . '"><a href="#">' . do_shortcode($content) . ' </a></li>';
}
add_shortcode('page', 'imic_page');
/* SIDEBAR SHORTCODES
  =================================================*/
function imic_sidebar($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => "",
		"column" => 4
     ), $atts));
	 ob_start();
dynamic_sidebar($id);
$html = ob_get_contents();
ob_end_clean();
return '
<div class="col-md-'.$column.' col-sm-'.$column.'">'.$html.'</div>';
}
add_shortcode('sidebar', 'imic_sidebar'); 
  
/* TABS SHORTCODES
  ================================================= */
function imic_tabs($atts, $content = null) {
    return '<div class="tabs">' . do_shortcode($content) . '</div>';
}
add_shortcode('tabs', 'imic_tabs');
function imic_tabh($atts, $content = null) {
    return '<ul class="nav nav-tabs">' . do_shortcode($content) . '</ul>';
}
add_shortcode('tabh', 'imic_tabh');
function imic_tab($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => '',
        "class" => ''
                    ), $atts));
    return '<li class="' . $class . '"> <a data-toggle="tab" href="#' . $id . '"> ' . do_shortcode($content) . ' </a> </li>';
}
add_shortcode('tab', 'imic_tab');
function imic_tabc($atts, $content = null) {
    return '<div class="tab-content">' . do_shortcode($content) . '</div>';
}
add_shortcode('tabc', 'imic_tabc');
function imic_tabrow($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => '',
        "class" => ''
                    ), $atts));
    $output = '<div id="' . $id . '" class="tab-pane ' . $class . '">' . do_shortcode($content) . '</div>';
    return $output;
}
add_shortcode('tabrow', 'imic_tabrow');
/* ACCORDION SHORTCODES
  ================================================= */
function imic_accordions($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => ''
                    ), $atts));
    return '<div class="accordion" id="accordion' . $id . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('accordions', 'imic_accordions');
function imic_accgroup($atts, $content = null) {
    return '<div class="accordion-group panel">' . do_shortcode($content) . '</div>';
}
add_shortcode('accgroup', 'imic_accgroup');
function imic_acchead($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => '',
        "class" => '',
        "tab_id" => ''
                    ), $atts));
    $output = '<div class="accordion-heading accordionize"> <a class="accordion-toggle ' . $class . '" data-toggle="collapse" data-parent="#accordion' . $id . '" href="#' . $tab_id . '"> ' . do_shortcode($content) . ' <i class="fa fa-angle-down"></i> </a> </div>';
    return $output;
}
add_shortcode('acchead', 'imic_acchead');
function imic_accbody($atts, $content = null) {
    extract(shortcode_atts(array(
        "tab_id" => '',
        "in" => ''
                    ), $atts));
    $output = '<div id="' . $tab_id . '" class="accordion-body ' . $in . ' collapse">
					  <div class="accordion-inner"> ' . do_shortcode($content) . ' </div>
					</div>';
    return $output;
}
add_shortcode('accbody', 'imic_accbody');
/* TOGGLE SHORTCODES
  ================================================= */
function imic_toggles($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => ''
                    ), $atts));
    return '<div class="accordion" id="toggle' . $id . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('toggles', 'imic_toggles');
function imic_togglegroup($atts, $content = null) {
    return '<div class="accordion-group panel">' . do_shortcode($content) . '</div>';
}
add_shortcode('togglegroup', 'imic_togglegroup');
function imic_togglehead($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => '',
        "tab_id" => ''
                    ), $atts));
    $output = '<div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#' . $tab_id . '"> ' . do_shortcode($content) . ' <i class="fa fa-plus-circle"></i> <i class="fa fa-minus-circle"></i> </a> </div>';
    return $output;
}
add_shortcode('togglehead', 'imic_togglehead');
function imic_togglebody($atts, $content = null) {
    extract(shortcode_atts(array(
        "tab_id" => ''
                    ), $atts));
    $output = '<div id="' . $tab_id . '" class="accordion-body collapse">
              <div class="accordion-inner"> ' . do_shortcode($content) . '  </div>
            </div>';
    return $output;
}
add_shortcode('togglebody', 'imic_togglebody');
/* PROGRESS BAR SHORTCODE
  ================================================= */
function imic_progress_bar($atts) {
    extract(shortcode_atts(array(
        "percentage" => '',
        "name" => '',
        "type" => '',
        "value" => '',
        "colour" => ''
                    ), $atts));
    if ($type == 'progress-striped') {
        $typeClass = $type;
    } else {
        $typeClass = "";
    }
    if ($colour == 'progress-bar-warning') {
        $warningText = '(warning)';
    } else {
        $warningText = "";
    }
    $service_bar_output = '';
    if ($type == "") {
        $type = "standard";
        if (!empty($name)) {
            $service_bar_output = '<div class="progress-label"> <span>' . $name . '</span> </div>';
        }
    }
    $service_bar_output .= '<div class="progress ' . $typeClass . '">';
    if ($type == 'progress-striped') {
        $service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%">';
        $service_bar_output .= '<span class="sr-only">' . $value . '% '.__('Complete(success)','framework').' </span>';
        $service_bar_output .= '</div>';
    } else if ($type == 'colored') {
        if (!empty($warningText)) {
            $spanClass = '';
        } else {
            $spanClass = 'sr-only';
        }
        $service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%"> <span class="' . $spanClass . '">' . $value . '% '.__('Complete','framework'). $warningText . '</span> </div>';
    } else {
        $service_bar_output .= '<div class="progress-bar progress-bar-primary" data-appear-progress-animation="' . $value . '%" data-appear-animation-delay="200"> <span class="progress-bar-tooltip">' . $value . '%</span> </div>';
    }
    $service_bar_output .= '</div>';
    return $service_bar_output;
}
add_shortcode('progress_bar', 'imic_progress_bar');
/* TOOLTIP SHORTCODE
  ================================================= */
function imic_tooltip($atts, $content = null) {
    extract(shortcode_atts(array(
        "title" => '',
        "link" => '#',
        "direction" => 'top'
                    ), $atts));
    $tooltip_output = '<a href="' . $link . '" rel="tooltip" data-toggle="tooltip" data-original-title="' . $title . '" data-placement="' . $direction . '">' . do_shortcode($content) . '</a>';
    return $tooltip_output;
}
add_shortcode('imic_tooltip', 'imic_tooltip');
/* YEAR SHORTCODE
  ================================================= */
function imic_year_shortcode() {
    $year = date('Y');
    return $year;
}
add_shortcode('the-year', 'imic_year_shortcode');
/* WORDPRESS LINK SHORTCODE
  ================================================= */
function imic_wordpress_link() {
    return '<a href="http://wordpress.org/" target="_blank">'.__('WordPress','framework').'</a>';
}
add_shortcode('wp-link', 'imic_wordpress_link');
/* COUNT SHORTCODE
  ================================================= */
function imic_count($atts) {
    extract(shortcode_atts(array(
        "speed" => '2000',
        "to" => '',
        "icon" => '',
        "subject" => '',
        "textstyle" => ''
                    ), $atts));
    $count_output = '';
    if ($speed == "") {
        $speed = '2000';
    }
	$count_output .= '<div class="countdown">';
    $count_output .= '<div class="fact-ico"> <i class="fa ' . $icon . ' fa-4x"></i> </div>';
    $count_output .= '<div class="clearfix"></div>';
    $count_output .= '<div class="timer" data-perc="' . $speed . '"> <span class="count">' . $to . '</span></div>';
    $count_output .= '<div class="clearfix"></div>';
    if ($textstyle == "h3") {
        $count_output .= '<h3>' . $subject . '</h3>';
    } else if ($textstyle == "h6") {
        $count_output .= '<h6>' . $subject . '</h6>';
    } else {
        $count_output .= '<span class="fact">' . $subject . '</span>';
    }
	$count_output .= '</div>';
    return $count_output;
}
add_shortcode('imic_count', 'imic_count');
/* MODAL BOX SHORTCODE
  ================================================== */
function imic_modal_box($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => "",
        "title" => "",
        "text" => "",
        "button" => ""
                    ), $atts));
    $modalBox = '<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#' . $id . '">' . $button . '</button>
            <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="' . $id . 'Label">' . $title . '</h4>
                  </div>
                  <div class="modal-body"> ' . $text . ' </div>
                  <div class="modal-footer">
               <button type="button" class="btn btn-default inverted" data-dismiss="modal">'.__('Close','framework').'</button>
                  </div>
                </div>
              </div>
            </div>';
    return $modalBox;
}
add_shortcode('modal_box', 'imic_modal_box');
/* FORM SHORTCODE
  ================================================== */
function imic_form_code($atts, $content = null) {
   extract(shortcode_atts(array(
        "form_email" => '',
                    ), $atts));
     if(!empty($form_email)){
        $admin_email = $form_email; 
      }else{
      $admin_email = get_option('admin_email');
       }
$subject_email = __('Contact Form','framework'); 
   $formCode = '<form action="'.get_template_directory_uri().'/mail/contact.php" type="post" class="contact-form-native">
					  <div class="row">
						<div class="form-group">
						  <div class="col-md-6">
							<label>'.__('Your name','framework').' *</label>
							<input type="text" value="" maxlength="100" class="form-control" name="name" id="name">
						  </div>
						  <div class="col-md-6">
							<label>'.__('Your email address','framework').' *</label>
							<input type="email" value="" maxlength="100" class="form-control" name="email" id="email">
						  </div>
                                                  <div class="col-md-12">
							<label>'.__('Your Phone Number','framework').'</label>
							<input type="text" id="phone" name="phone" class="form-control input-lg">
						  </div>
						</div>
					  </div>
					  <div class="row">
                                          <input type ="hidden" name ="image_path" id="image_path" value ="'.get_template_directory_uri().'">
                                          <input type="hidden" id="phone" name="phone" class="form-control input-lg" placeholder="">
                                          <input id="admin_email" name="admin_email" type="hidden" value ="'.$admin_email.'">
                                              <input id="subject" name="subject" type="hidden" value ="'.$subject_email.'">
						<div class="form-group">
						  <div class="col-md-12">
							<label>'.__('Comment','framework').'</label>
							<textarea maxlength="5000" rows="10" class="form-control" name="comments" id="comments" style="height: 138px;"></textarea>
						  </div>
						</div>
					  </div>
					  <div class="row">
						<div class="col-md-12">
						  <input type="submit" name ="submit" id ="submit" value="'.__('Submit','framework').'" class="btn btn-primary" data-loading-text="'.__('Loading...','framework').'">
						</div>
					  </div>
					</form><div class="clearfix"></div>
                    <div id="message"></div>';
    return $formCode;
}
add_shortcode('imic_form', 'imic_form_code');
/* FULLSCREEN VIDEO SHORTCODE
  ================================================= */
function imic_fullscreen_video($atts, $content = null) {
    extract(shortcode_atts(array(
        "videourl" => '',
        "fullwidth" => '',
		"autoplay" => 0
                    ), $atts));
    $fw_video_output = "";
    if (!empty($videourl)) {
		if ($fullwidth == "yes") {
        	$fw_video_output .='<div class="fw-video">'.imic_video_embed($videourl, 300, 200,$autoplay).'</div>';
		} else {
        	$fw_video_output .=imic_video_embed($videourl, 300, 200,$autoplay);
		}
    }
    return $fw_video_output;
}
add_shortcode('fullscreenvideo', 'imic_fullscreen_video');
/* Event Calendar SHORTCODE
  ================================================= */
function event_calendar($atts) {
    extract(shortcode_atts(array(
        "category_id" => '',
        "google_cal_id" => '',
        "filter" => '',
        "google_cal_id1" => '',
        "google_cal_id2" => '',
		"view" => 'month'
                    ), $atts));
		global $imic_options;
       
		//$google_feeds = $imic_options['google_feed'];
		$calendar_header_view = $imic_options['calendar_header_view'];
		$calendar_event_limit = $imic_options['calendar_event_limit'];
		$google_api_key = $imic_options['google_feed_key'];
		if($google_cal_id !==""){
			$google_calendar_id = $google_cal_id;
		} else {
			$google_calendar_id = $imic_options['google_feed_id'];
		}
		$calendar_today = (isset($imic_options['calendar_today']))?$imic_options['calendar_today']:'Today';
		$calendar_month = (isset($imic_options['calendar_month']))?$imic_options['calendar_month']:'Month';
		$calendar_week = (isset($imic_options['calendar_week']))?$imic_options['calendar_week']:'Week';
		$calendar_day = (isset($imic_options['calendar_day']))?$imic_options['calendar_day']:'Day';
		$google_calendar_id1 = $google_cal_id1;
		$google_calendar_id2 = $google_cal_id2;
        $monthNamesValue = $imic_options['calendar_month_name'];
        $monthNames = (empty($monthNamesValue)) ? array() : explode(',', trim($monthNamesValue));
        $monthNamesShortValue = $imic_options['calendar_month_name_short'];
        $monthNamesShort = (empty($monthNamesShortValue)) ? array() : explode(',', trim($monthNamesShortValue));
        $dayNamesValue = $imic_options['calendar_day_name'];
        $dayNames = (empty($dayNamesValue)) ? array() : explode(',', trim($dayNamesValue));
        $dayNamesShortValue = $imic_options['calendar_day_name_short'];
        $dayNamesShort = (empty($dayNamesShortValue)) ? array() : explode(',', trim($dayNamesShortValue));
		wp_enqueue_script('imic_fullcalendar');
		wp_enqueue_script('imic_gcal');
		wp_enqueue_script('imic_calender_events');
		$format=ImicConvertDate(get_option('time_format'));
                $term_output = '';
		if($filter==1) { 
		$e_terms = get_terms('event-category');
                $_color_bg='';
                foreach($e_terms as $term) {
                    $color_bg_cat=get_option( "category_".$term->term_id);
                    if($color_bg_cat)
                    {
                        $_color_bg=$color_bg_cat['catBG'];
                    }
                  }
                 $term_output .= '<div class="events-listing-header"><input type="radio" class="calender_filter" value="" checked="checked" id="calender_filter_#" name="calender_filter" value="#">'.'<label for="calender_filter_#">'.__('All','framework').'</label>';
		foreach($e_terms as $term) {
                   $color_bg_cat=get_option( "category_".$term->term_id);
                   $customColor_bg = isset($imic_options['custom_theme_color'])?$imic_options['custom_theme_color']:'';
                   $color_bg_class='';
                   $color_bg='';
                   $style='';
                   if($color_bg_cat && $_color_bg!='')
                   {
                       $color_bg=$color_bg_cat['catBG'];
                       $style="background-color:$color_bg;color:white";
                   }
                   else if($customColor_bg && $_color_bg!='' && $imic_options['theme_color_type']==1)
                   {
                      $color_bg = $customColor_bg;
                      $style="background-color:$color_bg;color:white";
                   }
                   else if($_color_bg!='')
                   {
                      $color_bg_class='accent-bg'; 
                      $style="color:white";
                   }       
                 $term_output .= '<input type="radio" id="calender_filter_'.$term->term_id.'" class="calender_filter" name="calender_filter" value="'.$term->term_id.'"><label for="calender_filter_'.$term->term_id.'" style="'.$style.'" class="'.$color_bg_class.'">'.$term->name.'</label>';
               }
                $term_output .= '</div>';
                }
        wp_localize_script('imic_calender_events', 'calenderEvents', array('homeurl' => get_template_directory_uri(), 'monthNames' => $monthNames, 'monthNamesShort' => $monthNamesShort, 'dayNames' => $dayNames, 'dayNamesShort' => $dayNamesShort,'time_format'=>$format,'start_of_week'=>get_option('start_of_week'),'googlekey'=>$google_api_key,'googlecalid'=>$google_calendar_id,'googlecalid1'=>$google_calendar_id1,'googlecalid2'=>$google_calendar_id2,'calheadview'=> $calendar_header_view,'eventLimit'=>$calendar_event_limit,'today'=>$calendar_today,'month'=>$calendar_month,'week'=>$calendar_week,'day'=>$calendar_day,'view'=>$view));
return $term_output.'<div class="col-md-12"><div id ="'.$category_id.'" class ="event_calendar calendar"></div></div>';
}
add_shortcode('event_calendar', 'event_calendar');

/* widget shortcode for latest sermons */
function recent_sermons($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE,
		'autoplay' => ''
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct","framework"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('recent_sermons','recent_sermons'); 

/* end widget shortcode for latest sermons */
?>