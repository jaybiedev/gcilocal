<?php
    include_once(get_template_directory() . "/lib/classes/Featurebox.php");

    $sermons_cat='';
    $advanced_sermons_category= get_post_meta($home_id,'imic_advanced_sermons_category',true);


    $posts = get_posts(array('post_type' => 'sermons',
        'sermons-category'=>$sermons_cat,
        'post_status' => 'publish',
        'suppress_filters' => false,
        'posts_per_page' => 1
    ));


    if(!empty($advanced_sermons_category)) {
        $sermons_cat_data= get_term_by('id',$advanced_sermons_category,'sermons-category');

        if(!empty($sermons_cat_data)) {
            $sermons_cat= $sermons_cat_data->slug;
        }
    }

    // show only first/latest post
    if (count($posts) > 0) {
        $Featurebox = new Featurebox($posts[0]->ID);
        echo $Featurebox->render();
    }
