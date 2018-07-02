<?php
include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/Spol.php");
include_once(get_template_directory() . "/lib/classes/Sermon.php");

$Spol = new Spol();
$media = array();

$spols = $Spol->getAll();

$Sermon = new Sermon();
$sermons = $Sermon->getAll();

$front_page_id = get_option( 'page_on_front' );
$post_per_page = get_post_meta($front_page_id, 'imic_media_to_show_on', true);
if (empty($post_per_page))
    $post_per_page = 9;

if (!empty($sermons)) {
    $start_sermon = 1;  // skip the first
    array_walk($sermons, function($item, $key) {
        $item->post_type = 'media-sermon'; 
        $item->permalink = get_permalink($item);
        $item->author = get_the_term_list($item->ID, 'sermons-speakers', '', ', ', '' );
    });

    $media = array_slice($sermons, $start_sermon, abs($post_per_page/2));
}

// merge sermons and spols
$media = array_merge($media, array_slice($spols, 0, $post_per_page - count($media)));

usort($media, function($previous, $next) {
    return $next->post_date - $previous->post_date;
});

?>	
	
<div class="listing media-listing">
	<!-- Media Listing -->
	<?php
    $list_counter = 0;
    foreach ($media as $item) {
        $list_counter++;
        if ($list_counter > $post_per_page)
            break;
        ?>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <?php
            $Featurebox = new Featurebox();
            // inject Post
            $Featurebox->post_id = $item->ID;
            $Featurebox->Post = $item;

            echo $Featurebox->render();?>
        </div>
        <?php
    }
    ?>
</div>
	