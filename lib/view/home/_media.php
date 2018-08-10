<?php
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
$media = PostHelper::getHomePageCards('media');
?>	
	
<div class="listing media-listing">
	<!-- Media Listing -->
	<?php
    foreach ($media as $Featurebox) {?>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <?php echo $Featurebox->render();?>
        </div>
    <?php }?>
</div>
	