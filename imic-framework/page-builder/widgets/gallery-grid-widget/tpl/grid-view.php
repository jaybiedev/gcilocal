<?php
global $imic_options;
$custom_gallery_pagination = get_post_custom(get_the_ID());
$posts_per_page = $custom_gallery_pagination['number_of_posts'][0];
$the_categories = wp_kses_post($instance['categories']);
$post_title = wp_kses_post($instance['title']);
$grid_column = (!empty($instance['grid_column']))? $instance['grid_column'] : 4 ;
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
$gallery_size = imicGetThumbAndLargeSize();
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
$size_thumb =$gallery_size[0];
$size_large =$gallery_size[1];
$custom_gallery_filter = get_post_custom(get_the_ID());
$gallery_category = get_post_meta(get_the_ID(),'imic_advanced_gallery_taxonomy',true);
if(!empty($gallery_category)){
$gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
if(!empty($gallery_categories)){
$gallery_category= $gallery_categories->slug;}
else{
$gallery_category='';    
}}
?>
<?php query_posts( array ( 'post_type' => 'gallery', 'gallery-category' => ''. $the_categories .'', 'posts_per_page' => $numberPosts, 'paged' => get_query_var('paged') ) );
if(have_posts()): 
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo $allpostsurl; ?>" class="btn btn-primary pull-right push-btn"><?php echo $allpostsbtn; ?></a><?php } ?>
<h3 class="widgettitle"><?php echo $post_title; ?></h3>
</div>
<?php } 
if($instance['filters']){ ?>
        <ul class="nav nav-pills sort-source" data-sort-id="gallery" data-option-key="filter">
        <?php $gallery_cats = get_terms("gallery-category");?>
            <li data-option-value="*" class="active"><a href="#"><i class="fa fa-th"></i> <span><?php _e('Show All', 'framework'); ?></span></a></li>
        <?php foreach($gallery_cats as $gallery_cat) { ?>
            <li data-option-value=".format-<?php echo $gallery_cat->slug; ?>"><a href="#"><i class="fa <?php echo $gallery_cat->description; ?>"></i> <span><?php echo $gallery_cat->name; ?></span></a></li>
        <?php } ?>
        </ul>
    <div class="clearfix spacer-30"></div>
<?php }
?>
<?php 
if ($grid_column == 6) {
$class = 'col-md-2 col-sm-2';
}elseif ($grid_column == 3) {
$class = 'col-md-4 col-sm-4';
} elseif ($grid_column == 4) {
$class = 'col-md-3 col-sm-3';
} else {
$class = 'col-md-6 col-sm-6';
}?>

<div class="row">
<ul class="sort-destination" data-sort-id="gallery">

<?php while(have_posts()) : the_post();
$custom = get_post_custom(get_the_ID());
$image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
$thumb_id=get_post_thumbnail_id(get_the_ID());
$post_format_temp =get_post_format();
if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
$post_format =!empty($post_format_temp)?$post_format_temp:'image';
$term_slug = get_the_terms(get_the_ID(), 'gallery-category');
echo '<li class="grid-item col-md-'.$grid_column.' col-sm-6';
if (!empty($term_slug)) {
foreach ($term_slug as $term) {
  echo ' format-'.$term->slug.' ';
}
}
echo ' format-'.$post_format.'">';
echo'<div class="grid-item-inner">';
switch (get_post_format()) {
case 'image':
$large_src_i = wp_get_attachment_image_src($custom['_thumbnail_id'][0], $size_large);
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
}
echo $Lightbox_init;
the_post_thumbnail($size_thumb);
echo'</a>';
break;
case 'gallery':
echo '<div class="media-box">';
imic_gallery_flexslider(get_the_ID());  
if (count($image_data) > 0) {
echo'<ul class="slides">';
foreach ($image_data as $custom_gallery_images) {
$large_src = wp_get_attachment_image_src($custom_gallery_images, $size_large);
$gallery_thumbnail = wp_get_attachment_image_src($custom_gallery_images, $size_thumb);
$gallery_title = get_the_title($custom_gallery_images);
echo'<li class="item">';
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="' .esc_url($large_src[0]). '"data-rel="prettyPhoto[' . get_the_title() . ']">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="'.esc_url($large_src[0]) .'" title="'.esc_attr($gallery_title).'" class="magnific-gallery-image">';
}
echo $Lightbox_init;
if($i === 0){
	  echo '<img src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
} else {
	  echo '<img class="lazy" data-src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
}
echo'</a></li>';
$i++;
}
echo'</ul>';
 }
echo'</div></div>';
break;
case 'link':
if (!empty($custom['imic_gallery_link_url'][0])) {
echo '<a href="' . $custom['imic_gallery_link_url'][0] . '" target="_blank" class="media-box">';
the_post_thumbnail($size_thumb);
echo'</a>';
}
break;
case 'video':
if (!empty($custom['imic_gallery_video_url'][0])) {
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" data-rel="prettyPhoto" class="media-box">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" title="'.get_the_title().'" class="media-box magnific-video">';
}
echo $Lightbox_init;
the_post_thumbnail($size_thumb);
echo'</a>';
}
break;
default:
if(!empty($thumb_id)){
$large_src_i = wp_get_attachment_image_src($thumb_id, $size_large);
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
}
echo $Lightbox_init;
the_post_thumbnail($size_thumb);
echo'</a>';
}
break;
}
echo'</div>';
if($instance['show_post_meta']){
	echo '<h3 class="gallery_title_meta">'.get_the_title().'</h3>';
};
echo'</li>';
endif; endwhile; ?>
</ul>
</div>
<?php if(!empty($instance['gallery_page_pagination'])) : 
 echo '<div class="text-align-center">';
pagination();
echo '</div>';
 endif;
endif; wp_reset_query(); ?>