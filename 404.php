<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
get_header();
$class=is_active_sidebar('sermons-sidebar')?8:12;
imic_sidebar_position_module();
?>
<div class="container">
    <div class="row">
     <div class="col-md-<?php echo $class; ?> sermon-archive" id="content-col"> 
            <!-- Sermons Listing -->
            <article class="post sermon">
                <header class="post-title">
                    <h2><?php _e('Error 404 - Not Found', 'framework') ?></h2>
                    <div class="breaker"><span class="left"></span><div class="icon"></div><span class="right"></span></div>
                </header>
                <section class="entry">
                    <p><?php _e("Sorry, but you are looking for something that isn't here.", "framework") ?></p>
                </section>
            </article>
        </div>
        <!-- Start Sidebar -->
        	 <?php
                if(is_active_sidebar('sermons-sidebar')):
                    echo '<div class="col-md-4 sidebar" id="sidebar-col">';
                dynamic_sidebar('sermons-sidebar'); 
                   echo '</div>';
                endif;
                 ?>
        <!-- End Sidebar -->
    </div>
</div>
<?php get_footer(); ?>