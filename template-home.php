<?php
/*
  Template Name: Home
*/
get_header();
global $imic_options;

include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
include_once(get_template_directory() . "/lib/classes/Carousel.php");
include_once(get_template_directory() . "/lib/classes/BackgroundImage.php");

$front_page_id = get_option( 'page_on_front' );

// $custom_home = get_post_custom($frontpage_id);
// $front_page_id = get_the_ID();
$pageOptions = imic_page_design('',8); //page design options
imic_sidebar_position_module();
$show_featured_article = get_post_meta($front_page_id,'imic_featured_article',true);
$show_featured_media = get_post_meta($front_page_id,'imic_featured_media',true);
// $featured_media_id = get_post_meta($front_page_id,'imic_home_featured_media',true);

// flags for News and Events
$show_recent_news_area = get_post_meta($front_page_id,'imic_imic_recent_posts',true);
$show_recent_media_area = get_post_meta($front_page_id,'imic_imic_recent_media_area',true);
$show_recent_events_area = get_post_meta($front_page_id,'imic_imic_recent_events_area',true);
$show_upcoming_events =  get_post_meta($front_page_id,'imic_imic_upcoming_events',true); 
$show_recent_articles_area = get_post_meta($front_page_id,'imic_imic_recent_articles_area',true);
$show_galleries_area = get_post_meta($front_page_id,'imic_imic_galleries',true);

$cards_all_sections = array();
if ($show_recent_news_area == "1")
    $cards_all_sections[] = 'news';
if ($show_recent_media_area == "1")
    $cards_all_sections[] = 'media';
if ($show_recent_events_area == "1")
    $cards_all_sections[] = 'events';
if ($show_recent_articles_area == "1")
    $cards_all_sections[] = 'articles';
if ($show_galleries_area == "1")
    $cards_all_sections[] = 'gallery';
//
$cards_all = PostHelper::getHomePageCards($cards_all_sections, $front_page_id);

//$front_page_splash = get_post_field("imic_pages_Choose_slider_display", true);
$front_page_splash = get_post_meta($front_page_id,'imic_pages_Choose_slider_display',true);

if ($front_page_splash == '1') {
    include(locate_template('pages_slider.php'));
}
elseif (empty($front_page_splash) ) {
    include(locate_template('pages_banner.php'));
}
else {
    //
}

/** Upcoming Events Loop ** */
$temp_wp_query = clone $wp_query;
$today = date('Y-m-d');
$currentTime = date(get_option('time_format'));
$upcomingEvents = '';
$upcoming_events_category = get_post_meta($front_page_id, 'imic_upcoming_event_taxonomy',true);
if(!empty($upcoming_events_category)){
    $events_categories= get_term_by('id',$upcoming_events_category,'event-category');
    $upcoming_events_category= $events_categories->slug;
}
$imic_events_to_show_on = get_post_meta($front_page_id,'imic_events_to_show_on',true);
$imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
$event_add = imic_recur_events('future','nos',$upcoming_events_category,'');

$google_events = getGoogleEvent();
if(!empty($google_events))
    $new_events = $google_events + $event_add;
else
    $new_events = $event_add;

ksort($new_events);

if(!empty($new_events)){
    $nos_event = 1;
    foreach ($new_events as $key => $value)
    {
        $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
        $event_End_time = get_post_meta($value, 'imic_event_end_tm', true);
        $event_End_time = strtotime($event_End_time);
		$eventTime = strtotime($eventTime);
		$count_from = (isset($imic_options['countdown_timer'])) ? $imic_options['countdown_timer'] : '';

		if($count_from==1) {
		    $counter_time = date('G:i',$event_End_time);
		}
		else {
		    $counter_time = date('G:i',$eventTime);
		}

        if(preg_match('/^[0-9]+$/',$value)) {
       
		    if($eventTime!='') {
		        $eventTime = date_i18n(get_option('time_format'),$eventTime);
		    }
		
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
            }
		}
        else {
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
    }
}
$wp_query = clone $temp_wp_query;
?>
<!-- Start Notice Bar -->
<?php
$imic_custom_message = get_post_meta($front_page_id,'imic_custom_text_message',true);
$imic_latest_sermon_events = get_post_meta($front_page_id, 'imic_latest_sermon_events_to_show_on', true);
$imic_all_event_url= get_post_meta($front_page_id, 'imic_all_event_url', true);
$imic_upcoming_events_area = get_post_meta($front_page_id,'imic_upcoming_area',true);

if($imic_upcoming_events_area==1)  {
    if ((!empty($firstEventTitle) && $imic_latest_sermon_events == 'latest_event')||(!empty($firstEventTitle) && $imic_latest_sermon_events=='')) { ?>
        <div class="notice-bar">
        <div class="container">
        <?php $imic_going_on_events = get_post_meta($front_page_id, 'imic_going_on_events', true);

        if($imic_going_on_events==2) {
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

                if($days_extra>0) {
                    break;
                }

                if($event_st_time<date('U')&&$event_en_time>date('U')) {
                    $currently_running[$key]=$value;
                }
            }
            $going_nos_event = 1;
            $google_events = getGoogleEvent('goingEvent');

           if(!empty($google_events))
               $new_events = $google_events+$currently_running;
	       else
	           $new_events = $currently_running;
            ksort($new_events);

            if(!empty($new_events)) {
                $imic_custom_going_on_events_title = get_post_meta($front_page_id, 'imic_custom_going_on_events_title', true);
                $imic_custom_going_on_events_title=!empty($imic_custom_going_on_events_title)?$imic_custom_going_on_events_title:__('Going on Events','framework');
                echo '<div class="goingon-events-floater">';
                echo '<h4>'.$imic_custom_going_on_events_title.'</h4>';
                ?>
                <div class="goingon-events-floater-inner"></div>
                <div class="flexslider" data-arrows="yes" data-style="slide" data-pause="yes">
                <ul class="slides">

                <?php foreach ($new_events as $key => $value) {
                    if(preg_match('/^[0-9]+$/',$value)) {
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

                        if(!empty($stime)||!empty($etime)) {
                            $fa_clock='<i class="fa fa fa-clock-o"></i> ';
                        }
                        $date_converted=date('Y-m-d',$key );
                        $custom_event_url =imic_query_arg($date_converted,$value);
                        $event_title=get_the_title($value);
                    }
                    else {
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

                        if(!empty($stime)||!empty($etime)) {
                            $fa_clock='<i class="fa fa fa-clock-o"></i> ';
                        }
                    }
                    echo '<li>
                        <a href="'.$custom_event_url.'"><strong class="title">' . $event_title . '</strong></a>
                        <span class="time">'.$fa_clock.$stime.$etime.'</span>
                    </li>';
                    $going_nos_event++;
                } ?>
                </ul>
                </div>
        </div>
                <?php
            }
            $wp_query = clone $temp_wp_query;
        }?>

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6 notice-bar-title">
                <span class="notice-bar-title-icon hidden-xs"><i class="fa fa-calendar fa-3x"></i></span>
                <span class="title-note"><?php _e('Next', 'framework'); ?></span>
                <strong><?php _e('Upcoming Event', 'framework'); ?></strong>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6 notice-bar-event-title">

                <?php
                $specific_event_data='';
                $event_category= get_post_meta($front_page_id,'imic_advanced_event_taxonomy','true');

                if($event_category!='') {
                    $event_categories= get_term_by('id',$event_category,'event-category');

                    if(!empty($event_categories)) {
                        $event_category= $event_categories->slug;
                    }
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

                if(!empty($imic_all_event_url)||!empty($pages_e[0]->ID)) {
                    $imic_all_event_url = !empty($imic_all_event_url) ? $imic_all_event_url: get_permalink($pages_e[0]->ID); ?>
                    <div class="col-md-2 col-sm-6 hidden-xs">
                        <a href="<?php echo $imic_all_event_url ?>" class="btn btn-primary btn-lg btn-block">
                        <?php _e('All Events', 'framework'); ?></a>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
    elseif ($imic_latest_sermon_events == 'latest_sermon') {
        echo '<div class="notice-bar latest-sermon bg-navy navy">
                    <div class="row">';
                     require_once(get_template_directory() . "/lib/view/home/_latest_sermon.php");
                    echo '</div>
            </div>';
    }
    else {
        echo '<div class="notice-bar custom-message">
                <div class="container">
                    <div class="row">';
                    echo (do_shortcode($imic_custom_message));
                    echo '</div>
                </div>
            </div>';
    }

}
?><!-- End Notice Bar --> 

<!-- Start Home page Content -->
<div class="main" role="main">
    <div class="full">
        <?php  wp_reset_query();
        $home_page_content =  trim(get_the_content());
        if (!empty($home_page_content)) {?>
	        <div class="page-content">
        	    <?php echo $home_page_content;?>
            </div>
	    <?php }?>
		<?php if ($show_featured_article=="1") { ?>		
        <div class="silver onchurch nav-center featuredbox">            
            <!-- featured article -->
            <?php include_once(get_template_directory() . "/lib/view/home/_featured_article.php");?>
        </div>
		<?php } ?>
    </div>
    <?php if ($show_featured_media=="1")  { ?>
    <div class="sermon featuredbox navy" style="clear:both;"> 
        <?php require_once(get_template_directory() . "/lib/view/home/_featured_media.php"); // falls back to latest sermon, and latest spol?>
    </div>
    <?php } ?>
    <?php 
    if ($show_recent_news_area == "1" || $show_recent_media_area == "1"
        || $show_recent_events_area  == "1"|| $show_recent_articles_area  == "1"
        || $show_galleries_area == "1") {
    ?>
    <div class="container-fluid featuredbox onchurch">
        <header class="listing-header">
            <h1 class="text-align-center">News & Events</h1>
            <hr>
            <h2 class="text-align-center color-text-gray">WHAT'S HAPPENING</h2>
        </header>
                <!-- news and events -->
                <?php // include_once(get_template_directory() . "/lib/view/home/_news_events.php");?>
        <div class="nav-center">
            <div class="container">
                <ul class="nav nav-pills btn-group"> 
                    <li class="btn btn-default active"><a data-toggle="pill" href="#all-cards">ALL</a></li>
                    <?php if ($show_recent_news_area == '1'){?>
	                    <li class="btn btn-default"><a data-toggle="pill" href="#news">NEWS</a></li>
                	<?php };?>
                    <?php if ($show_recent_media_area == '1'){?>
	                    <li class="btn btn-default"><a data-toggle="pill" href="#media">MEDIA</a></li>
                	<?php };?>
                    <?php if ($show_recent_events_area == '1'){?>
                    	<li class="btn btn-default"><a data-toggle="pill" href="#events">EVENTS</a></li>
                	<?php };?>
                    <?php if ($show_recent_articles_area == '1'){?>
	                    <li class="btn btn-default"><a data-toggle="pill" href="#articles">ARTICLES</a></li>
                	<?php };?>
                    <?php if ($show_galleries_area == '1') {?>
                    	<li class="btn btn-default"><a data-toggle="pill" href="#gallery">PHOTO GALLERY</a></li>
                	<?php };?>
                </ul>
                <div class="tab-content">
                    <div id="all-cards" class="tab-pane fade in active">
                        <div class="listing all-listing">
                            <?php foreach ($cards_all as $Featurebox) { ?>
                                <div class="col-lg-4">
                                    <?php echo $Featurebox->render();?>
                                </div>
                            <?php } ?>
                        </div>		                
					</div>
                	<?php if ($show_recent_news_area == '1') {?>
	                    <div id="news" class="tab-pane fade ">
		                    <?php include_once(get_template_directory() . "/lib/view/home/_news.php");?>
                    	</div>
                	<?php }?>
                    <?php if ($show_recent_media_area == '1') {?>
                    	<div id="media" class="tab-pane fade">
	                        <?php include_once(get_template_directory() . "/lib/view/home/_media.php");?>
                        </div>
                	<?php }?>
                    <?php if ($show_recent_events_area == '1'){?>
                        <div id="events" class="tab-pane fade">
			                <?php include_once(get_template_directory() . "/lib/view/home/_events.php");?>
    					</div>
                	<?php }?>
	                <?php if ($show_recent_articles_area == '1') {?>                            
	                    <div id="articles" class="tab-pane fade">
                            <?php include_once(get_template_directory() . "/lib/view/home/_articles.php");?>
						</div>
                	<?php }?>
                    <?php if ($show_galleries_area == '1') {?>
                        <div id="gallery" class="tab-pane fade">
    		                <?php include_once(get_template_directory() . "/lib/view/home/_gallery.php");?>
    					</div>
                	<?php };?>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
<?php 
get_footer();
