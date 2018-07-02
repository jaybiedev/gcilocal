<?php
/**
* Retrive GCI SPOL RSS Feed 
*/

class Spol {

	public $file = "spol.json";
	public $nexttime = 0;
	public $items = [];

	public function __construct () {

	    $this->file = (get_template_directory() . "/../../uploads");
	    $this->file =  realpath($this->file);
	    
	    if ($this->file !== false) {
    	    $this->file .= "/spol.json";
    		if (file_exists($this->file)) {
    			$filetime = filemtime($this->file);
    			$this->nexttime = strtotime("+7 day", $filetime);
    		}
	    }
	}

	public function getAll() {

	    if (!empty($this->items))
	        return $this->items;
	    
		// nexttime is the filetime the cached json file should be refreshed.
		if (time() > $this->nexttime) {
			// $yt_channel_id = "UCgE-RnN9S3U_4zPz8VwvLmA";
			$yt_channel_id = "UCcjkt-3-U8mogW8QtO9Yr6Q";
			$url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . $yt_channel_id;

			$xml = simplexml_load_file($url);

			$namespaces = $xml->getNamespaces(true); // get namespaces
			 
			$items = array();
			foreach ($xml->entry as $item) {

			  $tmp = new stdClass();
			  $tmp->id = trim((string) $item->children($namespaces['yt'])->videoId);
			  $tmp->title = trim((string) $item->title);
			  $tmp->author  = trim((string) $item->author->name);
			  $tmp->uri  = trim((string) $item->author->uri);
			  $tmp->updated =  date('Y-m-d', strtotime(trim((string) $item->updated)));
			  $tmp->link = trim((string) $item->link->attributes()->href);
			 
			  // now for the data in the media:group
			  $MediaGroup = $item->children($namespaces['media'])->group;
			 
			  $tmp->url = trim((string) $MediaGroup->children($namespaces['media'])->content->attributes()->url);
			  $tmp->thumbnail = trim((string) $MediaGroup->children($namespaces['media'])->thumbnail->attributes()->url);
			  $tmp->description = trim((string) $MediaGroup->children($namespaces['media'])->description);
			  
              // wp post attribs
              $tmp->post_title = $tmp->title;
			  $tmp->thumbnail_url = $tmp->thumbnail;
			  $tmp->permalink = $tmp->link;
			  $tmp->post_type = 'media-youtube-spol';
			  $tmp->post_date = date('Y-m-d', strtotime(trim((string) $item->published)));
			  $tmp->post_modified = $tmp->updated;
			  
			  $remove_url_regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";			 
			  $tmp->caption = preg_replace($remove_url_regex, "", $tmp->description);
			  
			  $items[] = $tmp;
			}

			$fp = fopen($this->file, 'w');
			fwrite($fp, json_encode($items));
			fclose($fp);
		}

		$str = file_get_contents($this->file);
		$this->items = json_decode($str);
		
		return $this->items;
	}

	public function getFirst() {
		if (empty($this->items))
			$this->getAll();

		return $this->items[0];
	}
}