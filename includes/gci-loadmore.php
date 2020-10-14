<?php
/*
 * Ajax load more Custom Post Types
 */
function gci_loadmore_setup() {
	global $wp_query; 
	$jstime = filemtime(dirname(__FILE__).'/../js/gci-loadmore.js');
	wp_register_script( 'gci_loadmore_js', get_stylesheet_directory_uri() . '/js/gci-loadmore.js?v='.$jstime, array('jquery') );
 
	wp_localize_script( 'gci_loadmore_js', 'gci_loadmore', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
	));
 	wp_enqueue_script( 'gci_loadmore_js' );
}
add_action( 'wp_enqueue_scripts', 'gci_loadmore_setup' );

function gci_loadmore_ajax_handler() {
	$args = unserialize( stripslashes( $_POST['query'] ));
	$args['paged'] = $_POST['page'] + 1;
	$cptType = $_POST['cptType'];
 
	query_posts( $args );
 
	if( have_posts() ) :
		while( have_posts() ): the_post();
			if ($cptType == 'sermons') { // formatting for sermons
				if( '' != get_the_post_thumbnail() ) {
											$class = "col-md-8";
										} else {
											$class = "col-md-12";
										}
                    $custom = get_post_custom(get_the_ID());
                   ?>
                    <article class="post sermon">
                        <header class="post-title">
                            <?php
                            echo'<div class="row">
      					<div class="col-md-9 col-sm-9">
            				<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                            echo'<span class="meta-data"><i class="fa fa-calendar"></i>' . __('Posted on ', 'framework') . get_the_time(get_option('date_format'));
                            echo get_the_term_list(get_the_ID(), 'sermons-speakers', ' | Pastor: ', ', ', '' );
                            echo'</span>
                                         </div>';
                            echo'<div class="col-md-3 col-sm-3 sermon-actions">';
                            if (!empty($custom['imic_sermons_url'][0])) {
                                echo '<a href="' . get_permalink() . '#playvideo" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Video', 'framework') .'" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
                            }
                           
                             $attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
                            if (!empty($attach_full_audio)) {
                                echo'<a href="' . get_permalink() . '#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Audio', 'framework') .'" rel="tooltip"><i class="fa fa-headphones"></i></a>';
                            }
                            echo '<a href="' . get_permalink() . '#read" data-placement="top" data-toggle="tooltip" data-original-title="'.__('Read online', 'framework') .'" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
                            $attach_pdf= imic_sermon_attach_full_pdf(get_the_ID());
                            if (!empty($attach_pdf)) {
                               
                               echo '<a href="' . IMIC_THEME_PATH . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
                            }
                            echo'</div>
                 	</div>';
                            ?>
                        </header>
                        <div class="post-content">
                            <div class="row">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="col-md-4">
                                    <a href="<?php the_permalink() ?>" class="media-box">
                                        <?php
                                            the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
                                        ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <div class="<?php echo $class; ?>">
                                	<div class="page-content">
                                    <?php echo imic_excerpt(100); ?>
                                    </div>
                                    <p><a href="<?php the_permalink() ?>" class="btn btn-primary"><?php _e('Continue reading ', 'framework'); ?> <i class="fa fa-long-arrow-right"></i></a></p>
                                </div>
                            </div>
                        </div>
                    </article>
			<?php
			}
		endwhile;
	endif;
	die;
}
add_action('wp_ajax_loadmore', 'gci_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'gci_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}
?>