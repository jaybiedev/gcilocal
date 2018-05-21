<?php
/*
  Template Name: Home Page Builder
*/
get_header();
global $imic_options;
$custom_home = get_post_custom(get_the_ID());
$home_id = get_the_ID();
$pageOptions = imic_page_design('',8); //page design options
imic_sidebar_position_module();
/* Start Hero Slider */
get_template_part('flex-slider');
/* End Hero Slider */
/** Upcoming Events Loop ** */
$temp_wp_query = clone $wp_query;
$today = date('Y-m-d');
$currentTime = date(get_option('time_format'));
$upcomingEvents = '';
$upcoming_events_category = get_post_meta(get_the_ID(),'imic_upcoming_event_taxonomy',true);
if(!empty($upcoming_events_category)){
$events_categories= get_term_by('id',$upcoming_events_category,'event-category');
$upcoming_events_category= $events_categories->slug; }
$imic_events_to_show_on = get_post_meta(get_the_ID(),'imic_events_to_show_on',true);
$imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
$event_add = imic_recur_events('future','nos',$upcoming_events_category,'');
       $google_events = getGoogleEvent();
	   if(!empty($google_events))
      $new_events = $google_events+$event_add;
	  else  $new_events = $event_add;
     ksort($new_events);
     if(!empty($new_events)){
		 $nos_event = 1;
    foreach ($new_events as $key => $value) {
		$eventTime = get_post_meta($value, 'imic_event_start_tm', true);
	   $event_End_time = get_post_meta($value, 'imic_event_end_tm', true);
	   $event_End_time = strtotime($event_End_time);
		$eventTime = strtotime($eventTime);
		$count_from = (isset($imic_options['countdown_timer']))?$imic_options['countdown_timer']:'';
		if($count_from==1) { $counter_time = date('G:i',$event_End_time); }
		else { $counter_time = date('G:i',$eventTime); }
         if(preg_match('/^[0-9]+$/',$value)){
       
		if($eventTime!='') { $eventTime = date_i18n(get_option('time_format'),$eventTime); }
		
		  $eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
		  $eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
		  $eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
		  $eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
		  $evstendtime    =  $eventStartTime.'|'.$eventEndTime;
		  $evstenddate    =  $eventStartDate.'|'.$eventEndDate;
		  $event_dt_out   =  imic_get_event_timeformate( $evstendtime,$evstenddate,$value,$key);
		  $event_dt_out   =  explode('BR',$event_dt_out);
       
        $stime = '';
        $setime = '';
        if ($eventTime != '') {
            $stime = ' | ' . $eventTime;
            $setime = $eventTime;
        }
        $date_converted=date('Y-m-d',$key );
        $custom_event_url =imic_query_arg($date_converted,$value);  
         $event_title=get_the_title($value);
        if ($nos_event == 1) {
            $firstEventTitle = $event_title;
            $firstEventURL = $custom_event_url;
            $date_timer_event = date('Y-m-d', $key);
            $unix_time = strtotime($date_timer_event . ' ' . $setime);
            $time_timer_event = date('G:i', $unix_time);
            $firstEventDate = date_i18n( get_option( 'date_format' ),$key);
            $firstEventDateData = date('Y-m-d', $key) . ' ' . $counter_time;
         }}
         else{
             $google_data =(explode('!',$value)); 
            $event_title=$google_data[0];
           $custom_event_url=$google_data[1];
		   if((date('G', $key))=='00')
		   {
			   $stime = " | ".__("All Day","framework");
		   }
		   else
		   {
           		$stime = ' | ' . date(get_option('time_format'), $key);
		   }
           if ($nos_event == 1) {
            $firstEventTitle = $event_title;
            $firstEventURL = $custom_event_url;
            $date_timer_event = date('Y-m-d', $key);
			$firstEventDateData = date('Y-m-d G:i', $key);
            $eventTime = date_i18n(get_option('time_format'),$key);
            $unix_time = strtotime($date_timer_event . ' ' . $eventTime);
            $time_timer_event = date('G:i', $unix_time);
            $firstEventDate = date_i18n( get_option( 'date_format' ),$key);
			
			$event_dt_out = imic_get_event_timeformate($key.'|'.strtotime($google_data[2]),$key.'|'.$key,$value,$key);
			$event_dt_out = explode('BR',$event_dt_out);
        }
         }
        $upcomingEvents .= '<li class="item event-item">
                         		<div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">'.imic_global_month_name($key).'</span> </div>
                         		<div class="event-detail">
								<h4><a href="' . $custom_event_url . '">' . $event_title.'</a>'.imicRecurrenceIcon($value).'</h4>';
								if(preg_match('/^[0-9]+$/',$value)){
			                      $upcomingEvents .=	'<span class="event-dayntime meta-data">' .$event_dt_out[1].',&nbsp;&nbsp;'.$event_dt_out[0] . '</span>';
								}
								else
								{
									$upcomingEvents .= '<span class="event-dayntime meta-data">' .date_i18n('l', $key) . $stime . '</span>';
								}
								$upcomingEvents .= '</div>
								<div class="to-event-url">
									<div><a href="'.$custom_event_url.'" class="btn btn-default btn-sm">'.__('Details','framework').'</a></div>
								</div>
							</li>';
        if (++$nos_event > $imic_events_to_show_on)
            break;
     }}
     else{
   $no_upcoming_events_msg = '<div class="notice-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 hidden-xs">
                        
                    </div>'.__('No Upcoming Events Found', 'framework').'</div></div>';
     }
$wp_query = clone $temp_wp_query;
?>
<!-- Start Notice Bar -->
<?php
$imic_custom_message = get_post_meta($home_id,'imic_custom_text_message',true);
 $imic_latest_sermon_events = get_post_meta($home_id, 'imic_latest_sermon_events_to_show_on', true);
$imic_all_event_sermon_url= get_post_meta($home_id, 'imic_all_event_sermon_url', true);
 $imic_upcoming_events_area = get_post_meta($home_id,'imic_upcoming_area',true);
if($imic_upcoming_events_area==1) {
if ((!empty($firstEventTitle) && $imic_latest_sermon_events == 'letest_event')||(!empty($firstEventTitle) && $imic_latest_sermon_events=='')) {
    ?>
<div class="notice-bar">
<div class="container">
<?php $imic_going_on_events = get_post_meta($home_id, 'imic_going_on_events', true);
if($imic_going_on_events==2){ 
$event_add_going = imic_recur_events('future','nos','','');
ksort($event_add_going);
$currently_running = array();
foreach($event_add_going as $key=>$value) {
	$today = date('Y-m-d');
	$event_ongoing_date = date('Y-m-d',$key);
	$days_extra = imic_dateDiff($today, $event_ongoing_date);
	$event_st_time = get_post_meta($value,'imic_event_start_tm',true);
	$event_en_time = get_post_meta($value,'imic_event_end_tm',true);
	$evemt_st_time = strtotime($today.' '.$event_st_time);
	$event_en_time = strtotime($today.' '.$event_en_time);
	if($days_extra>0) { break; }
	if($event_st_time<date('U')&&$event_en_time>date('U')) {
	$currently_running[$key]=$value; }
}
$going_nos_event = 1;
$google_events = getGoogleEvent('goingEvent');
   if(!empty($google_events))
       $new_events = $google_events+$currently_running;
	   else  $new_events = $currently_running;
ksort($new_events);
if(!empty($new_events)){
$imic_custom_going_on_events_title = get_post_meta($home_id, 'imic_custom_going_on_events_title', true);
$imic_custom_going_on_events_title=!empty($imic_custom_going_on_events_title)?$imic_custom_going_on_events_title:__('Going on Events','framework');
echo '<div class="goingon-events-floater">';
echo '<h4>'.$imic_custom_going_on_events_title.'</h4>';
?>
<div class="goingon-events-floater-inner"></div>
<div class="flexslider" data-arrows="yes" data-style="slide" data-pause="yes">
<ul class="slides"><?php
foreach ($new_events as $key => $value) {
if(preg_match('/^[0-9]+$/',$value)){
$eventTime = get_post_meta($value, 'imic_event_start_tm', true);
$eventEndTime = get_post_meta($value, 'imic_event_end_tm', true);
$dash=$fa_clock = $stime =$etime= '';
if ($eventTime != '') {
$stime = strtotime($eventTime);
$stime=date('G:i',$stime );
}
if ($eventEndTime != '') {
$etime = strtotime($eventEndTime);
if(!empty($stime)){
   $dash=' - '; 
}
$etime=$dash.date('G:i',$etime);
}
if(!empty($stime)||!empty($etime)){
$fa_clock='<i class="fa fa fa-clock-o"></i> ';  
}
$date_converted=date('Y-m-d',$key );
$custom_event_url =imic_query_arg($date_converted,$value);
$event_title=get_the_title($value);
}
else{
            $google_data =(explode('!',$value)); 
            $event_title=$google_data[0];
            $custom_event_url=$google_data[1];
           $dash=$fa_clock = $stime =$etime= '';
if ($key != '') {
$stime = $key;
$stime=date('G:i',$stime );
}
$eventEndTime=$google_data[2];
if ($eventEndTime != '') {
$etime = strtotime($eventEndTime);
if(!empty($stime)){
   $dash=' - '; 
}
$etime=$dash.date('G:i',$etime);
}
if(!empty($stime)||!empty($etime)){
$fa_clock='<i class="fa fa fa-clock-o"></i> ';  
}}
echo '<li>
<a href="'.$custom_event_url.'"><strong class="title">' . $event_title . '</strong></a>
<span class="time">'.$fa_clock.$stime.$etime.'</span>
</li>';
$going_nos_event++; } ?>
</ul>
</div>
<?php echo '</div>';  } 
   $wp_query = clone $temp_wp_query; }?>
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-6 notice-bar-title"> <span class="notice-bar-title-icon hidden-xs"><i class="fa fa-calendar fa-3x"></i></span> <span class="title-note"><?php _e('Next', 'framework'); ?></span> <strong><?php _e('Upcoming Event', 'framework'); ?></strong> </div>
<div class="col-md-3 col-sm-6 col-xs-6 notice-bar-event-title">
<?php 
$specific_event_data='';
$event_category= get_post_meta($home_id,'imic_advanced_event_taxonomy','true');
	if($event_category!=''){
$event_categories= get_term_by('id',$event_category,'event-category');
if(!empty($event_categories)){
$event_category= $event_categories->slug; }
$specific_event_data = imic_recur_events('future','nos',$event_category,'');
ksort($specific_event_data);
$num = 1;
foreach($specific_event_data as $key=>$value):
	$eventTime = get_post_meta($value, 'imic_event_start_tm', true);
	$event_End_time = get_post_meta($value, 'imic_event_end_tm', true);
	$event_End_time = strtotime($event_End_time);
	$eventTime = strtotime($eventTime);
	$count_from = (isset($imic_options['countdown_timer']))?$imic_options['countdown_timer']:'';
	if($count_from==1) { $counter_time = date('G:i',$event_End_time); }
	else { $counter_time = date('G:i',$eventTime); }
	$firstEventDateData = date('Y-m-d', $key) . ' ' . $counter_time;
	$firstEventTitle = get_the_title($value);
	$firstEventDate = date_i18n( get_option( 'date_format' ),$key);
	$date_converted=date('Y-m-d',$key );
	$firstEventURL = imic_query_arg($date_converted,$value);
	break;
endforeach;
} ?>
<h5><a href="<?php echo $firstEventURL; ?>"><?php echo $firstEventTitle; ?></a></h5>
<span class="meta-data"><?php echo $firstEventDate; ?></span> </div>
<div id="counter" class="col-md-4 col-sm-6 col-xs-12 counter" data-date="<?php echo strtotime($firstEventDateData); ?>">
                    <div class="timer-col"> <span id="days"></span> <span class="timer-type"><?php _e('days', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="hours"></span> <span class="timer-type"><?php _e('hrs', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="minutes"></span> <span class="timer-type"><?php _e('mins', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="seconds"></span> <span class="timer-type"><?php _e('secs', 'framework'); ?></span> </div>
                </div>
                <?php
                $pages_e = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-events.php'
                ));
               if(!empty($imic_all_event_sermon_url)||!empty($pages_e[0]->ID)){
                    $imic_all_event_sermon_url = !empty($imic_all_event_sermon_url) ? $imic_all_event_sermon_url: get_permalink($pages_e[0]->ID);
                ?>
                    <div class="col-md-2 col-sm-6 hidden-xs"> <a href="<?php echo $imic_all_event_sermon_url ?>" class="btn btn-primary btn-lg btn-block"><?php _e('All Events', 'framework'); ?></a> </div>
                    <?php } ?>
            </div>
        </div>
    </div>
<?php } elseif($imic_latest_sermon_events == 'letest_sermon') {
   $sermons_cat='';
   $advanced_sermons_category= get_post_meta($home_id,'imic_advanced_sermons_category',true);
   if(!empty($advanced_sermons_category)){
  $sermons_cat_data= get_term_by('id',$advanced_sermons_category,'sermons-category');
  if(!empty($sermons_cat_data)){
   $sermons_cat= $sermons_cat_data->slug;
   }}
   $posts = get_posts(array('post_type' => 'sermons','sermons-category'=>$sermons_cat, 'post_status' => 'publish', 'suppress_filters' => false, 'posts_per_page' => 1));
    if (!empty($posts[0]->ID)) {
        ?>
        <div class="notice-bar latest-sermon">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 hidden-xs">
                        <h3><i class="fa fa-microphone"></i> <?php _e('Latest Sermon', 'framework'); ?></h3>
                    </div>
                    <?php
                    foreach ($posts as $post) {
					   $custom = get_post_custom(get_the_ID());
                      $attach_full_audio= imic_sermon_attach_full_audio($post->ID);
                      
                      if(!empty($attach_full_audio)) {
						  echo '<div class="col-md-7 col-sm-8 col-xs-12">';
						  echo '<h5><a href="'.get_the_permalink().'">'.get_the_title().'</a></h5>, <span class="meta-data">'.get_the_time(get_option('date_format')).'</span>';
                  echo '<audio class="audio-player" src="' . $attach_full_audio . '" type="audio/mp3" controls></audio>';
                       echo '</div>';
        	}
                        elseif (empty($attach_full_audio) && !empty($custom['imic_sermons_url'][0])){
							echo '<div class="col-md-7 col-sm-8 col-xs-12">';
							echo '<a href="' . $custom['imic_sermons_url'][0] . '" data-rel="prettyPhoto" class="latest-sermon-play"><i class="fa fa-play-circle-o"></i></a>';
                            echo '<h3><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h3>';
                       echo '</div>'; ?>
<?php
                           } else {
							echo '<div class="col-md-7 col-sm-8 col-xs-12">';
                            echo '<h3><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h3>'; 
                       echo '</div>';
						   }
                        $pages_s = get_pages(array(
                            'meta_key' => '_wp_page_template',
                            'meta_value' => 'template-sermons.php'
                        ));
                       if(!empty($imic_all_event_sermon_url)||!empty($pages_s[0]->ID)){
                        $imic_all_event_sermon_url = !empty($imic_all_event_sermon_url) ? $imic_all_event_sermon_url: get_permalink($pages_s[0]->ID);
                        echo'<div class="col-md-2 hidden-sm hidden-xs">
        	<a href="' . $imic_all_event_sermon_url . '" class="btn btn-block btn-primary">' . __('All Sermons', 'framework') . '</a>
        </div>';
                    }}
                    ?>
                </div>
            </div>
        </div>
    <?php } }
else {
	echo '<div class="notice-bar latest-sermon">
            <div class="container">
                <div class="row">';
				echo (do_shortcode($imic_custom_message));
				echo '</div>
			</div>
		</div>';
}
}
?>
<!-- End Notice Bar --> 
<!-- Start Content -->
<div class="main" role="main">
 <div id="content" class="content full">
    <div class="container">
       <?php  wp_reset_query();
              if($post->post_content!="") :
			  echo '<div class="page-content">';
                              the_content();    
				echo '</div>';    
                      endif;	 wp_reset_query();
               ?>
          
            </div>
        </div>
    </div>
<?php get_footer(); ?>