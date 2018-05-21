<?php die();
header("Content-Type: application/rss+xml; charset=UTF-8");

$options = get_option('imic_options');
wp_redirect( home_url('/feed/?post_type=sermons'), 301 );
exit;

$args = array(
	'post_type' => 'sermons',
	'posts_per_page' => -1,
	'meta_key' => 'post_date',
	'meta_value' => date("m/d/Y"),
	'meta_compare' => '>=',
	'orderby' => 'meta_value',
	'order' => 'DESC'
);
$sermon_podcast_query = new WP_Query($args);

echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0" <?php imic_podcast_add_namespace(); ?>>
	<channel>
		<?php echo esc_attr( $options['podcast_title'] ) ?>
		<title><?php echo esc_attr( $options['podcast_title'] ) ?></title>
		<link><?php echo esc_url( $options['podcast_website_url'] ) ?></link>
        <atom:link href="<?php if ( ! empty( $_SERVER['HTTPS'] ) ) {
			echo 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		} else {
			echo 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		} ?>" rel="self" type="application/rss+xml"/>
		<language><?php echo esc_html( $options['podcast_language'] ) ?></language>
		<description><?php echo esc_html( $options['podcast_description'] ) ?></description>
	
		<?php imic_podcast_add_head(); ?>
	
		<?php if ( $sermon_podcast_query->have_posts() ) : while ( $sermon_podcast_query->have_posts() ) : $sermon_podcast_query->the_post();
			global $post;

			$audio_file= imic_sermon_attach_full_audio(get_the_ID());
			$podcast_desc = get_post_meta($post->ID, 'imic_sermons_podcast_description', 'true');

			if ( $audio_file != '' ) : ?>
			<item>
				<title><?php the_title_rss() ?></title>
				<link><?php the_permalink_rss() ?></link>
				<?php if ( get_comments_number() || comments_open() ) : ?>
					<comments><?php comments_link_feed(); ?></comments>
				<?php endif; ?>
				<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
				<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
				<?php the_category_rss( 'rss2' ) ?>
				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<description><?php echo esc_html( $podcast_desc); ?></description>
				<?php imic_podcast_add_item(); ?>
			</item>
		<?php endif; ?>
		<?php endwhile; endif; wp_reset_query(); ?>
	</channel>
</rss>