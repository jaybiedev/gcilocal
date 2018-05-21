<?php
global $home_id;
$custom_home = get_post_custom($home_id);
$imic_Choose_slider_display = get_post_meta($home_id, 'imic_Choose_slider_display', true);
if ($imic_Choose_slider_display == 0) {
	$speed = (get_post_meta($home_id, 'imic_slider_speed', true)!='')?get_post_meta($home_id, 'imic_slider_speed', true):5000;
    $pagination = get_post_meta($home_id, 'imic_slider_pagination', true);
    $auto_slide = get_post_meta($home_id, 'imic_slider_auto_slide', true);
    $direction = get_post_meta($home_id, 'imic_slider_direction_arrows', true);
    $effect = get_post_meta($home_id, 'imic_slider_effects', true);
   $slider_image=get_post_meta($home_id, 'imic_slider_image', false);
   if (count($slider_image) > 0) {
        ?>
            <div class="hero-slider flexslider clearfix" data-autoplay=<?php echo $auto_slide; ?> data-pagination=<?php echo $pagination; ?> data-arrows=<?php echo $direction; ?> data-style=<?php echo $effect; ?> data-pause="yes" data-speed=<?php echo $speed; ?>>
                <ul class="slides">
                    <?php
                    foreach ($slider_image as $custom_home_image) {
                        $src = wp_get_attachment_image_src($custom_home_image, 'full');
                        $attachment_meta = imic_wp_get_attachment($custom_home_image);
                        $caption = $attachment_meta['caption'];
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
        <?php
    }
} else {
    $imic_select_revolution_from_list = get_post_meta($home_id, 'imic_select_revolution_from_list', true);
	echo '<div class="slider-revolution-new">';
	$imic_select_revolution_from_list = preg_replace('/\\\\/', '', $imic_select_revolution_from_list);
    echo do_shortcode($imic_select_revolution_from_list);
	echo '</div>';
}
?>