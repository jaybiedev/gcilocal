<?php
/*
  Template Name: Events List
 */
get_header();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module(); ?>
<?php
function get_future_event($m,$term='')
{
	$m = $m+1;
	$data_post = array();
	$currentEventTime = date('Y-m');
	$month = date('Y-m', strtotime("+$m month", strtotime($currentEventTime)));
	$events = imic_recur_events('future','nos',$term,$month);
	$data_post['events'] = $events;
	$prev_month = date('Y-m',strtotime("+$m month", strtotime($currentEventTime)));
    $next_month = date('Y-m',strtotime("+$m month", strtotime($currentEventTime)));
	$data_post['prev_month'] = $prev_month;
	$data_post['next_month'] = $next_month;
	$data_post['cmonth'] = $month;
	if(empty($events) && $m<=12)
	{
		$data_post = get_future_event($m,$term);
		return $data_post;
	}
	return $data_post;
}

?>
<div class="container">
      <div class="row">
        	<div class="<?php echo $pageOptions['class']; ?>" id="content-col">
        	<?php 
			
			while(have_posts()):the_post();
			if($post->post_content!="") :
						echo '<div class="page-content">';
                              the_content();        
						echo '</div>';
                              echo '<div class="spacer-20"></div>';
                      endif;	
			endwhile; ?> 
        <div id="ajax_events"> 
        	<!-- Events Listing -->
            <div class="listing events-listing">
            <header class="listing-header">
            	<div class="row">
                	<div class="col-md-6 col-sm-6">
          				<h3><?php _e('All events', 'framework'); ?></h3>
                  </div>
                  <div class="listing-header-sub col-md-6 col-sm-6">
                    <?php 
						if(get_query_var('calendar'))
						{
							$currentEventTime = esc_attr(get_query_var('calendar'));
						}
						else
						{
							$currentEventTime = date('Y-m');
						}
						$prev_month = date('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
						$next_month = date('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
						
						/*$event_category = get_post_meta(get_the_ID(),'imic_advanced_event_list_taxonomy',true);
						if(!empty($event_category)){
						$event_categories= get_term_by('id',$event_category,'event-category');
						$event_category= $event_categories->slug; }*/
						
						$event_category = imic_get_term_category(get_the_ID(),'imic_advanced_event_list_taxonomy');
						$temp_wp_query = clone $wp_query;
						$today = date('Y-m-d');				  
						$before_week = date('Y-m-d', strtotime("-7 days"));
						$currentTime = date('Y-m-d');
						$events = imic_recur_events('','',$event_category,$currentEventTime);
						/*if(empty($events))
						{
						  $events_data = get_future_event(0,$event_category); 
						  $events = $events_data['events'];
						  $prev_month = $events_data['prev_month'];
						  $next_month = $events_data['next_month'];
						  $currentEventTime = $events_data['cmonth'];
						}*/
				  ?>
                  	<h5><?php echo date_i18n('F', strtotime($currentEventTime)); ?></h5>
                    	<nav class="next-prev-nav">
                    		<a href="javascript:" class="upcomingEvents" rel="<?php echo $event_category; ?>" id="<?php echo $prev_month; ?>"><i class="fa fa-angle-left"></i></a>
                    		<a href="javascript:" class="upcomingEvents" rel="<?php echo $event_category; ?>" id="<?php echo $next_month; ?>"><i class="fa fa-angle-right"></i></a>
                     	</nav>
                  </div>
              </div>
            </header>
            <section class="listing-cont">
              <ul>
              	<?php
                                $this_month_last = strtotime(date('Y-m-t 23:59'));
	                            $google_events = getGoogleEvent($this_month_last);
								if(!empty($google_events)) $new_events = $google_events+$events;
								else $new_events = $events;
				                ksort($new_events);
                                 if(!empty($new_events))
								 {
									foreach($new_events as $key=>$value) {
									if(preg_match('/^[0-9]+$/',$value))
									{
									  $eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
									  $eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
									  $eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
									  $eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
									  
									  $evstendtime = $eventStartTime.'|'.$eventEndTime;
									  $evstenddate = $eventStartDate.'|'.$eventEndDate;
									  
									  $event_dt_out = imic_get_event_timeformate( $evstendtime,$evstenddate,$value,$key);
			                          $event_dt_out = explode('BR',$event_dt_out);
									  
									   if($eventStartTime!='') 
									   { 
										 $eventStartTime = date(get_option('time_format'),$eventStartTime);
									   }
									   $date_converted=date('Y-m-d',$key );
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
									   if($eventTime!='') { $eventTime = date_i18n( get_option( 'time_format' ),$key); }
									 $eventEndTime =$google_data[2];
								  if($eventEndTime!='')
								   {
									   $eventEndTime = ' - '.date_i18n( get_option( 'time_format' ),strtotime($eventEndTime));
									}
								   $eventAddress=$google_data[3];
								  
							$event_dt_out = imic_get_event_timeformate($key.'|'.strtotime($google_data[2]),$key.'|'.$key,$value,$key);
						    $event_dt_out = explode('BR',$event_dt_out);
									} 
						 if($key>date('U')) {
			 ?>
                    <li id="<?php echo date('y-n-d',$key); ?>" class="item event-item event-id">	
                      <div class="event-date"> <span class="date"><?php echo date('d',$key); ?></span>
                       <span class="month"><?php echo imic_global_month_name($key); ?></span> </div>
                      <div class="event-detail">
                      <h4>
                      <a href="<?php echo $custom_event_url; ?>">
					   <?php echo $event_title; ?> </a><?php echo imicRecurrenceIcon($value); ?>
                      </h4>
                     <span class="event-dayntime meta-data">
					   <?php echo $event_dt_out[1].',&nbsp;&nbsp;'.$event_dt_out[0] ?>
                     </span> </div>
                      <div class="to-event-url">
                        <div><a href="<?php echo $custom_event_url; ?>" class="btn btn-default btn-sm"><?php _e('Details','framework'); ?></a></div>
                      </div>
                    </li> 
                                  <?php }
				 }
			}
                else{ ?>
			<li class="item event-item">	
                      <div class="event-detail">
                        <h4><?php _e('Sorry, there are no events for this month.','framework'); ?></h4>
                      </div>
                    </li>  
				<?php }?>
              </ul>
            </section>
          </div>
        </div>
     </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
      </div>
    </div>    
<?php get_footer(); ?>