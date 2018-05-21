<?php
$event_add = array();
$rec = 1;
$no_event = 0;
$today = date('Y-m-d');

/*$event_category = get_post_meta(get_the_ID(),'imic_advanced_event_list_taxonomy',true);
if(!empty($event_category)){
$event_categories= get_term_by('id',$event_category,'event-category');
$event_category= $event_categories->slug; }*/

$event_category = wp_kses_post($instance['categories']);
$perPage = (!empty($instance['listing_layout']['number_of_posts']))? $instance['listing_layout']['number_of_posts'] : 4 ;
$grid_column = (!empty($instance['listing_layout']['grid_column']))? $instance['listing_layout']['grid_column'] : 4 ;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$event_add = imic_recur_events('future','',$event_category,'');
$now = date('U');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count = 1;
$grid_item = 1;
$paginate = 1;
if($paged>1) {
$paginate = ($paged-1)*$perPage; $paginate = $paginate+1; }
 $google_events = getGoogleEvent();
if(!empty($google_events))
       $new_events = $google_events+$event_add;
	   else  $new_events = $event_add;
$TotalEvents = count($new_events);
if($TotalEvents%$perPage==0) {
$TotalPages = $TotalEvents/$perPage;
}
else {
$TotalPages = $TotalEvents/$perPage;
$TotalPages = $TotalPages+1;
}
if(!empty($new_events)){
ksort($new_events);
 echo '<ul class="grid-holder col-'.$grid_column.' events-grid">';
foreach ($new_events as $key => $value) {
if(preg_match('/^[0-9]+$/',$value)){
$google_flag =1;
}else{
$google_flag =2;
}

if($google_flag==1){
setup_postdata(get_post($value));
$eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
$eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
$eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
$eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
$event_dt_out = imic_get_event_timeformate($eventStartTime.'|'.$eventEndTime,$eventStartDate.'|'.$eventEndDate,$value,$key);
$event_dt_out = explode('BR',$event_dt_out);

$registration_status = get_post_meta($value,'imic_event_registration_status',true);
/** Event Details Manage **/
if($registration_status==1&&(function_exists('imic_get_currency_symbol'))) {
$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker','fa-money');	
}else {
$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker'); }
$stime = ""; $etime = "";
if($eventStartTime!='') { $stime = ' | ' .date_i18n(get_option('time_format'), $eventStartTime) ; }
if($eventEndTime!='') { $etime =  ' - '. date_i18n(get_option('time_format'),$eventEndTime); }
if($registration_status==1&&(function_exists('imic_get_currency_symbol'))) {
	$event_registration_fee = get_post_meta($value,'imic_event_registration_fee',true);
	$registration_charge = ($event_registration_fee=='')?'Free':imic_get_currency_symbol(get_option('paypal_currency_options')).get_post_meta($value,'imic_event_registration_fee',true);
$eventDetailsData = array($event_dt_out[1],$event_dt_out[0], get_post_meta($value,'imic_event_address',true),$registration_charge);	
/*
$eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true),$registration_charge);
*/
}else {
/*$eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true));*/
$eventDetailsData = array($event_dt_out[1],$event_dt_out[0], get_post_meta($value,'imic_event_address',true));
 }
$eventValues = array_filter($eventDetailsData, 'strlen');
}
if($count==$paginate&&$grid_item<=$perPage) { $paginate++; $grid_item++;
if($google_flag==1){
$frequency = get_post_meta($value,'imic_event_frequency',true); 
}
//if ('' != get_the_post_thumbnail($value)) {
echo '<li class="grid-item format-standard">';
if($google_flag==1){
$date_converted=date('Y-m-d',$key );
$custom_event_url =imic_query_arg($date_converted,$value); 
}
if($google_flag==2){
         $google_data =(explode('!',$value)); 
           $event_title=$google_data[0];
           $custom_event_url=$google_data[1];
           $stime = ""; $etime = "";
         $etime=$google_data[2];
     if($key!='') { $stime = ' | ' .date_i18n(get_option('time_format'), $key) ; }
if($etime!='') { $etime =  ' - '. date_i18n(get_option('time_format'),strtotime($etime)); }
      $eventAddress=$google_data[3];
     /* $eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime,$eventAddress);*/ 
	  $event_dt_out = imic_get_event_timeformate($key.'|'.$google_data[2],$key.'|'.$key,$value,$key);
      $event_dt_out = explode('BR',$event_dt_out);
	$eventDetailsData = array($event_dt_out[1],$event_dt_out[0],$eventAddress);
$eventValues = array_filter($eventDetailsData, 'strlen');
$eventDetailIcons = array('fa-calendar','fa-clock-o', 'fa-map-marker'); 
}
echo '<div class="grid-item-inner">';
if($google_flag==1){
echo '<a href="'.$custom_event_url.'" class="media-box">';
echo get_the_post_thumbnail($value, 'full');
echo '</a>';
$event_title=get_the_title($value);
}
//problem start
echo '<div class="grid-content">';
echo '<h3><a href="' . $custom_event_url. '">'.$event_title.'</a>'.imicRecurrenceIcon($value).'</h3>';
if($google_flag==1){
echo '<div class="page-content">';
//echo imic_excerpt(25);
echo '</div>';
}
//problem end
echo'</div>';
if(!empty($eventValues)){ 
echo '<ul class="info-table">';
$flag = 0;
foreach($eventDetailsData as $edata){
if(!empty($edata)){
echo '<li><i class="fa '.$eventDetailIcons[$flag].'"></i> '.$edata.' </li>';
}				
$flag++;	
}
echo '</ul>';
//}
echo '</div>
</li>';
 }} $count++; }
echo '</ul>';
wp_reset_postdata(); ?>
<?php if(!empty($instance['listing_layout']['show_pagination'])){ 
$TotalPages = floor($TotalPages);
if($TotalPages>1) {
pagination($TotalPages,$perPage); }
 } ?>
<?php
}
?>
           