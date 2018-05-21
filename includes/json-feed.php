<?php
// - standalone json feed -
header('Content-Type:application/json');
// - grab wp load, wherever it's hiding -
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
include_once('../../../../wp-includes/wp-db.php');
// - grab date barrier -
//$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );
$today = date('Y-m-d');
// - query -
 $event_cat_id = '';
 $month_event = $_POST['month_event'];
if (isset($_POST['event_cat_id'])&&!empty($_POST['event_cat_id'])){
  $event_cat_id = $_POST['event_cat_id'];
  $term_data = get_term_by( 'id', $event_cat_id, 'event-category', '', '' );
  $event_cat_id = $term_data->slug; }
$prev_month = date_i18n('Y-m', strtotime($month_event . " - 15 day"));
$prev_events = imic_recur_events_calendar('','',$event_cat_id,$prev_month);
$current_events = imic_recur_events_calendar('','',$event_cat_id,$month_event);
$next_month = date_i18n('Y-m', strtotime($month_event . " + 40 day"));
$next_events = imic_recur_events_calendar('','',$event_cat_id,$next_month);
$events = $prev_events+$current_events+$next_events;
ksort($events);
$jsonevents = array();
// - loop -
if ($events):
    global $post, $imic_options;
    foreach ($events as $key=>$value):
        //setup_postdata($post);
		$event_end_time_actual = get_post_meta($value, 'imic_event_end_tm', true);
		$event_end_time_actual_str = strtotime($event_end_time_actual);
		$frequency = get_post_meta($value,'imic_event_frequency',true);	
		$cat_id = wp_get_post_terms( $value, 'event-category', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all') );
		$event_color = '';
		if(!empty($cat_id)) {
		$cat_id = $cat_id[0]->term_id;
		$cat_data = get_option("category_".$cat_id);
		$event_color = ($cat_data['catBG']!='')?$cat_data['catBG']:$imic_options['event_default_color']; }
		$frequency_count = '';
			$frequency_count = get_post_meta($value,'imic_event_frequency_count',true);
			if($frequency>0) { $color = ($event_color!='')?$event_color:$imic_options['recurring_event_color']; $frequency_count = $frequency_count; } else { $frequency_count = 0; $color = $event_color; }
		$date_converted=date('Y-m-d',$key);
        $custom_event_url =imic_query_arg($date_converted,$value);
		$start_unix_time = get_post_meta($value,'imic_event_start_tm',true);
		$start_unix_time = strtotime($start_unix_time);
		$start_time = date('G:i',$start_unix_time);
		$event_time_end = date('G:i',$key);
		$start_date_cal = date('Y-m-d',$key);
		$start_unix_cal = strtotime($start_date_cal.' '.$start_time);
		$event_start_date_only = get_post_meta($value,'imic_event_start_dt',true);
		$event_start_date_only_str = strtotime($event_start_date_only);
		$event_start_date_only = date('Y-m-d',$event_start_date_only_str);
		$event_end_date_only = get_post_meta($value,'imic_event_end_dt',true);
		$event_end_date_only_str = strtotime($event_end_date_only);
		$event_end_date_only = date('Y-m-d',$event_end_date_only_str);
		$diff_date = imic_dateDiff($event_start_date_only,$event_end_date_only);
		if($diff_date>0) { 
		$end_time = date('G:i',$event_end_time_actual_str); 
		$key = strtotime($event_end_date_only.' '.$end_time);
		$start_unix_cal = strtotime($event_start_date_only.' '.$start_time);
		 }
		//if($diff_date>0) { $key = $key+4320; } 
            $gmts = date('Y-m-d H:i:s', $start_unix_cal);
            $gmte = date('Y-m-d H:i:s', $key);
            $gmts = strtotime($gmts);
            $gmte = strtotime($gmte);
            $stime = date('c', $gmts);
            $etime = date('c', $gmte);
		$st_all_day = get_post_meta($value,'imic_event_all_day',true);
		//if($diff_date>1) { $st_all_day = "12:00"; } 
         
     // - json items -
        $jsonevents[] = array(
            //'title' => html_entity_decode(get_the_title($value),ENT_QUOTES,ini_get("default_charset")),
			'title' => get_the_title($value),
            'allDay' => ($st_all_day!=1)?false:true, // <- true by default with FullCalendar
            'start' => $stime,
            'end' => $etime,
            'url' => $custom_event_url,
			'backgroundColor' => $color,
			'borderColor' => $color
        );
endforeach;
else :
endif;
// - fire away -
$options = get_option('imic_options');
$events_feeds = $options['event_feeds'];
if($events_feeds==1) {
echo json_encode($jsonevents); }
?>