<?php
$content = wp_kses_post($instance['content']);
$close = esc_attr($instance['close']);
$type = (!empty($instance['type']))? $instance['type'] : 'standard' ;
$animation = (!empty($instance['animation']))? $instance['animation'] : 'fadeIn' ;
$color = esc_attr($instance['custom_color']);
$bcolor = esc_attr($instance['custom_bcolor']);
$tcolor = esc_attr($instance['custom_tcolor']) ?>

<div data-appear-animation="<?php echo $animation; ?>"><div class="alert alert-<?php echo $type; ?> fade in" style="background-color:<?php echo $color; ?>; color:<?php echo $tcolor; ?>; border-color:<?php echo $bcolor; ?>"> <?php if($close!=""){ ?><a class="close" style="color:<?php echo $tcolor; ?>" data-dismiss="alert" href="#">&times;</a><?php } ?> <?php echo $content; ?> </div></div>