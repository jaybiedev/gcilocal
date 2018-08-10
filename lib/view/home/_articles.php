<?php
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
$articles = PostHelper::getHomePageCards('articles');
?>

<div class="listing articles-listing">
    <!-- Latest News -->
    <?php
    foreach ($articles as $Featurebox) { ?>
        <div class="col-lg-4">
            <?php echo $Featurebox->render();?>
        </div>
    <?php } ?>
</div>
