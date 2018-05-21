<?php
$title = wp_kses_post($instance['title']);
$percentile = esc_attr($instance['percentile']);
$stripped = esc_attr($instance['stripped']);
$type = (!empty($instance['type']))? $instance['type'] : 'primary' ;
$animation = esc_attr($instance['animation']);
$color = esc_attr($instance['custom_color']) ?>

<div class="progress-label"> <span><?php echo $title; ?></span> </div>
  <div class="progress <?php if($stripped!=""){ ?>progress-striped<?php } ?>">
    <div class="progress-bar progress-bar-<?php echo $type; ?>" style="background-color:<?php if($color!=""){ ?><?php echo $color; ?><?php } ?>;" data-appear-progress-animation="<?php echo $percentile; ?>%" data-appear-animation-delay="<?php echo $animation; ?>"> <span class="sr-only"><?php echo $percentile; ?>% Complete (<?php echo $type; ?>)</span><?php if($stripped!=""){ ?><span class="progress-bar-tooltip"><?php echo $percentile; ?>%</span><?php } ?> </div>
  </div>