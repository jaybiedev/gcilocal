
<!-- Start Featured Gallery -->
<?php
$front_page_id = get_option( 'page_on_front' );
$gallery_category = get_post_meta($front_page_id,'imic_home_gallery_taxonomy', true);

    if(!empty($gallery_category)){
        $gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
        $gallery_category= $gallery_categories->slug;
    }

    $posts_per_page = get_post_meta($front_page_id,'imic_galleries_to_show_on',true);
    $posts_per_page=!empty($posts_per_page)?$posts_per_page:9;
    
    // $temp_wp_query = clone $wp_query;
    // $gallery_bg_image_id = get_post_meta($front_page_id,'imic_galleries_background_image',true);
    // $gallery_bg_image = wp_get_attachment_image_src($gallery_bg_image_id, 'full');
    $posts = get_posts(array(
        'post_type' => 'gallery',
        'gallery-category' => $gallery_category,
        'posts_per_page' => $posts_per_page,
    ));

    ?>
    
    <div class="listing events-listing">
    <!-- Latest News -->
    <?php
    $list_counter = 0;
    foreach ($posts as $item) {
        if ($list_counter > $posts_per_page)
            break;
            ?>
        <div class="col-lg-4">
            <?php
            $Featurebox = new Featurebox();
            // inject Post
            $Featurebox->post_id = $item->ID;
            $Featurebox->Post = $item;
            echo $Featurebox->render();?>
        </div>
        <?php
        $list_counter++;
    }
    ?>
	</div>
    <?php 
    /*
    if (have_posts()) {
       $gallery_size = imicGetThumbAndLargeSize();
       $size_thumb =$gallery_size[0];
       $size_large =$gallery_size[1];
      ?>
        <div class="featured-gallery <?php if($gallery_bg_image != ''){echo 'parallax parallax8';} ?>" <?php if($gallery_bg_image != ''){echo 'style="background-image:url('.$gallery_bg_image[0].');"';} ?>>
            <div class="container">
                <div class="row">
                    <?php
                    echo '<div class="col-md-3 col-sm-3">';
                    $imic_custom_gallery_title = !empty($custom_home['imic_custom_gallery_title'][0]) ? $custom_home['imic_custom_gallery_title'][0] : __('Updates from our gallery', 'framework');
                    echo'<h4>' . $imic_custom_gallery_title . '</h4>';
                    $imic_custom_more_galleries_title = !empty($custom_home['imic_custom_more_galleries_title'][0]) ? $custom_home['imic_custom_more_galleries_title'][0] : __('More Galleries', 'framework');
                    $pages = get_pages(array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'template-gallery-pagination.php'
                    ));
                    $imic_custom_more_galleries_url = !empty($custom_home['imic_custom_more_galleries_url'][0]) ? $custom_home['imic_custom_more_galleries_url'][0] : get_permalink($pages[0]->ID);
                    echo'<a href="' . $imic_custom_more_galleries_url . '" class="btn btn-default btn-lg">' . $imic_custom_more_galleries_title . '</a>';
                    echo '</div>';

                    while (have_posts()):

                        the_post();
                        $custom = get_post_custom(get_the_ID());
                        $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
                        $thumb_id=get_post_thumbnail_id(get_the_ID());

                        if(!empty($imic_gallery_images)) {
                          $gallery_img = $imic_gallery_images;
                        } else {
                          $gallery_img = '';
                        }
                         $post_format_temp = get_post_format();

                        if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
                         $post_format =!empty($post_format_temp)?$post_format_temp:'image';
                         echo '<div class="col-md-3 col-sm-3 post format-' . $post_format . '">';
                            switch (get_post_format()) {
                                case 'image':
                                    $large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
                                    if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
                                        $Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
                                    }elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
                                        $Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
                                    }
                                    echo $Lightbox_init;
                                    the_post_thumbnail($size_thumb);
                                    echo'</a>';
                                    break;
                                case 'gallery':
                                    echo '<div class="media-box">';
                                    imic_gallery_flexslider(get_the_ID());
                                    if (count($image_data) > 0) {
                                        echo'<ul class="slides">';
                                        $i = 0;
                                        foreach ($image_data as $custom_gallery_images) {
                                        $large_src = wp_get_attachment_image_src($custom_gallery_images, 'full');
                                        $gallery_thumbnail = wp_get_attachment_image_src($custom_gallery_images, $size_thumb);
                                        $gallery_title = get_the_title($custom_gallery_images);
                                        echo'<li class="item">';
                                        if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
                                            $Lightbox_init = '<a href="' .esc_url($large_src[0]). '"data-rel="prettyPhoto[' . get_the_title() . ']">';
                                        }elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
                                            $Lightbox_init = '<a href="'.esc_url($large_src[0]) .'" title="'.esc_attr($gallery_title).'" class="magnific-gallery-image">';
                                        }
                                        echo $Lightbox_init;
                                        if($i === 0){
                                              echo '<img src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
                                        } else {
                                              echo '<img class="lazy" data-src="'.$gallery_thumbnail[0].'" alt="' .esc_attr($gallery_title). '" >';
                                        }
                                        echo'</a></li>';
                                        $i++;
                                        }
                                        echo'</ul>';
                                    }
                                    echo'</div>
                                    </div>';
                                    break;
                                case 'link':
                                    if (!empty($custom['imic_gallery_link_url'][0])) {
                                        echo '<a href="' . $custom['imic_gallery_link_url'][0] . '" target="_blank" class="media-box">';
                                        the_post_thumbnail($size_thumb);
                                        echo'</a>';
                                    }
                                    break;
                                case 'video':
                                    if (!empty($custom['imic_gallery_video_url'][0])) {
                                       if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
                                            $Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" data-rel="prettyPhoto" class="media-box">';
                                        }elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
                                            $Lightbox_init = '<a href="' . $custom['imic_gallery_video_url'][0] . '" title="'.get_the_title().'" class="media-box magnific-video">';
                                        }
                                        echo $Lightbox_init;
                                        the_post_thumbnail($size_thumb);
                                        echo'</a>';
                                    }
                                    break;
                                default:
                                    $large_src_i = wp_get_attachment_image_src($thumb_id, 'full');
                                    if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
                                        $Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
                                    }elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
                                        $Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" title="'.get_the_title().'" class="media-box magnific-image">';
                                    }
                                    echo $Lightbox_init;
                                    the_post_thumbnail($size_thumb);
                                    echo'</a>';
                                    break;
                            }
                            echo'</div>';
                        endif;
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    */
    wp_reset_query();
    //-- End Featured Gallery --