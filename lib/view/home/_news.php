<?php
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
$news = PostHelper::getHomePageCards('news');
?>
<div class="listing news-listing">
    <!-- Latest News -->
    <?php
    foreach ($news as $Featurebox) {
        ?>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <?php echo $Featurebox->render();?>
        </div>
    <?php }?>
</div>
