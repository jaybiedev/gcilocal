<?php
if (!defined('ABSPATH'))exit; 
include(__DIR__.'/google-api-php-client-master/src/Google/autoload.php'); 
 function GetCalendarEvents($calendar_id=null,$api_key=null,$curr_time=null,$max_results=10)
 {
	 if($calendar_id == null || $api_key == null) return false;
	 	//TELL GOOGLE WHAT WE'RE DOING
		$client = new Google_Client();
		$client->setApplicationName($calendar_id); //DON'T THINK THIS MATTERS
		$client->setDeveloperKey($api_key); //GET AT AT DEVELOPERS.GOOGLE.COM
		$cal = new Google_Service_Calendar($client);
		//THE CALENDAR ID, FOUND IN CALENDAR SETTINGS. IF YOUR CALENDAR IS THROUGH GOOGLE APPS
		//YOU MAY NEED TO CHANGE THE CENTRAL SHARING SETTINGS. THE CALENDAR FOR THIS SCRIPT
		//MUST HAVE ALL EVENTS VIEWABLE IN SHARING SETTINGS.
		//$calendarId = 'you@gmail.com';
		//TELL GOOGLE HOW WE WANT THE EVENTS
		$params = array(
		//CAN'T USE TIME MIN WITHOUT SINGLEEVENTS TURNED ON,
		//IT SAYS TO TREAT RECURRING EVENTS AS SINGLE EVENTS
			'singleEvents' => true,
			'orderBy' => 'startTime',
			'timeMin' => $curr_time,//ONLY PULL EVENTS STARTING TODAY
			//'timeMax' =>date(DATE_ATOM, strtotime($curr_time)),
			'maxResults' => $max_results //ONLY USE THIS IF YOU WANT TO LIMIT THE NUMBER
						  //OF EVENTS DISPLAYED
		 
		);
		//THIS IS WHERE WE ACTUALLY PUT THE RESULTS INTO A VAR
		$events = $cal->events->listEvents($calendar_id, $params); 
		$calTimeZone = $events->timeZone; //GET THE TZ OF THE CALENDAR
		
		//SET THE DEFAULT TIMEZONE SO PHP DOESN'T COMPLAIN. SET TO YOUR LOCAL TIME ZONE.
		//date_default_timezone_set($calTimeZone);
		 
    //START THE LOOP TO LIST EVENTS
	$i = 0;
	$googleEvents =array();
    foreach ($events->getItems() as $event) 
	{
        //Convert date to month and day
         $eventDateStr = $event->start->dateTime;
		 $temp_timezone = $event->start->timeZone;
          //THIS OVERRIDES THE CALENDAR TIMEZONE IF THE EVENT HAS A SPECIAL TZ
         if (!empty($temp_timezone)) 
		 {
            $timezone = new DateTimeZone($temp_timezone); //GET THE TIME ZONE
                 //Set your default timezone in case your events don't have one
         } 
		 else 
		 {
			  $timezone = new DateTimeZone($calTimeZone);
         }
         if(empty($eventDateStr))
         {
             // it's an all day event
             $eventDateStr = $event->start->date;
         }
		 $eventEndTime = $event->end->dateTime;
         if(empty($eventEndTime))
         {
             $eventEndTime = '23:59';
         }
		 else
		 {
			  $eventEnd = new DateTime($eventEndTime,$timezone);
			  $eventEndTime = $eventEnd->format("H:i");;
		 }
         
         $eventdate = new DateTime($eventDateStr,$timezone);
 		 $link = $event->htmlLink;
         $TZlink = $link . "&ctz=" . $calTimeZone; //ADD TZ TO EVENT LINK
         //PREVENTS GOOGLE FROM DISPLAYING EVERYTHING IN GMT
            $newmonth = $eventdate->format("Y-m-d H:i");//CONVERT REGULAR EVENT DATE TO LEGIBLE MONTH
            $newday = $eventdate->format("j");//CONVERT REGULAR EVENT DATE TO LEGIBLE DAY
			$googleEvents[$i]['start_time']  = $newmonth;
			$googleEvents[$i]['end_time']    = $eventEndTime;
			$googleEvents[$i]['url']         = $link;
			$googleEvents[$i]['title']       = $event->summary;
			$googleEvents[$i]['event_day']   = $newday; 
			$i++;
    }
	return $googleEvents;
  }
?>