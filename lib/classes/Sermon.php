<?php
/**
* Retrive Sermons
*/

class Sermon {

	public $items = [];
	public $sermons_category = null;
	public $posts_per_page = null;

	public function __construct () {
        //
	}

	public function getAll() {

	    if (!empty($this->items))
	        return $this->items;
	    
        $this->items = get_posts(array('post_type' => 'sermons',
            'sermons-category'=>$this->sermons_category,
            'post_status' => 'publish',
            'suppress_filters' => false,
            'posts_per_page' => $this->posts_per_page
        ));
		
		return $this->items;
	}

	public function getFirst() {
		if (empty($this->items))
			$this->getAll();

		return $this->items[0];
	}
}