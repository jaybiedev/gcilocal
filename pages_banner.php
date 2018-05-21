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
$height = ($height == '') ? '480' : $height;
$breadpad = $height - 60;

$largeTopTxt = '';
$largeBtmTxt = '';
$smallTxt = '';
$bannerBtnLink = '';
$bannerBtnWin = '';
$bannerBtnTxt = '';

$banner_image_id = get_post_meta($home_id,'imic_header_image', true);
$src = wp_get_attachment_image_src($banner_image_id, 'Full');
$image_url = $src[0];

$css = "<style>\n";
//    $css .= ".body ol.breadcrumb  {padding-top: " .$breadpad . "px;}\n";
$css .= ".nav-backed-header.parallax  {background-image:url(" . $banner_image_url . ");}";
$css .= "</style>";
//echo $css;

$html =<<<HTML

<div class="banner" style="background:url({$image_url}) center top no-repeat;background-size:cover;height:{$height}px">
	<div class="inner">
            <span class="txt">
                <span class="desc">
                    <strong>{$largeTopTxt}</strong>
                    <span class="sub">{$largeBtmTxt}</span>
                    <span class="sub">{$smallTxt}</span>
                </span>
                <span class="btn large">
                    <a href="{$bannerBtnLink}" {$bannerBtnWin}>{$bannerBtnTxt}</a>
                </span>
            </span>
    </div><!--end .inner-->
</div><!--end .banner-->
HTML;

echo $html;