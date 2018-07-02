<?php

$home_id = get_option( 'page_on_front' );
$events_per_page = get_post_meta($home_id, 'imic_events_to_show_on', true);
$events_per_page = !empty($events_per_page) ? $events_per_page : 4;

$upcoming_events_category = get_post_meta($home_id,'imic_upcoming_event_taxonomy',true);

$upcoming_events_category = get_post_meta($front_page_id, 'imic_upcoming_event_taxonomy',true);
if(!empty($upcoming_events_category)){
    $events_categories= get_term_by('id',$upcoming_events_category,'event-category');
    $upcoming_events_category= $events_categories->slug;
}
$imic_events_to_show_on = get_post_meta($front_page_id,'imic_events_to_show_on',true);
$imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
$event_add_ids = imic_recur_events('future','nos',$upcoming_events_category,'');

$google_events = getGoogleEvent();
if(!empty($google_events))
    $new_event_ids = $google_events + $event_add;
else
    $new_event_ids = $event_add_ids;

?>

<!-- Events Listing -->
<div class="listing events-listing">
    <?php
    if(count($new_event_ids) == 0) { ?>
        <!-- Events Listing -->
        <div class="col-lg-4 col-md-4 col-sm-6">
        	<div class="card">
        		<div class="view overlay"></div>
        		<div class="card-body">
        			<h4>No upcoming events.</h4>
        		</div>        		
        	</div>
    	</div>
    <?php } 
    else {
        $list_counter = 0;
    
        foreach ($new_event_ids as $key=>$post_id) {
            if ($list_counter > $events_per_page)
                break;
            
                if(preg_match('/^[0-9]+$/', $post_id)) {
                    $item = get_post($post_id);
                    
                    $eventStartTime =  get_post_meta($post_id, 'imic_event_start_tm', true);
                    $eventStartDate =  get_post_meta($post_id, 'imic_event_start_dt', true);
                    $eventEndTime   =  strtotime(get_post_meta($post_id, 'imic_event_end_tm', true));
                    $eventEndDate   =  strtotime(get_post_meta($post_id, 'imic_event_end_dt', true));

                    // override
                    $item->caption = date('F j, Y D.', strtotime($eventStartDate)) . " @{$eventStartTime}";
                }
            ?>
            <div class="col-lg-4 col-md-4 col-sm-3">
                <?php
                $Featurebox = new Featurebox();
                // inject Post
                $Featurebox->post_id = $item->ID;
                $Featurebox->Post = $item;
                echo $Featurebox->render();?>
            </div>
            <?php
            $list_counter++;
        }
    }
    ?>
</div>
