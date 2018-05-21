<?php $tabid = wp_kses_post($instance['tab_id']); ?>
<div class="accordion" id="<?php echo $tabid; ?>">
    <?php $i =0; $class='active'; $classs='in'; $output=''; ?>
	<?php foreach( $instance['tabs'] as $i => $tab ) : ?>
    <?php if($i!=0) 
	{ $class=''; $classs=''; }
      	$output.='<div class="accordion-group panel accordion-panel">
                        <div class="accordion-heading accordionize"> <a class="accordion-toggle '.$class.'" data-toggle="collapse" data-parent="#'.$tabid.'" href="#Area_'.$tabid.$i.'">'.wp_kses_post( $tab['tab_nav_title'] ).'<i class="fa fa-angle-down"></i> </a> </div>';
      	$output.='<div id="Area_'.$tabid.$i.'" class="accordion-body collapse '.$classs.'"><div class="accordion-inner">'.wp_kses_post( $tab['tab_nav_content'] ).'</div></div></div>';
    	 $i++; 
		 endforeach; 
         echo $output; ?>
</div>