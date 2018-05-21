<?php

// add the itunes namespace to the RSS opening element
function imic_podcast_add_namespace() {
	echo 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"';
}

// Pre-hook for adding podcast data to the XML file.
add_action( 'pre_get_posts', 'imic_podcast_add_hooks', 9999 );

// Create custom RSS feed for sermon podcasting
add_action( 'do_feed_podcast', 'imic_sermon_podcast_feed', 10, 1 );

// Custom rewrite for podcast feed
function imic_sermon_podcast_feed_rewrite($wp_rewrite) {
	$feed_rules = array(
		'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
		'(.+).xml' => 'index.php?feed='. $wp_rewrite->preg_index(1)
	);
	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
//add_filter('generate_rewrite_rules', 'imic_sermon_podcast_feed_rewrite');

// Add podcast data to the WordPress default XML feed
function imic_podcast_add_hooks( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_feed() ) {
		if ($query->get('post_type')=='sermons' ) {
			add_filter( 'get_post_time', 'imic_podcast_item_date', 10, 3 );
			add_filter( 'bloginfo_rss', 'imic_bloginfo_rss_filter', 10, 2 );
			add_filter( 'wp_title_rss', 'imic_modify_podcast_title', 99, 3 );
			add_action( 'rss_ns', 'imic_podcast_add_namespace' );
			add_action( 'rss2_ns', 'imic_podcast_add_namespace' );
			add_action( 'rss_head', 'imic_podcast_add_head' );
			add_action( 'rss2_head', 'imic_podcast_add_head' );
			add_action( 'rss_item', 'imic_podcast_add_item' );
			add_action( 'rss2_item', 'imic_podcast_add_item' );
			add_filter( 'the_content_feed', 'imic_podcast_summary', 10, 3 );
			add_filter( 'the_excerpt_rss', 'imic_podcast_summary' );
			add_filter( 'rss_enclosure', '__return_empty_string' );
		}
	}
}

// Create custom RSS feed for sermon podcasting
function imic_sermon_podcast_feed() {
	load_template( IMIC_SERMONS . 'podcast-feed.php');
}

// add podcast head
function imic_podcast_add_head() {
	$options = get_option('imic_options'); ?>
    <copyright><?php echo html_entity_decode( esc_html( $options['podcast_copyright'] ), ENT_COMPAT, 'UTF-8' ) ?></copyright>
	<itunes:subtitle><?php echo esc_html( $options['podcast_itunes_subtitle'] ) ?></itunes:subtitle>
	<itunes:author><?php echo esc_html( $options['podcast_itunes_author'] ) ?></itunes:author>
	<?php if ( trim( category_description() ) !== '' ) : ?>
        <itunes:summary><?php echo stripslashes( wp_filter_nohtml_kses( category_description() ) ); ?></itunes:summary>
	<?php else: ?>
        <itunes:summary><?php echo wp_filter_nohtml_kses( $options['podcast_itunes_summary'] ); ?></itunes:summary>
	<?php endif; ?>
	<itunes:owner>
		<itunes:name><?php echo esc_html( $options['podcast_itunes_owner_name'] ) ?></itunes:name>
		<itunes:email><?php echo esc_html( $options['podcast_itunes_owner_email'] ) ?></itunes:email>
	</itunes:owner>
    <itunes:explicit>no</itunes:explicit>
		<?php
		$cover_image = $options['podcast_itunes_cover_image']['url'];
		if($cover_image != ''){
		 ?>
			<itunes:image href="<?php echo esc_url($cover_image) ?>" />
        <?php } else { ?>
			<itunes:image href="<?php echo esc_url(get_template_directory_uri()) ?>/images/cover.png" />
        <?php } ?>
    <itunes:category text="<?php echo esc_attr( $options['podcast_itunes_top_category'] ); ?>">
        <itunes:category
                text="<?php echo esc_attr( $options['podcast_itunes_sub_category'] ) ?>"/>
    </itunes:category>
	<?php
}

// add itunes specific info to each item
function imic_podcast_add_item(){
	$options = get_option('imic_options');
	global $post;
	
	$topics_all= wp_get_post_terms( get_the_ID() , 'sermons-tag' );
	$topics_all = false;
	if( $topics_all && count( $topics_all ) > 0 ) {
		$c = 0;
		foreach( $topics_all as $t ) {
			if( $c == 0 ) {
				$topics_list = esc_html( $t->name );
				++$c;
			} else {
				$topics_list .= ', ' . esc_html( $t->name );
			}
		}
	}
	$attached_audio  = imic_sermon_attach_full_audio(get_the_ID());
	$audio_raw       = str_ireplace( 'https://', 'http://', $attached_audio );
	$audio_p         = strrpos( $audio_raw, '/' ) + 1;
	$audio_raw       = urldecode( $audio_raw );
	$audio           = substr( $audio_raw, 0, $audio_p ) . rawurlencode( substr( $audio_raw, $audio_p ) );
	$speaker         = strip_tags( get_the_term_list( $post->ID, 'sermons-speakers', '', ' &amp; ', '' ) );
	$series          = strip_tags( get_the_term_list( $post->ID, 'sermons-category', '', ', ', '' ) );
	$topics          = $topics_list;
	$post_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
	$post_image      = str_ireplace( 'https://', 'http://', ! empty( $post_image['0'] ) ? $post_image['0'] : '' );
	$audio_duration  = get_post_meta( $post->ID, 'imic_sermon_duration', true ) ?: '0:00';
	$audio_file_size = get_post_meta( $post->ID, 'imic_sermon_size', 'true' ) ?: 0;

	// Fix for relative audio file URLs
	if ( substr( $audio, 0, 1 ) === '/' ) {
		$audio = home_url( $audio );
	}
	?>
    <itunes:author><?php echo esc_html( $speaker ); ?></itunes:author>
    <itunes:subtitle><?php echo esc_html( $series ); ?></itunes:subtitle>
	<?php if ( $post_image ) : ?>
        <itunes:image href="<?php echo esc_url( $post_image ); ?>"/>
	<?php endif; ?>
    <enclosure url="<?php echo esc_url( $audio ); ?>" length="<?php echo esc_attr( $audio_file_size ); ?>" type="audio/mpeg"/>
    <itunes:duration><?php echo esc_html( $audio_duration ); ?></itunes:duration>
	<?php if ( $topics ): ?>
        <itunes:keywords><?php echo esc_html( $topics ); ?></itunes:keywords>
	<?php endif; ?>

	<?php
}
	
//Display the sermon description as the podcast summary
function imic_podcast_summary ($content) {
	global $post;
	$podcast_desc = get_post_meta($post->ID, 'imic_sermons_podcast_description', 'true');
	//$content = '';
	$content = $podcast_desc;
}

//Filter published date for podcast: use sermon date instead of post date
function imic_podcast_item_date ($time, $d = 'U', $gmt = false) {
	$time = get_the_date('D, d M Y H:i:s O');
	return $time;
}

// Replace feed title with the one defined in Sermon Manager settings
function imic_modify_podcast_title( $title ) {
	$options = get_option('imic_options');
	$podcast_title = esc_attr( $options['podcast_title'] );

	if ( $podcast_title !== '' ) {
		return $podcast_title;
	}

	return $title;
}

// Modifies get_bloginfo output and injects Sermon Manager data
function imic_bloginfo_rss_filter( $info, $show ) {
	$options = get_option('imic_options');
	$new_info = '';

	switch ( $show ) {
		case 'name':
			$new_info = esc_attr( $options['podcast_title'] );
			break;
		case 'description':
			$new_info = stripslashes( wp_filter_nohtml_kses( $options['podcast_itunes_summary'] ) );
			break;
	}

	if ( $new_info !== '' ) {
		return $new_info;
	}

	return $info;
}
?>