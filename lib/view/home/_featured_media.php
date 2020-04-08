<?php
$front_page_id = get_option( 'page_on_front' );
$featured_media_id = get_post_meta($front_page_id,'imic_home_featured_media', true);

if (empty($featured_media_id)) {
    require_once(__DIR__ . '/_latest_sermon.php');
}
else {
    // $post =  get_post($featured_media_id);
    // $Featurebox = new Featurebox($post->ID);
    $Featurebox = new Featurebox($featured_media_id);
    echo $Featurebox->render();
}
