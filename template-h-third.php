<?php
/*
Template Name: Home 3
*/
get_header();
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
if ((!empty($firstEventTitle) && $imic_latest_sermon_events == 'latest_event')||(!empty($firstEventTitle) && $imic_latest_sermon_events=='')) {
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
<?php } elseif($imic_latest_sermon_events == 'latest_sermon') {
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
            <?php
            /** Upcoming Events Loop ** */
            $imic_recent_events_area = get_post_meta($home_id, 'imic_imic_upcoming_events', true);
            if ($imic_recent_events_area == 1) {
                $temp_wp_query = clone $wp_query;
                $today = date('Y-m-d');
                $currentTime = date(get_option('time_format'));
                $upcomingEvents = '';
                $upcoming_events_category = get_post_meta(get_the_ID(),'imic_upcoming_event_taxonomy',true);
if(!empty($upcoming_events_category)){
$events_categories= get_term_by('id',$upcoming_events_category,'event-category');
$upcoming_events_category= $events_categories->slug; }
                $imic_events_to_show_on = get_post_meta(get_the_ID(), 'imic_events_to_show_on', true);
                $imic_events_to_show_on = !empty($imic_events_to_show_on) ? $imic_events_to_show_on : 4;
                $event_add = imic_recur_events('future','',$upcoming_events_category,'');
                    $nos_event = 1;
                   $google_events = getGoogleEvent();
                   if(!empty($google_events))
				   $new_events = $google_events+$event_add;
				   else  $new_events = $event_add;
                   ksort($new_events);
                   if(!empty($new_events)){
                   foreach ($new_events as $key => $value) {
                       if(preg_match('/^[0-9]+$/',$value)){
                        $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
                        $eventEndTime = get_post_meta($value, 'imic_event_end_tm', true);
                        $eventTime = strtotime($eventTime);
                        $eventEndTime = strtotime($eventEndTime);
                        if ($eventTime != '') {
                            $eventTime = date_i18n(get_option('time_format'), $eventTime);
                        }
                        if ($eventEndTime != '') {
                            $eventEndTime = date_i18n(get_option('time_format'), $eventEndTime);
                        }
                        $stime = '';
                        $setime = '';
                        if ($eventTime != '') {
                            $stime = ' | ' . $eventTime;
                            $setime = $eventTime;
                        }
                        $etime = '';
                        if ($eventEndTime != '') {
                            $etime = ' - ' . $eventEndTime;
                        }
                        $date_converted = date('Y-m-d', $key);
                        $custom_event_url =imic_query_arg($date_converted,$value);  
                       $google_events_flag=1;
                       $event_title=get_the_title($value);
                        }
                         else{
                             $google_events_flag =2;
        $google_data =(explode('!',$value)); 
            $event_title=$google_data[0];
           $custom_event_url=$google_data[1];
           $stime = '';
                        $setime = '';
                        if ($key != '') {
                            $stime = ' | ' . date(get_option('time_format'), $key);
                            $setime = date(get_option('time_format'), $key);
                        }
                         $etime=$google_data[2];
                        if($etime!='') { $etime =  ' - '. date_i18n(get_option('time_format'),strtotime($etime)); }    
     }
                        $upcomingEvents .='<li class="col-md-3 format-standard">
                    		<div class="grid-item-inner">';
                        if($google_events_flag==1){
                        $thumb_id = get_post_thumbnail_id($value);
                        if (!empty($thumb_id)):
                            $upcomingEvents .='<a href="' . $custom_event_url . '" class="media-box">' . get_the_post_thumbnail($value, '600x400') . '</a>';
                        endif;
                        }
                        $upcomingEvents .='<div class="grid-content">';
                        if($google_events_flag==1){
                        $e_term = wp_get_object_terms($value,'event-category');
                         $term_link='';
                        if (!empty($e_term)) {
                              $pages_e = get_pages(array(
                              'meta_key' => '_wp_page_template',
                              'meta_value' => 'template-event-category.php'
                            ));
                        $imic_event_category_page_url=!empty($pages_e[0]->ID)?get_permalink($pages_e[0]->ID):'';
                                        $i = 1;
                                        foreach ($e_term as $terms) {
                                            if ($i == 1) {
                                                if(!empty($imic_event_category_page_url)){
                                                $term_link=imic_query_arg_event_cat($terms->slug,$imic_event_category_page_url);
                                                $term_link='<a href="' . $term_link . '">' .$terms->name. '</a>';
                                            }}
                                            $i++;
                                        }}
                           if(!empty($term_link)){
                                        $upcomingEvents .='<div class="label label-primary event-cat">' .$term_link .'</div>';
                                        }
                        }
                       $upcomingEvents .='<h5><a href="' . $custom_event_url . '">' .$event_title . '</a>'.imicRecurrenceIcon($value).'</h5>                       
                        			<span class="meta-data"><i class="fa fa-calendar"></i>' . date_i18n('l', $key) . $stime . $etime . '</span>';
                                 $upcomingEvents .='</div>';
                    		$upcomingEvents .='</div></li>';
                       
                        if (++$nos_event > $imic_events_to_show_on)
                            break;
                    }
            }
               else{
                    $no_upcoming_events_msg = __('No Upcoming Events Found', 'framework');
            }
                $wp_query = clone $temp_wp_query;
                $pages_e = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-events.php'
                ));
                $imic_custom_all_event_url = get_post_meta($home_id, 'imic_custom_all_event_url', true);
                ?>
            <div class="listing">
                    <header class="listing-header">
                        <?php
                        if (!empty($imic_custom_all_event_url) || !empty($pages_e[0]->ID)) {
                            $imic_custom_all_event_url = !empty($imic_custom_all_event_url) ? $imic_custom_all_event_url : get_permalink($pages_e[0]->ID);
                            $imic_custom_all_event_url = !empty($imic_custom_all_event_url) ? $imic_custom_all_event_url : get_permalink($pages_e[0]->ID);
                            echo '<a href="' . $imic_custom_all_event_url . '" class="btn btn-primary pull-right push-btn">' . __('All Events', 'framework') . '</a>';
                        }
                        $imic_custom_upcoming_events_title= get_post_meta($home_id,'imic_custom_upcoming_events_title',true);
                        $imic_custom_upcoming_events_title = !empty($imic_custom_upcoming_events_title) ? $imic_custom_upcoming_events_title : __('Upcoming Events', 'framework');
                        echo '<h3>' . $imic_custom_upcoming_events_title . '</h3>';
                        ?>
                    </header>
                    <section class="listing-cont">
                        <ul class="event-blocks row">
                            <?php
                            echo $upcomingEvents;
                            if (isset($no_upcoming_events_msg)):
                                echo '<li class="col-md-12">' . $no_upcoming_events_msg . '</li>';
                            endif;
                            ?>
                        </ul></section>
                </div>
                <div class="margin-20"></div>
                <?php
            }
            $posts_per_page = get_post_meta($home_id, 'imic_posts_to_show_on', true);
            $imic_recent_post_area = get_post_meta($home_id, 'imic_imic_recent_posts', true);
            $post_category = get_post_meta($home_id,'imic_recent_post_taxonomy',true);
						
            
            if ($imic_recent_post_area == 1) {
                if ($posts_per_page == '') {
                    $posts_per_page = 3;
                }
                $temp_wp_query = clone $wp_query;
                if(!empty($post_category))
										{
					$post_category = explode(',', $post_category);
                    query_posts(array(
                        'post_type' => 'post',
												'tax_query' => array(array(
												'taxonomy' => 'category',
												'field' => 'term_id',
												'terms' => $post_category,
												'operator' => 'IN')),
                        'posts_per_page' => $posts_per_page,
                    ));
										}
										else
										{
											query_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => $posts_per_page,
                    ));
										}
                if (have_posts()):
                    ?>
                    <div class="row">
                        <div class="<?php echo $pageOptions['class']; ?>" id="content-col">  
                            <div class="listing post-listing">
                                <header class="listing-header">
                                    <?php
                                    $imic_custom_latest_news_title = get_post_meta($home_id,'imic_custom_latest_news_title', true);
                                    $imic_custom_latest_news_title = !empty($imic_custom_latest_news_title) ?$imic_custom_latest_news_title: __('Latest News', 'framework');
                                    echo'<h3>' . $imic_custom_latest_news_title . '</h3>';
                                    ?>
                                </header>
                                <section class="listing-cont">
                                    <ul>
                                        <?php
                                        while (have_posts()):the_post();
                                            if ('' != get_the_post_thumbnail()) {
                                                $class = "col-md-8";
                                            } else {
                                                $class = "col-md-12";
                                            }
                                            ?>
                                            <li class="item post">
                                                <div class="row">
                                                    <?php
                                                    if (has_post_thumbnail()):
                                                        echo '<div class="col-md-4">
                                                        <a href="' . get_permalink() . '" class="media-box">';
                                                        the_post_thumbnail('600x400');
                                                        echo '</a>
                                                </div>';
                                                    endif;
                                                    ?>
                                                    <div class="<?php echo $class; ?>">
                                                        <div class="post-title">
                                                            <?php
                                                            echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                                                            echo '<span class="meta-data"><i class="fa fa-calendar"></i>' . __('on ', 'framework') . get_the_time(get_option('date_format')) . '</span></div>';
															echo '<div class="page-content">';
                                                            echo imic_excerpt(50);
															echo '</div>';
                                                            echo'<p><a href="' . get_permalink() . '" class="btn btn-primary">' . __('Continue reading', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>';
                                                            ?>
                                                        </div>
                                                    </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </section>
                            </div></div>
                        <?php
                    endif;
                    $wp_query = clone $temp_wp_query;
                }
                ?>
                <?php if(!empty($pageOptions['sidebar'])){ ?>
                <!-- Start Sidebar -->
                <div class="col-md-4 col-sm-6" id="sidebar-col"> 
                    <?php dynamic_sidebar($pageOptions['sidebar']); ?>
                </div>
                <!-- End Sidebar -->
                <?php } ?>
            </div></div>
       	<div class="margin-50"></div>
        <!-- Parallax Section 1 -->
        <?php
        $imic_imic_galleries = get_post_meta($home_id, 'imic_imic_galleries', true);
        $posts_per_page = get_post_meta($home_id, 'imic_galleries_to_show_on', true);
        $posts_per_page = !empty($posts_per_page) ? $posts_per_page : 3;
        $temp_wp_query = clone $wp_query;
        $gallery_category = get_post_meta($home_id,'imic_home_gallery_taxonomy',true);
if(!empty($gallery_category)){
$gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
if(!empty($gallery_categories)){
$gallery_category= $gallery_categories->slug;}
else{
  $gallery_category='';  
}}
        query_posts(array(
            'post_type' => 'gallery',
            'gallery-category' => $gallery_category,
            'posts_per_page' => $posts_per_page,
        ));
        if (have_posts() && $imic_imic_galleries == 1):
           $gallery_size = imicGetThumbAndLargeSize();
           $size_thumb =$gallery_size[0];
           $size_large =$gallery_size[1];
            $gallery_thumb_id = get_post_meta($home_id, 'imic_galleries_background_image', true);
            $large_src_i = wp_get_attachment_image_src($gallery_thumb_id, 'full');
            ?>
            <div class="parallax parallax1 padding-tb100" style="background-image:url(<?php echo $large_src_i[0]; ?>)">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <?php
                            $imic_custom_gallery_title = get_post_meta($home_id,'imic_custom_gallery_title',true);
                            $imic_custom_gallery_title = !empty($imic_custom_gallery_title)?$imic_custom_gallery_title: __('Updates from our gallery', 'framework');
                            echo'<h4>' . $imic_custom_gallery_title . '</h4>';
                            $imic_custom_more_galleries_title = get_post_meta($home_id,'imic_custom_more_galleries_title',true);
                            $imic_custom_more_galleries_title = !empty($imic_custom_more_galleries_title) ? $imic_custom_more_galleries_title: __('More Galleries', 'framework');
                            $pages = get_pages(array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'template-gallery-pagination.php'
                            ));
                            $imic_custom_more_galleries_url = get_post_meta($home_id, 'imic_custom_more_galleries_url', true);
                            $imic_custom_more_galleries_url = !empty($imic_custom_more_galleries_url) ? $imic_custom_more_galleries_url : get_permalink($pages[0]->ID);
                            if (!empty($imic_custom_more_galleries_url) || !empty($pages[0]->ID)) {
                                echo'<a href="' . $imic_custom_more_galleries_url . '" class="btn btn-default btn-lg">' . $imic_custom_more_galleries_title . '</a>';
                            }
                            echo '</div>';
                            while (have_posts()):the_post();
                                $custom = get_post_custom(get_the_ID());
                               if (!empty($imic_gallery_images)) {
                                    $gallery_img = $imic_gallery_images;
                                } else {
                                    $gallery_img = '';
                                }
                                $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
                    $thumb_id=get_post_thumbnail_id(get_the_ID());
                   $post_format_temp =get_post_format();
                 if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
                  $post_format =!empty($post_format_temp)?$post_format_temp:'image';
                                    echo '<div class="col-md-3 col-sm-3 post format-' . $post_format . '">';
                                    switch (get_post_format()) {
										case 'image':
											$large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
											if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
												$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
											}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
												$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
											}
											echo $Lightbox_init;
											the_post_thumbnail($size_thumb);
											echo'</a>';
											break;
										case 'gallery':
											echo '<div class="media-box">';
											imic_gallery_flexslider(get_the_ID());
											if (count($image_data) > 0) {
												echo'<ul class="slides">';
												$i = 0;
												foreach ($image_data as $custom_gallery_images) {
												$large_src = wp_get_attachment_image_src($custom_gallery_images, 'full');
												$gallery_thumbnail = wp_get_attachment_image_src($custom_gallery_images, $size_thumb);
												$gallery_title = get_the_title($custom_gallery_images);
												echo'<li class="item">';
												if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
													$Lightbox_init = '<a href="' .esc_url($large_src[0]). '"data-rel="prettyPhoto[' . get_the_title() . ']">';
												}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
													$Lightbox_init = '<a href="'.esc_url($large_src[0]) .'" title="'.esc_attr($gallery_title).'" class="magnific-gallery-image">';
												}
												echo $Lightbox_init;
												if($i === 0){
													  echo '<img src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
												} else {
													  echo '<img class="lazy" data-src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
												}
												echo'</a></li>';
												$i++;
												}
												echo'</ul>';
											}
											echo'</div>
											</div>';
											break;
										case 'link':
											if (!empty($custom['imic_gallery_link_url'][0])) {
												echo '<a href="' . $custom['imic_gallery_link_url'][0] . '" target="_blank" class="media-box">';
												the_post_thumbnail($size_thumb);
												echo'</a>';
											}
											break;
										case 'video':
											if (!empty($custom['imic_gallery_video_url'][0])) {
											   if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
													$Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" data-rel="prettyPhoto" class="media-box">';
												}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
													$Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" title="'.get_the_title().'" class="media-box magnific-video">';
												}
												echo $Lightbox_init;
												the_post_thumbnail($size_thumb);
												echo'</a>';
											}
											break;
										default:
											$large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
											if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
												$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
											}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
												$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
											}
											echo $Lightbox_init;
											the_post_thumbnail($size_thumb);
											echo'</a>';
											break;
									}
									echo'</div>';
                                endif;
                            endwhile;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="margin-50"></div>
                <?php
            endif;
            $wp_query = clone $temp_wp_query;
            $temp_wp_query = clone $wp_query;
            $imic_switch_album = get_post_meta($home_id, 'imic_switch_sermon_album', 'true');
            if ($imic_switch_album == 1) {
                $number_of_sermon_albums = get_post_meta($home_id, 'imic_number_of_sermon_albums', true);
                $number_of_sermon_albums = !empty($number_of_sermon_albums) ? $number_of_sermon_albums : 4;
                ?>
                <div class="container">
                    <div class="listing">
                        <header class="listing-header">
                            <?php
                            $pages_s_albums = get_pages(array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'template-sermons-albums.php'
                            ));
                            $imic_all_sermon_url = get_post_meta($home_id, 'imic_sermon_albums_url', 'true');
                            if (!empty($imic_all_sermon_url) || !empty($pages_s_albums[0]->ID)) {
                                $imic_all_sermon_url = !empty($imic_all_sermon_url) ? $imic_all_sermon_url : get_permalink($pages_s_albums[0]->ID);
                                echo '<a href="' . $imic_all_sermon_url . '" class="btn btn-primary pull-right push-btn">' . __('All Albums', 'framework') . '</a>';
                            }
                            $imic_custom_albums_title = get_post_meta($home_id, 'imic_custom_albums_title', 'true');
                            $imic_custom_albums_title = !empty($imic_custom_albums_title) ? $imic_custom_albums_title : __('Latest Sermon Albums', 'framework');
                            echo '<h3>' . $imic_custom_albums_title . '</h3>';
                            ?>
                        </header>
                        <?php
                        $taxonomies = array('sermons-category');
                        $args = array('orderby' => 'count', 'hide_empty' =>true);
                        $sermonterms = get_terms($taxonomies, $args);
                        if(!empty($sermonterms)){
                        ?>
                        <section class="listing-cont">
                            <ul class="album-blocks row">
                                <?php
                                $i = 1;
                                foreach($sermonterms as $sermonterms_data) {
                                    query_posts(array(
                                        'post_type' => 'sermons',
                                        'sermons-category' => $sermonterms_data->slug
                                    ));
                                    $imic_sermon_attach_full_audio_array = $imic_sermons_url_array = array();
                                    while (have_posts()):the_post();
                                        $imic_sermons_url = get_post_meta(get_the_ID(), 'imic_sermons_url', true);
                                        if (!empty($imic_sermons_url)) {
                                            array_push($imic_sermons_url_array, $imic_sermons_url);
                                        }
                                        $imic_sermon_attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
                                        if (!empty($imic_sermon_attach_full_audio)) {
                                            array_push($imic_sermon_attach_full_audio_array, $imic_sermon_attach_full_audio);
                                        }
                                    endwhile;
                                    $term_link = get_term_link($sermonterms_data->slug, 'sermons-category');
                                     ?> 
                                    <li class="col-md-3 col-sm-6">
                                        <div class="grid-item-inner">
                                            <?php 
                                            $t_id = $sermonterms_data->term_id; // Get the ID of the term we're editing
                                            $term_meta = get_option($sermonterms_data->taxonomy . $t_id . "_image_term_id"); // Do the check 
                                            if(!empty($term_meta)){ ?>
                                            <a href="<?php echo $term_link; ?>" class="album-cover">
                                               <span class="album-image"><img src="<?php echo $term_meta; ?>" alt=""></span>
                                            </a>
                                            <?php
                                            }
                                            if (count($imic_sermons_url_array) > 0) {
                                                echo '<div class="label label-primary album-count">' . count($imic_sermons_url_array) . __(' videos', 'framework') . '</div>';
                                                echo '&nbsp';
                                            }
                                            if (count($imic_sermon_attach_full_audio_array) > 0) {
                                                echo '<div class="label label-primary album-count">' . count($imic_sermon_attach_full_audio_array) . __(' audios', 'framework') . '</div>';
                                            }
                                           // If there was an error, continue to the next term.
                                            if (is_wp_error($term_link)) {
                                                continue;
                                            } else {
                                                echo '<h5><a href="' . $term_link . '">' . $sermonterms_data->name . '</a></h5>';
                                                echo '<a class="btn btn-default btn-sm" href="' . $term_link . '" >' . __('Play ', 'framework') . '<i class="fa fa-play"></i></a>';
                                            }
                                            ?>
                                        </div>
                                    </li>
                                    <?php
                                    if ($i == $number_of_sermon_albums)
                                        break;
                                    $i++;
                                }
                                ?>
                            </ul>
                        </section>
                        <?php }
                        $wp_query = clone $temp_wp_query;?>
                    </div>
                </div>
    <?php } ?>
        </div>
    </div>
<?php
get_footer();
?>
