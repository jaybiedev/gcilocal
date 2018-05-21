<?php get_header();
imic_sidebar_position_module(); ?>
<div class="container">
    <div class="row">
        <div class="col-md-9 posts-archive" id="content-col">
            <?php
            if (have_posts()) :
			$post_type = get_post_type(get_the_ID());
			if($post_type!='event') {
                while (have_posts()):the_post();
                    echo'<article class="post">
                            <div class="row">';
					$class_child = 12;
                    if (has_post_thumbnail()):
					$class_child = 8;
                        echo '<div class="col-md-4 col-sm-4">
                    	<a href="' . get_permalink() . '">';
                        the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
                        echo'</a></div>';
                    endif;
                    echo '<div class="col-md-'.$class_child.' col-sm-'.$class_child.'">';
                    echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                    echo '<span class="post-meta meta-data">
                    		<span><i class="fa fa-calendar"></i>' . get_the_time(get_option('date_format')) . '</span><span><i class="fa fa-archive"></i>'.imic_custom_taxonomies_terms_links().'</span> <span>';
                    comments_popup_link('<i class="fa fa-comment"></i>'.__('No comments yet','framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link',__('Comments are off for this post','framework'));
                    echo'</span></span>';
                    echo imic_excerpt(50);
                    echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . __('Continue reading', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>';
                    echo '</div></div>';
                    echo '</article>';
                endwhile; }
				else {
					echo '<div class="listing events-listing">';
					echo '<section class="listing-cont"><ul>';
					$event_search = imic_recur_events('future','nos','','');
					foreach( $posts as $post) {
					$arr[] = get_the_ID();
					}
					$result = array_intersect($event_search, $arr);
					ksort($result);
					foreach($result as $key =>$value) {
                                
				$satime = get_post_meta($value,'imic_event_start_tm',true);
				$satime = strtotime($satime);
			        $date_converted=date('Y-m-d',$key );
                                $custom_event_url =  imic_query_arg($date_converted,$value);
                                echo '<li class="item event-item">	
				  <div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">' .imic_global_month_name($key). '</span> </div>
				  <div class="event-detail">
                                      <h4><a href="'.$custom_event_url.'">' . get_the_title($value).'</a>'.imicRecurrenceIcon($value).'</h4>';
                $stime = '';
                if ($satime != '') {
                    $stime = ' | ' . date_i18n(get_option('time_format'), $satime);
                }
                echo '<span class="event-dayntime meta-data">' . __(date_i18n('l', $key),'framework') . $stime . '</span> </div>
				  <div class="to-event-url">
					<div><a href="' .$custom_event_url.'" class="btn btn-default btn-sm">' . __('Details', 'framework') . '</a></div>
				  </div>
				</li>';
	}
				echo '</ul></section></div>';
				}
            else:
                echo '<article class="post">';
                if (current_user_can('edit_posts')) :
                    ?>
                    <h3><?php _e('No posts to display', 'framework'); ?></h3>
                    <p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'framework'), admin_url('post-new.php')); ?></p>
                <?php else : ?>
                    <h3><?php _e('Nothing Found', 'framework'); ?></h3>
                    <p><?php printf(__('Apologies, but no results were found. Perhaps searching will help find a related post..', 'framework')); ?></p>
                    <?php
                    echo '</article>';
                endif;
                ?>
            <?php
            endif; // end have_posts() check 
            pagination();
            ?>
        </div>
        <!-- Start Sidebar -->
        <?php if(is_active_sidebar('post-sidebar')){
            echo '<div class="col-md-3 sidebar" id="sidebar-col">';
            dynamic_sidebar('post-sidebar');
            echo '</div>';
            } ?>
        <!-- End Sidebar -->
    </div>
</div>
<?php get_footer(); ?>