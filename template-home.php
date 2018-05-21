<?php
/*
  Template Name: Home
*/
get_header();
global $imic_options;

include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/Carousel.php");
include_once(get_template_directory() . "/lib/classes/BackgroundImage.php");


$custom_home = get_post_custom(get_the_ID());
$home_id = get_the_ID();
$pageOptions = imic_page_design('',8); //page design options
imic_sidebar_position_module();


$front_page_splash = get_post_field("imic_pages_Choose_slider_display", true);

if ($front_page_splash == '1') {
    include(locate_template('pages_slider.php'));
}
elseif ($front_page_splash == '') {
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
$upcoming_events_category = get_post_meta(get_the_ID(),'imic_upcoming_event_taxonomy',true);
if(!empty($upcoming_events_category)){
    $events_categories= get_term_by('id',$upcoming_events_category,'event-category');
    $upcoming_events_category= $events_categories->slug;
}
$imic_events_to_show_on = get_post_meta(get_the_ID(),'imic_events_to_show_on',true);
$imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
$event_add = imic_recur_events('future','nos',$upcoming_events_category,'');

$google_events = getGoogleEvent();
if(!empty($google_events))
    $new_events = $google_events+$event_add;
else
    $new_events = $event_add;

ksort($new_events);

// Feature Box
$has_feature_box = get_post_meta($home_id,'imic_imic_featured_blocks',true);
$featured_blocks = get_post_meta($home_id,'imic_home_row_featured_blocks',true);
$featured_blocks_ids = preg_split('@[\s,]+@', get_post_meta($home_id,'imic_home_featured_blocks',true), NULL, PREG_SPLIT_NO_EMPTY);


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
$imic_custom_message = get_post_meta($home_id,'imic_custom_text_message',true);
$imic_latest_sermon_events = get_post_meta($home_id, 'imic_latest_sermon_events_to_show_on', true);
$imic_all_event_sermon_url= get_post_meta($home_id, 'imic_all_event_sermon_url', true);
$imic_upcoming_events_area = get_post_meta($home_id,'imic_upcoming_area',true);

if($imic_upcoming_events_area==1)  {
    if ((!empty($firstEventTitle) && $imic_latest_sermon_events == 'letest_event')||(!empty($firstEventTitle) && $imic_latest_sermon_events=='')) { ?>
        <div class="notice-bar">
        <div class="container">
        <?php $imic_going_on_events = get_post_meta($home_id, 'imic_going_on_events', true);

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
                $imic_custom_going_on_events_title = get_post_meta($home_id, 'imic_custom_going_on_events_title', true);
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
                $event_category= get_post_meta($home_id,'imic_advanced_event_taxonomy','true');

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

                if(!empty($imic_all_event_sermon_url)||!empty($pages_e[0]->ID)) {
                    $imic_all_event_sermon_url = !empty($imic_all_event_sermon_url) ? $imic_all_event_sermon_url: get_permalink($pages_e[0]->ID); ?>
                    <div class="col-md-2 col-sm-6 hidden-xs">
                        <a href="<?php echo $imic_all_event_sermon_url ?>" class="btn btn-primary btn-lg btn-block">
                        <?php _e('All Events', 'framework'); ?></a>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
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
    <div class="full">
        <div class="container">
            <div class="page-content">
                <?php  wp_reset_query();
                if ($post->post_content != "") {?>
                    <?php echo the_content();?>
                <?php }?>
            </div>
        </div>
        <div class="silver onchurch nav-center featuredbox">
            <!-- <header class="listing-header">
                <h1 class="text-align-center color-text-white">Featured</h1>
                <hr>
                <h2 class="text-align-center color-text-white">Articles for reading</h2>
            </header>
            -->

            <!-- articles -->
            <?php include_once(get_template_directory() . "/lib/view/home/_latest_article.php");?>
        </div>
    </div>
    <div class="sermon featuredbox navy" style="clear:both;">
        <?php require_once(get_template_directory() . "/lib/view/home/_latest_sermon.php");?>
    </div>
    <div class="container-fluid featuredbox onchurch">
        <header class="listing-header">
            <h1 class="text-align-center">News & Events</h1>
            <hr>
            <h2 class="text-align-center color-text-gray">WHAT'S HAPPENING</h2>
        </header>
        <div class="col-lg-12 content">
                <!-- news and events -->
                <?php include_once(get_template_directory() . "/lib/view/home/_news_events.php");?>
        </div>
        <div class="onchurch nav-center featuredbox">
            <div class="container">
                <ul class="nav nav-pills btn-group">
                    <li class="btn btn-default active"><a data-toggle="pill" href="#media">MEDIA</a></li>
                    <li class="btn btn-default"><a data-toggle="pill" href="#events">EVENTS</a></li>
                    <li class="btn btn-default"><a data-toggle="pill" href="#spol">BLOG / ARTICLES</a></li>
                    <li class="btn btn-default"><a data-toggle="pill" href="#spol">PHOTO GALLERY</a></li>
                </ul>
                <div class="tab-content">
                    <div id="media" class="tab-pane fade in active">
                        <div class="container-fluid card-cascade wider">
                            <?php require_once(get_template_directory() . "/lib/view/home/_spol.php");?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Featured Gallery -->
<?php
    $gallery_category = get_post_meta($home_id,'imic_home_gallery_taxonomy', true);

    if(!empty($gallery_category)){
        $gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
        $gallery_category= $gallery_categories->slug;
    }

    $imic_imic_galleries = get_post_meta($home_id,'imic_imic_galleries',true);
    $posts_per_page = get_post_meta($home_id,'imic_galleries_to_show_on',true);
    $posts_per_page=!empty($posts_per_page)?$posts_per_page:3;
    $temp_wp_query = clone $wp_query;
    $gallery_bg_image_id = get_post_meta($home_id,'imic_galleries_background_image',true);
    $gallery_bg_image = wp_get_attachment_image_src($gallery_bg_image_id, 'full');
    query_posts(array(
        'post_type' => 'gallery',
        'gallery-category' => $gallery_category,
        'posts_per_page' => $posts_per_page,
    ));

    if (have_posts()&&$imic_imic_galleries==1):
       $gallery_size = imicGetThumbAndLargeSize();
       $size_thumb =$gallery_size[0];
       $size_large =$gallery_size[1];
      ?>
        <div class="featured-gallery <?php if($gallery_bg_image != ''){echo 'parallax parallax8';} ?>" <?php if($gallery_bg_image != ''){echo 'style="background-image:url('.$gallery_bg_image[0].');"';} ?>>
            <div class="container">
                <div class="row">
                    <?php
                    echo '<div class="col-md-3 col-sm-3">';
                    $imic_custom_gallery_title = !empty($custom_home['imic_custom_gallery_title'][0]) ? $custom_home['imic_custom_gallery_title'][0] : __('Updates from our gallery', 'framework');
                    echo'<h4>' . $imic_custom_gallery_title . '</h4>';
                    $imic_custom_more_galleries_title = !empty($custom_home['imic_custom_more_galleries_title'][0]) ? $custom_home['imic_custom_more_galleries_title'][0] : __('More Galleries', 'framework');
                    $pages = get_pages(array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'template-gallery-pagination.php'
                    ));
                    $imic_custom_more_galleries_url = !empty($custom_home['imic_custom_more_galleries_url'][0]) ? $custom_home['imic_custom_more_galleries_url'][0] : get_permalink($pages[0]->ID);
                    echo'<a href="' . $imic_custom_more_galleries_url . '" class="btn btn-default btn-lg">' . $imic_custom_more_galleries_title . '</a>';
                    echo '</div>';

                    while (have_posts()):

                        the_post();
                        $custom = get_post_custom(get_the_ID());
                        $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
                        $thumb_id=get_post_thumbnail_id(get_the_ID());

                        if(!empty($imic_gallery_images)) {
                          $gallery_img = $imic_gallery_images;
                        } else {
                          $gallery_img = '';
                        }
                         $post_format_temp = get_post_format();

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
        <?php
    endif;

    wp_reset_query();
    //-- End Featured Gallery --
    get_footer();
?>