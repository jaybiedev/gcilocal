<section class="post-comments">
    <?php
// Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die(__('Please do not load this page directly. Thanks!','framework'));
    if (post_password_required()) {
        ?>
        <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'framework') ?></p>
        <?php
        return;
    }
    /* ----------------------------------------------------------------------------------- */
    /* 	Display the comments + Pings
      /*----------------------------------------------------------------------------------- */
    if (have_comments()) : // if there are comments 
        ?>
        <div id="comments" class="clearfix">
            <?php if (!empty($comments_by_type['comment'])) : // if there are normal comments  ?>
                <h3><i class="fa fa-comment"></i> <?php comments_number(__('No Comments', 'framework'), __('Comment(1)', 'framework'), __('Comments(%)', 'framework')); ?></h3>
                <ol class="comments">
                    <?php wp_list_comments('type=comment&avatar_size=51&callback=imic_comment'); ?>
                </ol>
            <?php endif; ?>
            <?php
            /* ----------------------------------------------------------------------------------- */
            /* 	Deal with no comments or closed comments
              /*----------------------------------------------------------------------------------- */
            if ('closed' == $post->comment_status) : // if the post has comments but comments are now closed 
                ?>
                <p class="nocomments"><?php _e('Comments are now closed for this article.', 'framework') ?></p>
            <?php endif; ?>
        <?php else : ?>
            <?php if ('open' == $post->comment_status) : // if comments are open but no comments so far  ?>
            <?php else : // if comments are closed ?>
                <?php if (is_single()) { ?><p class="nocomments"><?php _e('Comments are closed.', 'framework') ?></p><?php } ?>
            <?php endif; ?>
        <?php endif; ?>
</section>
<?php
/* ----------------------------------------------------------------------------------- */
/* 	Comment Form
  /*----------------------------------------------------------------------------------- */
if (comments_open()) :
    ?>
    <div id="respond-wrap" class="clearfix">
           <section class="post-comment-form">
           <div id="respond" class="clearfix">
                <h3><i class="fa fa-share"></i> <?php comment_form_title(__('Post a comment', 'framework'), __('Post a comment to %s', 'framework')); ?></h3>
                <div class="cancel-comment-reply">
			<?php cancel_comment_reply_link(__('Cancel Reply','framework')); ?>
		</div>
                <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
                    <p><?php printf(__('You must be %1$slogged in%2$s to post a comment.', 'framework'), '<a href="' . get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()) . '">', '</a>') ?></p>
                <?php else : ?>
                    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                        <?php if (is_user_logged_in()) :
                            echo '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','framework' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>'; else : ?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-4 col-sm-4">
                                        <input type="name" class="form-control input-lg" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" placeholder="<?php _e('Your name','framework'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <input type="email" name="email" class="form-control input-lg" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" placeholder="<?php _e('Your email','framework'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <input type="url" class="form-control input-lg" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" placeholder="<?php _e('Website (optional)','framework'); ?>" /></div>
                                </div>
                            </div>
                        <?php endif; ?>     
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea name="comment" id="comment-textarea" class="form-control input-lg" cols="8" rows="4"  tabindex="4" placeholder="<?php _e('Your comment','framework'); ?>" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input name="submit" type="submit" class="btn btn-primary btn-lg" id="submit" tabindex="5" value="<?php _e('Submit your comment', 'framework') ?>" />
                                    <?php comment_id_fields(); ?>
                                    <?php do_action('comment_form', $post->ID); ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; // If registration required and not logged in ?>
        </section>
    </div>
    <?php
endif; // if you delete this the sky will fall on your head ?>