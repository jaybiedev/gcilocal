<?php
$src = wp_get_attachment_image_src($instance['featured_image'], $instance['featured_size']);
if( !empty($src) ) {
	$attr = array(
		'src' => $src[0],
		'width' => $src[1],
		'height' => $src[2],
	);
}


$styles = array();
$classes = array('featured-block-widget');

if(!empty($instance['featured_title'])) $attr['featured_title'] = $instance['featured_title'];
if(!empty($instance['featured_alt'])) $attr['featured_alt'] = $instance['featured_alt'];
if(!empty($instance['featured_bound'])) {
	$styles[] = 'max-width:100%';
	$styles[] = 'height:auto';
}
if(!empty($instance['featured_full_width'])) {
	$styles[] = 'width:100%';
}
$styles[] = 'display:block';
$post_title = wp_kses_post($instance['featured_title']);
$post_link = wp_kses_post($instance['featured_link_text']);
?>


<?php if(!empty($instance['featured_image'])) : ?><div class="featured-block"> <a href="<?php echo sow_esc_url($instance['featured_url']) ?>" class="img-thumbnail" <?php if($instance['featured_new_window']) echo 'target="_blank"' ?>><?php endif; ?>
	<img <?php foreach($attr as $n => $v) echo $n.'="' . esc_attr($v) . '" ' ?> class="<?php echo esc_attr( implode(' ', $classes) ) ?>" <?php if( !empty($styles) ) echo 'style="'.implode('; ', $styles).'"'; ?>> <strong><?php echo $post_title; ?></strong> <span class="more"><?php echo $post_link; ?></span>
<?php if(!empty($instance['featured_image'])) : ?></a></div><?php endif; ?>