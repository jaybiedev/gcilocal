<?php
/*
  Template Name: Gallery Masonry
 */
get_header();
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$column_class = 9;  
}else{
$column_class = 12;  
}
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
$gallery_size = imicGetThumbAndLargeSize();
$size_thumb =$gallery_size[0];
$size_large =$gallery_size[1];
$custom_gallery = get_post_custom(get_the_ID());
$posts_per_page = get_post_meta(get_the_ID(),'imic_gallery_masonry_to_show_on',true);
$show_gallery_title_masonry = get_post_meta(get_the_ID(),'imic_show_gallery_title_masonry',true);

/*$gallery_category = get_post_meta(get_the_ID(),'imic_advanced_gallery_taxonomy',true);
if(!empty($gallery_category)){
$gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
if(!empty($gallery_categories)){
$gallery_category= $gallery_categories->slug;}
else{
$gallery_category='';    
}}*/

$gallery_category = imic_get_term_category(get_the_ID(),'imic_advanced_gallery_taxonomy','gallery-category');

echo '<div class="container">';
echo '<div class="row">'; ?>
<div class="col-md-<?php echo $column_class ?>" id="content-col">
<?php
while(have_posts()):the_post();
echo '<div class="page-content">';
the_content();		
echo '</div>';
endwhile;
$temp_wp_query = clone $wp_query;
query_posts(array(
'post_type' => 'gallery',
'posts_per_page' => $posts_per_page,
'gallery-category' => $gallery_category,
'paged' => get_query_var('paged')
));
if (have_posts()):
    ?>
<ul class="grid-holder col-3 events-grid">
<?php
while (have_posts()):the_post();
$custom = get_post_custom(get_the_ID());
$image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
$thumb_id=get_post_thumbnail_id(get_the_ID());
$post_format_temp =get_post_format();
if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
$post_format =!empty($post_format_temp)?$post_format_temp:'image';
echo '<li class="grid-item post format-' . $post_format . '">';
echo'<div class="grid-item-inner">';
switch (get_post_format()) {
case 'image':
$large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
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
$i = 0;
foreach ($image_data as $custom_gallery_images) {
$large_src = wp_get_attachment_image_src($custom_gallery_images, 'full');
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
echo'</div>
</div>';
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
$large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
}
echo $Lightbox_init;
the_post_thumbnail($size_thumb);
echo'</a>';
break;
}
echo'</div>';
if($show_gallery_title_masonry==1) {
	echo '<h3 class="gallery_title_meta">'.get_the_title().'</h3>';
};
echo'</li>';
endif;
endwhile;
?>
</ul>
<?php if ($wp_query->max_num_pages > 1) : ?>
<ul class="pager pull-right">
<li><?php next_posts_link(__('&larr;Older', 'framework')); ?></li>
<li><?php previous_posts_link(__(' Newer &rarr;', 'framework')); ?></li>
</ul>
<?php endif; ?>
<?php
endif;
$wp_query = clone $temp_wp_query;
echo'</div>';
if(is_active_sidebar($pageSidebar)) { ?>
    <!-- Start Sidebar -->
   	<div class="col-md-3 sidebar" id="sidebar-col">
    	<?php dynamic_sidebar($pageOptions['sidebar']); ?>
   	</div>
	<!-- End Sidebar -->
 <?php }
 echo '</div></div>';
get_footer(); ?>