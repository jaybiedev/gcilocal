<?php
            /** Upcoming Events Loop ** */
                $today = date('Y-m-d');
                $currentTime = date(get_option('time_format'));
                $event_category = wp_kses_post($instance['categories']);
				$imic_events_to_show_on = (!empty($instance['number_of_events']))? $instance['number_of_events'] : 4 ;
                $event_add = imic_recur_events('future','',$event_category,'');
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
                        $e_term = get_the_terms($value,'event-category');
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
                       	$upcomingEvents .='<h5><a href="' . $custom_event_url . '">' .$event_title . '</a>'.imicRecurrenceIcon($value).'</h5>                       	<span class="meta-data"><i class="fa fa-calendar"></i>' . date_i18n('l', $key) . $stime . $etime . '</span>';
                      	$upcomingEvents .='</div>';
                   		$upcomingEvents .='</div></li>';
                       	if (++$nos_event > $imic_events_to_show_on)
                      	break;
                    }
            	}
               	else{
                    $no_upcoming_events_msg = __('No Upcoming Events Found', 'framework');
            	}
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