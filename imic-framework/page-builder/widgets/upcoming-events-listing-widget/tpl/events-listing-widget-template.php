<?php 	$post_title = wp_kses_post($instance['widget_title']);
	   	$number = wp_kses_post($instance['number_of_events']);
	   	$numberEvent = (!empty($number))? $number : 4 ;
		$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
		$allpostsurl = sow_esc_url($instance['allpostsurl']);
       	$event_category = wp_kses_post($instance['categories']);
	   	$EventHeading = (!empty($post_title))? $post_title : __('Upcoming Events','imic-framework-admin') ;
	   	$today = date('Y-m-d');
	   
				$event_add = imic_recur_events('future','nos',$event_category,'');
			  $nos_event = 1;
			   $google_events = getGoogleEvent();
                          if(!empty($google_events))
       $new_events = $google_events+$event_add;
	   else  $new_events = $event_add;
                        ksort($new_events);
                        if(!empty($new_events)){
						echo '<div class="listing events-listing"><header class="listing-header">'; ?>
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?><?php echo'<h3>'. $post_title .'</h3></header>';
                      echo '<section class="listing-cont"><ul>';
			  foreach($new_events as $key=>$value)
			  {     
                              if(preg_match('/^[0-9]+$/',$value)){
				  $eventTime = get_post_meta($value,'imic_event_start_tm',true);
				  if(!empty($eventTime)){
                                  $eventTime = strtotime($eventTime);
				  $eventTime = date_i18n(get_option('time_format'),$eventTime);
                                  }
                                  $date_converted=date('Y-m-d',$key );
                                  $custom_event_url= imic_query_arg($date_converted,$value);
                              $event_title=  get_the_title($value);
                                  }
                              else{
								 $google_data =(explode('!',$value)); 
								$event_title=$google_data[0];
							   $custom_event_url=$google_data[1];
							   $eventTime='';
							   if(!empty($key)){
							   $eventTime = ' | ' . date(get_option('time_format'), $key);
							   }
							  }
                                  echo '<li class="item event-item clearfix">
							  <div class="event-date"> <span class="date">'.date_i18n('d',$key).'</span> <span class="month">'.imic_global_month_name($key).'</span> </div>
							  <div class="event-detail">
                                                       <h4><a href="'.$custom_event_url.'">'.$event_title.'</a>'.imicRecurrenceIcon($value).'</h4>';
							$stime = ''; if($eventTime!='') { $stime = ' | '.$eventTime; }
							echo '<span class="event-dayntime meta-data">'.date_i18n( 'l',$key ).$stime.'</span> </div>
							<div class="to-event-url">
                        <div><a href="'. $custom_event_url .'" class="btn btn-default btn-sm">'. __('Details','framework') .'</a></div>
                      </div>
							</li>';
							if (++$nos_event > $numberEvent) break;
			  }
			echo '</ul></section></div>';
		}else{
			_e('No Upcoming Events Found','imic-framework-admin');		
		}
?>