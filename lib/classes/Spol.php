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

	    $debug = false;
	    if (!$debug && !empty($this->items))
	        return $this->items;
	    
		// nexttime is the filetime the cached json file should be refreshed.
		if ($debug || time() > $this->nexttime) {
			//$yt_channel_id = "UCcjkt-3-U8mogW8QtO9Yr6Q";
			//$url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . $yt_channel_id;
			// Office Speaking of Life feed
			$url="https://www.gci.org/feed/?post_type=videos&media-categories=speaking-of-life&attach=video";
	    		
			$xml = simplexml_load_file($url);

			$namespaces = $xml->getNamespaces(true); // get namespaces
			 
			$items = array();
			foreach ($xml->channel->item as $item) {
			  $tmp = new stdClass();
			  $tmp->id = trim((string) $item->children($namespaces['yt'])->videoId);
			  $tmp->title = trim((string) $item->title);
			  $tmp->author  = 'Grace Communion International';
			  $tmp->uri  = $xml->channel->link;
			  $tmp->updated =  date('Y-m-d', strtotime(trim((string) $item->pubDate)));
			  $tmp->link = trim((string) $item->link);
			  $tmp->url = trim((string) $item->enclosure->attributes()->url);
			  $tmp->thumbnail = 'https://i1.ytimg.com/vi/'.$tmp->id.'/hqdefault.jpg';
			  $tmp->description = trim((string) $item->description);
			    
			  
              // wp post attribs
              $tmp->post_title = $tmp->title;
			  $tmp->thumbnail_url = $tmp->thumbnail;
			  $tmp->permalink = $tmp->link;
			  $tmp->post_type = 'media-youtube-spol';
			  $tmp->post_date = $tmp->updated;
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
