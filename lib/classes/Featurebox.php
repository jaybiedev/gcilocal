<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');
require_once(get_template_directory() . '/lib/classes/Post.php');

class Featurebox extends AbstractComponent  {

    public $post_id;
    public $image_url;
    public $title;
    public $excerpt;
    public $description;
    public $Post;
    
    private $front_page_id;

    function __construct($post_id=null) {

        if (!empty($post_id)) {
            $this->post_id = $post_id;
            $this->Post = new Post($post_id);
        }
        /*
         * $title = get_post(get_post_thumbnail_id())->post_title; //The Title
         * $caption = get_post(get_post_thumbnail_id())->post_excerpt; //The Caption
         * $description = get_post(get_post_thumbnail_id())->post_content; // The Descriptio
         */
    }


    function render() {

        //if ( ! get_post($this->post_id) )
          //  return '';

        //$image_url = get_the_post_thumbnail($this->post_id, $size = 'post-thumbnail');
        // $title = get_the_title($this->post_id);
        $html = null;
        switch ($this->Post->post_type) {
            case 'sermons':
                $html = $this->getSermonContent();
                break;
            case 'youtube':
                $html = $this->getYoutubeContent();
                break;
            case 'media-youtube-spol':
            case 'media-youtube':
            case 'media-sermon':
                $html = $this->getMediaContent();
                break;
            case 'gallery':
                $html = $this->getGalleryContent();
                break;
            default:
                $html = $this->getContent();
                break;
        }

        return $html;
    }

    function getImageUrl() {

        if (!empty($this->Post->thumbnail_url))
            $this->image_url = $this->Post->thumbnail_url;

        if (empty($this->image_url)) {
            $this->image_url = get_the_post_thumbnail_url($this->post_id);
        }

        return $this->image_url;
    }
    
    function getPermalink() {
        if (empty($this->Post->permalink)) {
            $this->Post->permalink = get_permalink($this->Post);
        }
        return $this->Post->permalink;        
    }

    function getTitle() {
        return $this->Post->post_title;
    }

    function getContent() {

        $caption = substr($this->Post->caption, 0,100);

        //if (empty($caption))
        //    $caption = $this->Post->post_content;

        if (strlen($caption) > 100) {
            $caption = substr($caption, 0, 100);
            $caption .= "...";
        }

        //             <img src="{$this->Post->thumbnail_url}" class="img-fluid" alt="{$this->Post->post_title}">
        $image_url = $this->getImageUrl();
        $permalink = $this->getPermalink();
        $html =<<<HTML
<div class="card">

        <!--Card image-->
        <div class="view overlay" style="background:url('{$image_url}')">
            <a href="#">
                <div class="mask rgba-white-slight"></div>
            </a>
        </div>

        <!--Card content-->
        <div class="card-body">
            <!--Title-->
            <h4 class="card-title">{$this->Post->post_title}</h4>
            <!--Text-->
            <p class="card-text">{$caption}</p>
            <a href="{$permalink}" class="btn btn-secondary">LEARN MORE</a>
        </div>

    </div>
HTML;

        return $html;
    }

    // photo gallery
    function getGalleryContent() {
        
        $caption = substr($this->Post->caption, 0,100);
        
        //if (empty($caption))
        //    $caption = $this->Post->post_content;
        
        if (strlen($caption) > 100) {
            $caption = substr($caption, 0, 100);
            $caption .= "...";
        }
        
        //             <img src="{$this->Post->thumbnail_url}" class="img-fluid" alt="{$this->Post->post_title}">
        $image_url = $this->getImageUrl();
        $permalink = $this->getPermalink();
        $html =<<<HTML
<a href="{$permalink}">
    <div class="card gallery">
        <!--Card image-->
        <div class="view overlay" style="background:url('{$image_url}')">
        </div>        
    </div>
</a>
HTML;
        
        return $html;
    }
    
    // SPOL or Sermons
    function getMediaContent() {
        
        // $caption = substr($this->Post->caption, 0,100);
        $caption = date('F j, Y', strtotime($this->Post->post_date));
        
        if (strlen($caption) > 100) {
            $caption = substr($caption, 0, 100);
            $caption .= "...";
        }
        
        $permalink = $this->Post->permalink;

	$target = "target='gcitab'";
        if ($this->Post->post_type == 'media-youtube-spol') {
            $spol_url= $this->getDestinationUrl('imic_media_spol_page_url');

            if (!empty($spol_url)) {
                $permalink = $spol_url . "?yt={$this->Post->ID}&title={$this->Post->post_title}#spol-play";
       		$target = "";
	    }
	}
                
        $image_url = $this->getImageUrl();
        $html =<<<HTML
<div class="card">

        <!--Card image-->
        <div class="view overlay" style="background:url('{$image_url}')">
            <a href="{$permalink}" {$target}>
            <i class="fa fa-play-circle" post-type="{$this->Post->post_type}" media-url="{$this->Post->permalink}"></i>
            </a>
        </div>
        
        <!--Card content-->
        <div class="card-body">
            <!--Title-->
            <h4 class="card-title">{$this->Post->post_title}</h4>
            <!--Text-->
            <p class="card-text">{$caption}</p>
            <!--Text-->
            <p class="card-text uppercase">{$this->Post->author}</p>
        </div>
        
    </div>
HTML;
        
        return $html;
    }
    
    function getYoutubeContent() {

        $caption = substr($this->Post->caption, 0,100);
        if (strlen($this->Post->caption) > 100)
            $caption .= "...";

        //             <img src="{$this->Post->thumbnail_url}" class="img-fluid" alt="{$this->Post->post_title}">

        $source_url = "https://www.youtube.com/embed/{$this->Post->id}";
        $html =<<<HTML
<div class="card">

        <!--Card image-->
        <div class="view overlay">
            <iframe width="100%" height="100%" src="{$source_url}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <!--Card content-->
        <div class="card-body">
            <!--Title-->
            <h4 class="card-title"><a href="{$source_url}" target="_blank">{$this->Post->post_title}</a></h4>
            <!--Text-->
            <p class="card-text" title="{$caption}">{$caption}</p>
            <a href="//gci.org/spol" target="_blank" class="btn btn-secondary">MORE SPEAKING OF LIFE</a>
        </div>

    </div>
HTML;

        return $html;
    }

    private function getDestinationUrl($for) {
        
        $url = '';
        if (empty($this->front_page_id))
            $this->front_page_id = get_option( 'page_on_front' );
        
        if (!empty($for))
            $url= get_post_meta($this->front_page_id, $for, true);
    
        return $url;
    }

    public function getSermonContent() {
        $custom = get_post_custom($this->post_id);
        $attach_full_audio = imic_sermon_attach_full_audio($this->post_id);

        $speakers = get_the_term_list($this->post_id, 'sermons-speakers', '', ', ', '' );
        $video_url = $custom['imic_sermons_url'][0];

        $frontpage_id = get_option( 'page_on_front' );
        $all_sermon_url= $this->getDestinationUrl('imic_all_sermon_url');
        if (empty($all_sermon_url))
            $all_sermon_url .= "/sermons";

        $media_html = null;
        $image_url = get_the_post_thumbnail_url($this->post_id);
        $content = $this->Post->post_content;
	if (!empty($content)) {
		$media_html =<<<HTML
<div class="page-content" style="margin:-50px auto;width:90%, line-height: 1.7rem; color: #efefef;">{$content}</div>
HTML;
	}

        if (!empty($video_url)) {
            $media_html .= "<div class='featured-media'>" . imic_video_embed($custom['imic_sermons_url'][0], '100%', '100%', 0) . "</div>";
        }
        elseif (!empty($attach_full_audio)) {
            $media_html .= '<audio class="audio-player" src="' . $attach_full_audio . '" type="audio/mp3" controls></audio>';
        }
        elseif (!empty($image_url)) {
        	$media_html .= "<div class='featured-media-image' 
                                 style=\"margin-top: 20px;background:url('{$image_url}') no-repeat;background-size:contain;\">
                       </div>";
	}
        $html =<<<HTML
        <div class="card text-align-center">
            <div class="view overlay" style="height:auto;">{$media_html}</div>
            <div class="card-body">
                <!--Title-->
                <h2 class="card-title text-align-center"><a href="{$this->Post->permalink}" class="text-primary">{$this->Post->post_title}</a></h2>
                <!--Text-->
                <!-- <p class="card-text"  title="{$this->Post->caption}">{$this->Post->caption}</p> -->
                <h3 class="color-text-light-gray">{$speakers} - {$this->Post->post_date_formatted}</h3>
                <a href="{$all_sermon_url}" class="btn btn-secondary">MORE SERMONS</a>
            </div>
        </div>
HTML;


        return $html;

    }

    public static function getFeaturedPostIds($post_id)
    {
        $meta = get_post_meta($post_id);
        $featured_post_ids = preg_split('@[\s,]+@', $meta['featured_ids'][0], NULL, PREG_SPLIT_NO_EMPTY);
        return $featured_post_ids;
    }

}
