<?php
if(is_front_page()){
    $page_on_front= get_option('page_on_front');
    if(!empty($page_on_front)){
  $home_id= get_option('page_on_front');
    }
}
else{
	$home_id = $id;
}

$custom_home = get_post_custom($home_id);
$height = get_post_meta($home_id,'imic_pages_slider_height',true);
$height = ($height == '') ? '480' : $height;
$breadpad = $height - 60;

$custom_title = get_post_meta($home_id,'imic_post_page_custom_title',true);
$custom_title_font_color = get_post_meta($home_id,'imic_post_page_custom_title_font_color',true);

if (empty($custom_title_font_color))
    $custom_title_font_color = "#fff";

$custom_subtitle = get_post_meta($home_id, 'imic_post_page_custom_subtitle', true);	


echo '<style type="text/css">' . "\n";
echo '.hero-slider{height:'.$height.'px;}';
echo '.hero-slider.flexslider ul.slides li{height:'.$height.'px;}';
echo '.body ol.breadcrumb{padding-top:'.$breadpad.'px;}';
echo "</style>" . "\n";
$imic_Choose_slider_display = get_post_meta($home_id, 'imic_pages_Choose_slider_display', true);
if ($imic_Choose_slider_display == 1) {
	$speed = (get_post_meta($home_id, 'imic_pages_slider_speed', true)!='')?get_post_meta($home_id, 'imic_pages_slider_speed', true):5000;
    $pagination = get_post_meta($home_id, 'imic_pages_slider_pagination', true);
    $auto_slide = get_post_meta($home_id, 'imic_pages_slider_auto_slide', true);
    $direction = get_post_meta($home_id, 'imic_pages_slider_direction_arrows', true);
    $effect = get_post_meta($home_id, 'imic_pages_slider_effects', true);

    if (count(get_post_meta($home_id, 'imic_pages_slider_image', false)) > 0) {
        ?><div style="position:relative;">
            <div class="hero-slider flexslider clearfix" data-autoplay=<?php echo $auto_slide; ?> data-pagination=<?php echo $pagination; ?> data-arrows=<?php echo $direction; ?> data-style=<?php echo $effect; ?> data-pause="yes" data-speed=<?php echo $speed; ?>>
                <ul class="slides">
                    <?php
                    $slider_counter=0;
                    foreach ($custom_home['imic_pages_slider_image'] as $custom_home_image) {
                    
                    	$slider_counter++;
                        $src = wp_get_attachment_image_src($custom_home_image, 'full');
                        $attachment_meta = imic_wp_get_attachment($custom_home_image);
                        $caption = $attachment_meta['caption'];
                    
                    	if ($slider_counter == 1 && !empty($custom_title)) {
                    		$caption = "<span class=\"desc\">
                    			<h1 style=\"color:{$custom_title_font_color}\">{$custom_title}</h1>
                    			<h3 style=\"color:{$custom_title_font_color}\">{$custom_subtitle}</h3>
                			</span>";
                        }
                        
                        $slide_meta = '';
                        $url = $attachment_meta['url'];
                        
                        if (!empty($url)) {
                            $slide_meta = '<a href="' . $url . '"></a>';
                        }
                        if (!empty($caption)) {
                            $slide_meta = '<span class="container">
                                                              <span class="slider-caption">
                                                                       <span>' . $caption . '</span>
                                                              </span>
                                               </span>';
                        }
                        if (!empty($caption) && ($url)) {
                            $slide_meta = '<a href="' . $url . '"><span class="container">
                                                              <span class="slider-caption">
                                                                       <span>' . $caption . '</span>
                                                              </span>
                                               </span></a>';
                        }
                        echo'<li class=" parallax" style="background-image:url(' . $src[0] . ');">' . $slide_meta . '</li>';
                    }
                    ?>
                </ul>
        </div>
        <div class="container">
              <ol class="breadcrumb" style="padding-top: 0;position: absolute;bottom: 0px;z-index: 22;">
                  <?php
                  if (function_exists('bcn_display_list')) {
                      bcn_display_list();
                  }
                  ?>
              </ol>
        </div>
        </div>
        <?php
    }
} else {
    $imic_select_revolution_from_list = get_post_meta($home_id, 'imic_pages_select_revolution_from_list', true);
	echo '<div class="slider-revolution-new">';
	$imic_select_revolution_from_list = preg_replace('/\\\\/', '', $imic_select_revolution_from_list);
    echo do_shortcode($imic_select_revolution_from_list); ?>
	<div class="container">
          <ol class="breadcrumb" style="padding-top: 0;position: absolute;bottom: 0px;z-index: 22;">
              <?php
              if (function_exists('bcn_display_list')) {
                  bcn_display_list();
              }
              ?>
          </ol>
    </div>
	</div>
    <?php 
}
?>