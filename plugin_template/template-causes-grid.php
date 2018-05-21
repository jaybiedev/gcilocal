<?php
/*
Template Name: Causes Grid
*/
get_header(); 
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('Payment-Imithemes/causes.php')) { ?> 
<!-- Start Content -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<?php 
                        while(have_posts()):the_post();
                        if($post->post_content!="") :
                            the_content();        
                            echo '<div class="spacer-20"></div>';
                        endif;	
                        endwhile;
                    ?>
                	<?php
					$paged = (get_query_var('paged'))?get_query_var('paged'):1;
                    query_posts(array('post_type'=>'causes',
					'paged'=>$paged,
					'meta_key'=>'imic_cause_status',
					'meta_value'=>'active'
					));
                    if(have_posts()): ?>
                    <ul class="grid-holder col-3 causes-grid">
                    	<?php while(have_posts()):the_post();
						$cause_start_date = get_post_meta(get_the_ID(),'imic_cause_end_dt',true);
						$cause_status = get_post_meta(get_the_ID(),'imic_cause_status',true);
						$cause_date = strtotime($cause_start_date);
						$now = date('Y-m-d');
						$now = strtotime($now);
						if($cause_date<=$now) { update_post_meta(get_the_ID(),'imic_cause_status','inactive'); }
						if($cause_status=='active') { ?>
                      	<li class="grid-item cause-item format-standard">
                            <div class="grid-item-inner">
                              <a href="<?php the_permalink(); ?>" class="media-box"> <?php the_post_thumbnail('full'); ?> </a>
                              <div class="grid-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php
                                //Cause Donation Progress Bar
                                $cause_amount = get_post_meta(get_the_ID(),'imic_cause_amount',true); 
                                if(!empty($cause_amount)) {
                                ?>
                                <div class="progress-label">
                               		<?php  
                                    $cause_received_amount = get_post_meta(get_the_ID(),'imic_cause_amount_received',true); 
                                    $cause_percentage = (intval($cause_received_amount)/intval($cause_amount))*100;
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
                                echo imic_excerpt(50); ?>
                                <a href="#" id="donate-popup" class="btn btn-default donate-paypal" data-toggle="modal" data-target="#PaymentModal-<?php echo get_the_ID(); ?>"><?php _e('Donate Now','framework'); ?></a>
                              </div>
                            </div>
                      	</li>
                    <?php } endwhile; ?>
                    </ul>
                	<?php pagination();
                    else: 
						_e('There is no any causes yet. ','framework');
                    endif;
					wp_reset_query();
					
                    query_posts(array('post_type'=>'causes','meta_key'=>'imic_cause_status','meta_value'=>'active','paged' => $paged));
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
                    <?php endwhile;
					endif;
					wp_reset_query(); ?>
                </div>
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