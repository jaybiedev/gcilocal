<?php
get_header();
$pages_e = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'template-staff.php'
        ));
$variable_post_id = $pages_e[0]->ID;
$order= get_post_meta($variable_post_id,'imic_staff_select_orderby',true);
if($order=="ID") {$sort_order = "DESC"; } else { $sort_order = "ASC"; }
$wp_query->set('orderby', $order);
$wp_query->set('order', $sort_order);
$wp_query->get_posts();
$pageOptions = imic_page_design($variable_post_id); //page design options 
imic_sidebar_position_module();
 ?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?>" id="content-col">  
            <?php
            if (have_posts()):
                while (have_posts()):the_post();
                    $custom = get_post_custom(get_the_ID());
                    echo '<div class="col-md-4 col-sm-4">
                    <div class="grid-item staff-item"> 
                        <div class="grid-item-inner">';
                    if (has_post_thumbnail()):
                        echo '<div class="media-box"><a href="' . get_permalink(get_the_ID()) . '">';
                        echo get_the_post_thumbnail(get_the_ID(), '600x400');
                        echo '</a></div>';
                    endif;
                    $job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
                    $job = '';
                    if (!empty($job_title)) {
                        $job = '<div class="meta-data">' . $job_title . '</div>';
                    }
                    echo '<div class="grid-content">
                                <h3> <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h3>';
                    echo $job;
                   echo imic_social_staff_icon();
                    $description = imic_excerpt();
                    if (!empty($description)) {
						echo '<div class="page-content">';
                        echo $description;
						echo '</div>';
                    }
                    echo'</div></div>
                    </div>
                </div>';
                endwhile;
echo '<div class="clear"></div>';
                if (function_exists("pagination")) {
                    pagination();
                }
            endif;
            ?>
        </div>
		<?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>