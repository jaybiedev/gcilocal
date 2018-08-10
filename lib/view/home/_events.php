<?php
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
$events = PostHelper::getHomePageCards('events');
?>

<div class="listing events-listing">
    <!-- Latest Events -->
    <?php
    foreach ($events as $Featurebox) { ?>
        <div class="col-lg-4">
            <?php echo $Featurebox->render();?>
        </div>
    <?php } ?>
</div>
