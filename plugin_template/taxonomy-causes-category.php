<?php
/*
Template Name: Causes List
*/
get_header(); 
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('Payment-Imithemes/causes.php')) {
	$pageOptions = imic_page_design(); //page design options
	imic_sidebar_position_module();
?> 
<!-- Start Content -->
  		<div class="container">
    		<div class="row">
                <div class="<?php echo $pageOptions['class']; ?> posts-archive causes-archive" id="content-col">
					<?php //Display all causes in list view
                    if(have_posts()):while(have_posts()):the_post();
					$cause_start_date = get_post_meta(get_the_ID(),'imic_cause_end_dt',true);
						$cause_status = get_post_meta(get_the_ID(),'imic_cause_status',true);
						$cause_date = strtotime($cause_start_date);
						$now = date('Y-m-d');
						$now = strtotime($now);
						if($cause_date<=$now) { update_post_meta(get_the_ID(),'imic_cause_status','inactive'); } ?>
                    <article class="post cause-item">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full',array('class'=>'img-thumbnail')); ?></a>
                                <a href="#" id="donate-popup" class="btn btn-primary btn-block donate-paypal" data-toggle="modal" data-target="#PaymentModal-<?php echo get_the_ID(); ?>"><?php _e('Donate Now','framework'); ?></a>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <span class="post-meta meta-data">
                                    <span><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></span>
                                    <?php 
                                    //Display all cause categories 			
                                    echo get_the_term_list(get_the_ID(), 'causes-category', '<span><i class="fa fa-archive"></i>', ', ', '</span>' ); 
                                    //Display cause's total Comments
                                    comments_popup_link('<span><i class="fa fa-comment"></i>'.__('No comments yet','framework').'</span>', '<span><i class="fa fa-comment"></i>1</span>', '<span><i class="fa fa-comment"></i>%</span>', 'comments-link',__('Comments are off for this post','framework')); 
                                    ?>
                                </span>  
                                <?php
                                //Cause Donation Progress Bar
                                $cause_amount = get_post_meta(get_the_ID(),'imic_cause_amount',true); 
                                if(!empty($cause_amount)) {
                                ?>
                                <div class="progress-label">
                                <?php 
                                $cause_received_amount = get_post_meta(get_the_ID(),'imic_cause_amount_received',true);
                                $cause_received_amount = (empty($cause_received_amount)) ? 0 : $cause_received_amount;
                                $cause_percentage = ($cause_received_amount/$cause_amount)*100;
                                $cause_percentage = round($cause_percentage); 
                                if($cause_percentage<=30) { 
                                    $class = 'progress-bar-danger'; 
                                } elseif(($cause_percentage<=70)&&($cause_percentage>30)) { 
                                    $class = 'progress-bar-warning'; 
                                } else { 
                                    $class = 'progress-bar-success'; 
                                } 
                                echo $cause_percentage; _e('% Donated of ','framework');
                                echo '<span>'.imic_get_currency_symbol(get_option('paypal_currency_options')). $cause_amount .'</span>';
                                $now = date('Y-m-d 23:59:59'); // or your date as well
                                $now = strtotime($now);
                                $cause_end_date = get_post_meta(get_the_ID(),'imic_cause_end_dt',true);
                                $cause_end_date = $cause_end_date.' 23:59:59';
                                $your_date = strtotime($cause_end_date);
                                $datediff = $your_date - $now;
                                $days_left = floor($datediff/(60*60*24)); 
                                $cause_date_msg = '';
                                if($days_left==0) { $cause_date_msg = '1 day to go'; } elseif($days_left<0) { $cause_date_msg = 'Cause Closed'; } else { $cause_date_msg = $days_left+'1'.' days to go'; } ?>
                                    <label class="cause-days-togo label label-default pull-right"><?php echo $cause_date_msg; ?></label>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $class; ?>" data-appear-progress-animation="<?php echo $cause_percentage; ?>%" data-appear-animation-delay="200"></div><!-- Upto 30% use class progress-bar-danger , upto 70% use class progress-bar-warning , afterwards use class progress-bar-success -->
                                </div>
                             <?php }
                             echo imic_excerpt(); ?>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; 
                    else: ?>
                    <article class="post cause-item">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <?php _e('There is no any causes yet. ','framework'); ?>
                            </div>
                        </div>
                    </article>    
                    <?php 
                    endif;
                    query_posts(array('post_type'=>'causes'));
                    if(have_posts()):while(have_posts()):the_post(); ?>
                    <!-- Payment Modal Window -->
                    <div class="modal fade" id="PaymentModal-<?php echo get_the_ID(); ?>" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="PaymentModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel"><?php _e('Donate to: ','framework'); ?><span class="accent-color payment-to-cause"><?php the_title(); ?></span></h4>
                          </div>
                          <div class="modal-body">
                            <?php echo do_shortcode('[imic_causes cause_id="'.get_the_ID().'" description="'.get_the_title().'"]'); ?>
                          </div>
                          <div class="modal-footer">
                            <p class="small short"><?php echo (get_option('donation_form_info')!='')?get_option('donation_form_info'):'If you would prefer to call in your donation, please call 1800.785.876'; ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endwhile; endif; wp_reset_query(); ?>
				</div>
				<?php
                pagination();  //Causes Pagination
                if(!empty($pageOptions['sidebar'])){ ?>
                <!-- Start Sidebar -->
                <div class="col-md-3 sidebar" id="sidebar-col">
                    <?php dynamic_sidebar($pageOptions['sidebar']); ?>
                </div>
                <!-- End Sidebar -->
                <?php } ?>
	</div>
</div>
<?php } else { ?>
<!-- Start Content -->
<div class="main" role="main">
	<div id="content" class="content full">
  		<div class="container">
          	<?php _e('Please activate "Payment Imithemes" plugin first. ','framework'); ?>
        </div>
    </div>
</div>
<?php }
get_footer(); ?>