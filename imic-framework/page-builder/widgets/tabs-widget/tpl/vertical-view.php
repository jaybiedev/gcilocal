<?php $tabid = wp_kses_post($instance['tab_id']); ?>
<div class="tabs vertical-tabs">
    <ul class="nav nav-tabs">
    <?php $i =0; $class='active';$output_li= $output_div=''; ?>
	<?php foreach( $instance['tabs'] as $i => $tab ) : ?>
    <?php if($i!=0) $class=''; 
      	$output_li.='<li class="'.$class.'"> <a data-toggle="tab" href="#sampletab_'.$tabid.$i.'">'.wp_kses_post( $tab['tab_nav_title'] ).'</a> </li>';
      	$output_div.='<div id="sampletab_'.$tabid.$i.'" class="tab-pane '.$class.'">'.wp_kses_post( $tab['tab_nav_content'] ).'</div>';
    	 $i++; 
		 endforeach; 
         echo $output_li; ?>
        </ul>
    <div class="tab-content">
    <?php echo $output_div; ?>
    </div>
</div>