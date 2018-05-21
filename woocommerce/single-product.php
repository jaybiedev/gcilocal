<?php
get_header();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?>" id="content-col"> 
           <?php
                while ( have_posts() ) : the_post(); 
			 		wc_get_template_part( 'content', 'single-product' );
		
                    /** Sermon Tags * */
                    $tag= get_the_term_list(get_the_ID(), 'product_tag', '', ', ', '');
                    if(!empty($tag)){
                    echo '<div class="post-meta">';
                    echo '<i class="fa fa-tags"></i>';
                    echo $tag;
                    echo '</div>';
                    }
                endwhile;
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