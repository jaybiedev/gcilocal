<?php 
get_header(); 
$variable_post_id= get_option('woocommerce_shop_page_id');
$pageOptions = imic_page_design($variable_post_id); //page design options
imic_sidebar_position_module(); ?>
<div class="container">
    <div class="row">
        <?php if (have_posts()):?>
             <div class="<?php echo $pageOptions['class']; ?> product-archive" id="content-col">  
                <!-- Products Listing -->
                            <?php
                            /**
                             * woocommerce_before_main_content hook
                             *
                             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                             * @hooked woocommerce_breadcrumb - 20
                             */
                            do_action('woocommerce_before_main_content');?>
                            <?php do_action('woocommerce_archive_description'); ?>
                            <?php if (have_posts()) : ?>
                                <?php
                                /**
                                 * woocommerce_before_shop_loop hook
                                 *
                                 * @hooked woocommerce_result_count - 20
                                 * @hooked woocommerce_catalog_ordering - 30
                                 */
                                do_action('woocommerce_before_shop_loop');
                                ?>
                                <?php woocommerce_product_loop_start(); ?>
                                <?php woocommerce_product_subcategories(); ?>
                                <?php while (have_posts()) : the_post(); ?>
                                    <?php wc_get_template_part('content', 'product'); ?>
                                <?php endwhile; // end of the loop. ?>
                                <?php woocommerce_product_loop_end(); ?>
                                <?php
                                /**
                                 * woocommerce_after_shop_loop hook
                                 *
                                 * @hooked woocommerce_pagination - 10
                                 */
//				do_action( 'woocommerce_after_shop_loop' );
                                ?>
                            <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>
                                <?php wc_get_template('loop/no-products-found.php'); ?>
                            <?php endif; ?>
                            <?php
                            /**
                             * woocommerce_after_main_content hook
                             *
                             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                             */
                            do_action('woocommerce_after_main_content');
                            ?>
                <?php
                pagination();
                ?>
            </div>
        <?php
       else:
           wc_get_template('loop/no-products-found.php'); 
        endif;
        if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>