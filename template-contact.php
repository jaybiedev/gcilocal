<?php
/*
  Template Name: contact
 */
get_header();
$custom = get_post_custom(get_the_ID());
$admin_email = (!empty($custom['imic_contact_email'][0])) ? $custom['imic_contact_email'][0] : get_option('admin_email');
$subject_email = (!empty($custom['imic_contact_subject'][0])) ? $custom['imic_contact_subject'][0] :__('Contact Form','framework'); 
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module(); ?>
<div class="container">
    <div class="row">
        <div class="<?php echo $pageOptions['class']; ?>" id="content-col">
            <?php
             while(have_posts()):the_post();
             	if($post->post_content!="") :
						echo '<div class="page-content">';
                              the_content();        
						echo '</div>';
                              echo '<div class="spacer-20"></div>';
                      endif;			
             endwhile; 
            if (!empty($custom['imic_our_location_text'][0])) {
                echo '<header class="single-post-header clearfix">
                        <h2 class="post-title">' . $custom['imic_our_location_text'][0] . '</h2>
                      </header>';
            }
            ?>
            <div class="post-content">
                <?php
               if ($custom['imic_contact_map_display'][0] == 'yes' && !empty($custom['imic_contact_map_box_code'][0])) {
                    echo '<div id="gmap">';
                    echo $custom['imic_contact_map_box_code'][0];
                    echo '</div>';
                }
                ?>
                   <div class="row">
                    <form method="post" id="contactform" name="contactform" class="contact-form-native" action="<?php echo get_template_directory_uri() ?>/mail/contact.php">
                        <div class="col-md-6 margin-15">
                            <div class="form-group">
                                <input type="text" id="name" name="name"  class="form-control input-lg" placeholder="<?php _e('Name*','framework'); ?>">
                            </div>
                            <div class="form-group">
                                <input type="email" id="email" name="email"  class="form-control input-lg" placeholder="<?php _e('Email*','framework'); ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" id="phone" name="phone" class="form-control input-lg" placeholder="<?php _e('Phone','framework'); ?>">
                                <input type ="hidden" name ="image_path" id="image_path" value ="<?php echo get_template_directory_uri() ?>">
                            <input id="admin_email" name="admin_email" type="hidden" value ="<?php echo $admin_email; ?>">
                            <input id="subject" name="subject" type="hidden" value ="<?php echo $subject_email; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <textarea cols="6" rows="7" id="comments" name="comments" class="form-control input-lg" placeholder="<?php _e('Message','framework'); ?>"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input id="submit" name="submit" type="submit" class="btn btn-primary btn-lg pull-right" value="<?php _e('Submit now!','framework'); ?>">
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div id="message"></div>
                </div>
				<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['2'] == '1') { ?>
                    <?php imic_share_buttons(); ?>
                <?php } ?>
            </div>
        </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>