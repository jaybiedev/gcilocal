<?php
/*
  Template Name: Ministry
 */
get_header();
$pageOptions = imic_page_design('',8); //page design options
$imic_post_custom_title = get_post_meta(get_the_ID(),'imic_post_page_custom_title',true);
$imic_post_custom_title = !empty($imic_post_custom_title) ? $imic_post_custom_title: get_the_title(get_the_ID());
imic_sidebar_position_module();
?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?>" id="content-col">
            <div class="post-content">
                <?php   if (has_post_thumbnail()) {
                  the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
                       } ?>
                <div class="spacer-30"></div>
                <?php  while(have_posts()):the_post();
				echo '<div class="page-content">';
                    the_content();		
				echo '</div>';
                endwhile; ?>
                
            </div>
        </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-4 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?> 
    </div>
</div>
<?php get_footer(); ?>