<?php
$number = wp_kses_post($instance['number']);
$text = wp_kses_post($instance['text']);
$ncolor = esc_attr($instance['number_color']);
$tcolor = esc_attr($instance['text_color']) ?>

<div class="countdown">
<div class="fact-ico">
<?php if( !empty($instance['icon_image']) ) {
	$attachment = wp_get_attachment_image_src($instance['icon_image']);
	if(!empty($attachment)) {
		$icon_styles[] = 'background-image: url(' . sow_esc_url($attachment[0]) . ')';
		if(!empty($instance['icon_size'])) $icon_styles[] = 'font-size: '.intval($instance['icon_size']).'px';

		?><div class="sow-icon-image" style="<?php echo implode('; ', $icon_styles) ?>"></div><?php
	}
}
else {
	$icon_styles = array();
	if(!empty($instance['icon_size'])) $icon_styles[] = 'font-size: '.intval($instance['icon_size']).'px';
	if(!empty($instance['icon_color'])) $icon_styles[] = 'color: '.$instance['icon_color'];

	echo siteorigin_widget_get_icon($instance['icon'], $icon_styles);
} ?> </div>
<div class="clearfix"></div>
<div class="timer" data-perc="<?php echo $number; ?>"> <span class="count"><?php echo $number; ?></span> </div>
<div class="clearfix"></div>
<span class="fact"><?php echo $text; ?></span>
</div>