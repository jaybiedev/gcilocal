<?php get_header(); 
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
$gallery_size = imicGetThumbAndLargeSize();
$size_thumb =$gallery_size[0];
$size_large =$gallery_size[1]; ?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?>" id="content-col"> 
        	<?php while (have_posts()):the_post(); ?>
                <?php $custom = get_post_custom(get_the_ID());
$image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
$thumb_id=get_post_thumbnail_id(get_the_ID());
$post_format_temp =get_post_format();
if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
$post_format =!empty($post_format_temp)?$post_format_temp:'image';
$term_slug = wp_get_object_terms(get_the_ID(), 'gallery-category');
echo '<div class="format-'.$post_format.'">';
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
if (count($image_data) > 0) {
echo'<ul class="row">';
$i = 0;
foreach ($image_data as $custom_gallery_images) {
$large_src = wp_get_attachment_image_src($custom_gallery_images, 'full');
$gallery_thumbnail = wp_get_attachment_image_src($custom_gallery_images, 'full');
$gallery_title = get_the_title($custom_gallery_images);
echo'<li class="col-md-4 col-sm-4 format-image margin-30">';
if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
	$Lightbox_init = '<a href="' .esc_url($large_src[0]). '"data-rel="prettyPhoto[' . get_the_title() . ']" class="media-box">';
}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
	$Lightbox_init = '<a href="'.esc_url($large_src[0]) .'" title="'.esc_attr($gallery_title).'" class="magnific-gallery-image media-box">';
}
echo $Lightbox_init;
	  echo '<img src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
echo'</a></li>';
$i++;
}
echo'</ul>';
 }
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
$large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
if ($large_src_i) {
	if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
		$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
	}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
		$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
	}
	echo $Lightbox_init;
}
the_post_thumbnail($size_thumb);
echo'</a>';
}
break;
}
echo'</div>';
endif; ?>
				<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['6'] == '1') { ?>
                    <?php imic_share_buttons(); ?>
                <?php } ?>
		<?php endwhile; ?> 
        </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>