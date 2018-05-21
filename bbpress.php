<?php
get_header(); 
imic_sidebar_position_module();
if ($imic_options['bbpress_sidebar']) {
	$content_col = 9;
} else {
	$content_col = 12;
}?>
<div class="container">
    <div class="row">
        <div class="col-md-<?php echo $content_col; ?>" id="content-col">
	  	<?php if(have_posts()):
                while(have_posts()):the_post();
             	if($post->post_content!="") :
                              the_content();
                      endif;
                endwhile;
            endif; ?>
        </div>
        <?php if ($imic_options['bbpress_sidebar']){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($imic_options['bbpress_sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>