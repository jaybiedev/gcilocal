<?php
/*
  Template Name: Events Timeline
 */
get_header();
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$column_class = 9;  
}else{
$column_class = 12;  
}
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
?>
      <div class="container">
      <div class="row">
        	<div class="col-md-<?php echo $column_class ?>" id="content-col">
        	<?php 
			
			while(have_posts()):the_post();
			if($post->post_content!="") :
					echo '<div class="page-content">';
                              the_content();        
					echo '</div>';
                              echo '<div class="spacer-20"></div>';
                      endif;	
			endwhile; ?> 
        <ul class="timeline">
      <?php $temp_wp_query = clone $wp_query;
$today = date('Y-m-d');
$currentTime = date(get_option('time_format'));
$upcomingEvents = '';

/*$event_category = get_post_meta(get_the_ID(),'imic_advanced_event_list_taxonomy',true);
if(!empty($event_category)){
$event_categories= get_term_by('id',$event_category,'event-category');
$event_category= $event_categories->slug; }*/

$event_category = imic_get_term_category(get_the_ID(),'imic_advanced_event_list_taxonomy');
$event_view_type = get_post_meta(get_the_ID(),'imic_events_timeline_view',true);
if($event_view_type == ''){
	$event_view_type = 'future';
}		
	$event_add = imic_recur_events($event_view_type,'nos',$event_category,'');
	$nos_event = 1;
	$month_check = 1;
        $google_events = getGoogleEvent();
		if(!empty($google_events))
       $new_events = $google_events+$event_add;
	   else  $new_events = $event_add;
	   if($event_view_type == 'future'){
     		ksort($new_events);
	   } else {
		   krsort($new_events);
	   }
	$month_tag = '';
    foreach ($new_events as $key => $value) {
		$frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
       	$frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
		
		if($month_tag!=imic_global_month_name($key)) { $month_check=1; }
		$year_tag = date_i18n('Y',$key);
		if($month_check==1) {
		$month_tag = imic_global_month_name($key); } if($month_check==1) { $tag = '<div class="timeline-badge">'.$month_tag.'<span>'.$year_tag.'</span></div>'; } else { $tag = ''; } $month_check++;
		 if(preg_match('/^[0-9]+$/',$value))
		 {
		    $eventAddress = get_post_meta($value,'imic_event_address',true);
		    $eventContact = get_post_meta($value,'imic_event_contact',true);
            $date_converted=date('Y-m-d',$key);
            $custom_event_url =imic_query_arg($date_converted,$value);    
            $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
		    $eventEndTime = get_post_meta($value,'imic_event_end_tm',true);
			
			//covert to timestamp
			$eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
			$eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
			$eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
            $eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
			
			$event_dt_out = imic_get_event_timeformate($eventStartTime.'|'.$eventEndTime,$eventStartDate.'|'.$eventEndDate,$value,$key);
			$event_dt_out = explode('BR',$event_dt_out);
			
		$eventTime = strtotime($eventTime);
		if($eventTime!='')
		{ 
		    $eventTime = date_i18n( get_option( 'time_format' ),$eventTime);
		}
		$eventEndTime = strtotime($eventEndTime);
		if($eventEndTime!='')
		{ 
		   $eventEndTime = ' - '.date_i18n( get_option( 'time_format' ),$eventEndTime);
		}
        
        $stime = '';
        $setime = '';
        if ($eventTime != '') {
            $stime = ' | ' . $eventTime;
            $setime = $eventTime;
        }
                 $event_title=get_the_title($value);
        }
               else{
				  
             $google_data =(explode('!',$value));
             $event_title=$google_data[0];
           $custom_event_url=$google_data[1];
           $eventTime =$key;
           if($eventTime!='') { $eventTime = date_i18n( get_option( 'time_format' ),$key); }
         $eventEndTime =$google_data[2];
      if($eventEndTime!='') {
		   $eventEndTime = ' - '.date_i18n( get_option( 'time_format' ),strtotime($eventEndTime)); 
		}
      $eventAddress=$google_data[3];
	  
	  $event_dt_out = imic_get_event_timeformate($key.'|'.strtotime($google_data[2]),$key.'|'.$key,$value,$key);
	  $event_dt_out = explode('BR',$event_dt_out);
      }
		if($nos_event%2==0) { $class = 'timeline-inverted'; } else { $class = ''; }
        echo '<li class="'.$class.'">
              '.$tag.'
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h3 class="timeline-title"><a href="'.$custom_event_url.'">'.$event_title.'</a> '.imicRecurrenceIcon($value).'</h3>
                </div>
                <div class="timeline-body">
				
                    <ul class="info-table">
                      <li><i class="fa fa-calendar"></i>'.$event_dt_out[1].'</li>';
					  if(!empty($eventTime)) { 
                      echo '<li><i class="fa fa-clock-o"></i>'.$event_dt_out[0].'</li>'; }
					  if(!empty($eventAddress)) {
                      echo '<li><i class="fa fa-map-marker"></i> '.$eventAddress.'</li>'; }
					  if(!empty($eventContact)) {
                      echo '<li><i class="fa fa-phone"></i> '.$eventContact.'</li>'; }
                    echo '</ul>
                </div>
              </div>
            </li>';
    $nos_event++; } ?>
            
        </ul>
	</div>
        <?php if(is_active_sidebar($pageSidebar)) { ?>
            <!-- Start Sidebar -->
            <div class="col-md-3 sidebar" id="sidebar-col">
                <?php dynamic_sidebar($pageOptions['sidebar']); ?>
            </div>
            <!-- End Sidebar -->
         <?php }
         echo '</div></div>';
get_footer(); ?>