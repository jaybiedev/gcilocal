<?php
/*
  Template Name: Home 2
 */
get_header();
$custom_home = get_post_custom(get_the_ID());
$home_id = get_the_ID();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
/* Start Hero Slider */
get_template_part('flex-slider');

/* End Hero Slider */
$imic_category_to_show_on_home = get_post_meta($home_id, 'imic_category_to_show_on_home',true);
$imic_number_of_post_cat = get_post_meta($home_id, 'imic_number_of_post_cat', true);
$temp_wp_query = clone $wp_query;
$combined_data =  array();
//if(!empty($imic_category_to_show_on_home[0])||!empty($imic_number_of_post_cat[0])){
//$combined_data = array_merge($imic_category_to_show_on_home, $imic_number_of_post_cat);
$combined_data = $imic_category_to_show_on_home;
//}

?>
<!-- Start Notice Bar -->
<div class="notice-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <ul class="nav nav-pills news-portal-cats">
                    <?php
                    foreach ($combined_data as $key => $value) {
                        echo ' <li><a href="' . get_category_link($key) . '">' . get_cat_name($key) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-4">
                <form method="get" id="searchform" action="<?php echo home_url(); ?>/">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="s" id="s" placeholder="<?php _e('Search Posts...','framework'); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type ="submit"><i class="fa fa-search fa-lg"></i></button>
                    </span>
                </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<!-- End Notice Bar -->
<!-- Start Content -->
<?php $imic_switch_categories_post=get_post_meta($home_id,'imic_switch_categories_post',true);
if($imic_switch_categories_post==1){
?>
<div class="main" role="main">
    <div id="content" class="content full">
        <div class="container posts-featuring">
            <?php while(have_posts()):the_post();
					echo '<div class="page-content">';
                    the_content();		
					echo '</div>';
                endwhile; ?>
            <div class="row">
                <div class="<?php echo $pageOptions['class']; ?> col-sm-8" id="content-col"> 
                    <!-- Latest News -->
                    <?php 
                    if(!empty($combined_data)){
                    foreach ($combined_data as $key) { ?>
                        <div class="listing post-listing">
                            <header class="listing-header">
                                <a href="<?php echo get_category_link($key); ?>" class="btn btn-default pull-right"><?php _e('More', 'framework') ?></a>
                                <h3><?php echo get_cat_name($key); ?></h3>
                            </header>
                            <?php
                            query_posts(array(
                                'post_type' => 'post',
                                'posts_per_page' => $imic_number_of_post_cat,
                                'cat' => $key
                            ));
                            if (have_posts()):
                                ?>
                                <section class="listing-cont">
                                    <ul>
                                        <?php
                                        $i = 1;
										$options = get_option('imic_options');
                                        $readmore_button = isset($options['readmore_button'])?$options['readmore_button']:0;
                                        while (have_posts()):the_post();
                                            echo'<li class="item post"><div class="row">';
                                            if ($i == 1) {
                                                if (has_post_thumbnail()):
                                                    echo'<div class="col-md-4">';
                                                    echo'<a href = "' . get_permalink() . '" class = "media-box">' . get_the_post_thumbnail() . '</a>';
                                                    echo'</div>';
                                                endif;
                                                echo'<div class="col-md-8">';
                                            } else {
                                                echo'<div class="col-md-12">';
                                            }
                                            echo'<div class="post-title"><h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                                            echo'<span class="meta-data"><i class="fa fa-calendar"></i>' . __('on ', 'framework') .get_the_time(get_option('date_format')). '</span></div>';
                                           // if ($i == 1) {
												if($readmore_button)
												{
														  echo '<div class="page-content">';
														   the_content();
														   echo '</div>';
														  echo '<a class="btn btn-primary" href="'.get_permalink().'">'
														.__('Read More','framework').
														'<i class="fa fa-long-arrow-right"></i></a>';
												}
												 else
												 {
													 echo imic_excerpt(25);
												 }
                                           // }
                                            echo '</div>
                                            </div>
                                            </li>';
                                            $i++;
                                        endwhile;
                                        ?>
                                    </ul>
                                </section>
                            <?php endif; ?>
                        </div>
                        <div class="spacer-30"></div>
                        <?php
                    }
                    }
                    $wp_query = clone $temp_wp_query;
                    ?>
                </div>
                <?php if(!empty($pageOptions['sidebar'])){ ?>
                <!-- Start Sidebar -->
                <div class="col-md-3 col-sm-4" id="sidebar-col">
                    <?php dynamic_sidebar($pageOptions['sidebar']); ?>
                </div>
                <!-- End Sidebar -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- Start Featured Gallery -->
<?php
}
$posts_per_page = get_post_meta($home_id,'imic_galleries_to_show_on',true);
$imic_imic_galleries = get_post_meta($home_id,'imic_imic_galleries',true);

/*$gallery_category = get_post_meta($home_id,'imic_home_gallery_taxonomy',true);
if(!empty($gallery_category)){
$gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
$gallery_category= $gallery_categories->slug; }*/

$gallery_category = imic_get_term_category($home_id,'imic_home_gallery_taxonomy','gallery-category');

$posts_per_page =!empty($posts_per_page)?$posts_per_page:3;
$temp_wp_query = clone $wp_query;
$gallery_bg_image_id = get_post_meta($home_id,'imic_galleries_background_image',true); 
$gallery_bg_image = wp_get_attachment_image_src($gallery_bg_image_id, 'full');
query_posts(array(
    'post_type' => 'gallery',
    'posts_per_page' => $posts_per_page,
    'gallery-category' => $gallery_category,
));
if (have_posts()&&$imic_imic_galleries==1):
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
                while (have_posts()):the_post();
                    $custom = get_post_custom(get_the_ID());
                     $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
                    $thumb_id=get_post_thumbnail_id(get_the_ID());
                   $post_format_temp =get_post_format();
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
endif; 
$wp_query = clone $temp_wp_query;
//-- End Featured Gallery --
get_footer();
?>