<?php
if(is_front_page()){
    $page_on_front= get_option('page_on_front');
    if(!empty($page_on_front)){
  $home_id= get_option('page_on_front');
    }
}else{
$home_id = $id;
}

// $custom_home = get_post_custom($home_id);
$height = (int)get_post_meta($home_id,'imic_pages_slider_height',true);
$height = (($height == '')||($height == '0')) ? '480' : $height;
$breadpad = $height - 60;

$custom_title = get_post_meta($home_id,'imic_post_page_custom_title',true);
$custom_title_font_color = get_post_meta($home_id,'imic_post_page_custom_title_font_color',true);

if (empty($custom_title_font_color))
    $custom_title_font_color = "#fff";

$custom_subtitle = get_post_meta($home_id, 'imic_post_page_custom_subtitle', true);
$bannerBtnLink = '';
$bannerBtnWin = '';
$bannerBtnTxt = '';

$banner_image_id = get_post_meta($home_id,'imic_header_image', true);
$src = wp_get_attachment_image_src($banner_image_id, 'Full');
$image_url = $src[0];

if (empty($banner_image_url)) {
    $banner_image_url = get_template_directory_uri() . "/images/default-banner.png";
}

$css = "<style>\n";
//    $css .= ".body ol.breadcrumb  {padding-top: " .$breadpad . "px;}\n";
$css .= ".nav-backed-header.parallax  {background-image:url(" . $banner_image_url . ");}";
$css .= "</style>";
//echo $css;

$html =<<<HTML

<div class="banner" style="position:relative;background:url({$image_url}) center top no-repeat;background-size:cover;height:{$height}px">
	<div class="inner">
            <span class="txt">
               <span class="desc">
                    <h1 style="color:{$custom_title_font_color}">{$custom_title}</h1>
                    <h3 style="color:{$custom_title_font_color}">{$custom_subtitle}</h3>
                </span>
                <span class="btn large">
                    <a href="{$bannerBtnLink}" {$bannerBtnWin}>{$bannerBtnTxt}</a>
                </span>
            </span>
    </div><!--end .inner-->
</div><!--end .banner-->
HTML;

echo $html;