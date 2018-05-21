<?php
/*
  Template Name: Full Width
 */
get_header(); ?>
    <div class="container">
        <div class="row">
	<article class="col-md-12">
		<?php if(have_posts()):
                while(have_posts()):the_post();
				echo '<div class="page-content">';
                    the_content();		
				echo '</div>';
                endwhile;
            endif; ?>
			<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['2'] == '1') { ?>
                <?php imic_share_buttons(); ?>
            <?php } ?>
	</article>
    	</div>
     </div>
<?php get_footer(); ?>