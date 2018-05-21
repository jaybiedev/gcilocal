<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');

class Carousel extends AbstractComponent
{

    function getImages() {

        $images = array();
        $category_id = get_cat_ID( 'FrontPage Carousel' );
        $Objects = $this->query($taxonomy='category', $category_id);

        foreach ($Objects->posts as $post_id) {
            $image = get_post($post_id);

            $destination_url = null;
            $custom_fields =  get_post_custom($image->ID);

            if (!empty($custom_fields['_destination_url']))
                $destination_url = $custom_fields['_destination_url'][0];

            // foreach ($custom_fields as $key=>$custom_field) {
            // }
            // $image = wp_get_attachment_image_src($post_id, 'full');
            // $image = wp_get_attachment_metadata($post_id, 'full');

            $images[] = array('url'=>$image->guid,
                    'title'=>$image->post_title,
                    'description'=>$image->post_content,
                    'caption'=>$image->post_excerpt,
                    'destination_url'=>$destination_url,
                    'href' => get_permalink( $image->ID ),
                    'alt' => get_post_meta( $image->ID, '_wp_attachment_image_alt', true ),
                );
        }

        return $images;
    }

    function getContent() {

        $max = get_field('number_of_banner_images');
        $image_list = $this->getAttribute('images');

        if (empty($max))
            $max = 3;

        $images = $image_list;
        if (count($image_list) > $max) {
            $rand_keys = array_rand($image_list, $max);
            $images = [];
            foreach($rand_keys as $key)
            {
                $images[] = $image_list[$key];
            }
        }

        $indicators = null;
        $items = null;

        for ($x=0; $x < count($images); $x++) {

            $image = $images[$x];

            $class = ($x == 0) ? 'active' : '';
            $url = $image['url'];
            $destination_url = $image['destination_url'];
            $alt = $image['alt'];
            $title = $image['title'];
            $caption = $image['caption'];
            $description = $image['description'];

            if (!empty($destination_url))
                $description .= "&nbsp;&nbsp;<a href=\"{$destination_url}\"><i class='fa fa-chevron-circle-right'></i></a>";

            $indicators .= "<li data-target=\"#savvy_wp_carousel\" data-slide-to=\"{$x}\" class=\"{$class}\"></li>\n";
            $items .= "<div class=\"item {$class}\">
                        <img src=\"{$url}\" alt=\"{$alt}\" style=\"width:100%;\" class=\"savvy_carousel_item_img\">
                        <div class=\"carousel-caption\" style='padding-bottom: 200px;'>
                          <h2>{$caption}</h2>
                          <p class='description'>{$alt}</p>
                        </div>
                </div>";
        }

        $html = <<<HTML
        <div id="savvy_wp_carousel" class="carousel slide" data-ride="carousel" style="height:100%;">
            <!-- Indicators -->
            <ol class="carousel-indicators" style="padding-bottom: 120px;">
                {$indicators}
            </ol>
        
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                {$items}
            </div>
        
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#savvy_wp_carousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#savvy_wp_carousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
HTML;

        return $html;
    }
}