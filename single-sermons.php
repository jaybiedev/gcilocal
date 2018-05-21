<?php
get_header();
$pageOptions = imic_page_design(); //page design options 
imic_sidebar_position_module();
$custom = get_post_custom(get_the_ID());?>
<div class="container">
<div class="row">
<div class="<?php echo $pageOptions['class']; ?>" id="content-col"> 
<header class="single-post-header clearfix">
<div class="pull-right sermon-actions">
<?php
$add_custom_video_mp4 = get_post_meta(get_the_ID(),'imic_sermons_add_video_mp4',true);
$add_custom_video_webm = get_post_meta(get_the_ID(),'imic_sermons_add_video_webm',true);
$add_custom_video_ogv = get_post_meta(get_the_ID(),'imic_sermons_add_video_ogv',true);
$add_custom_video_poster = get_post_meta(get_the_ID(),'imic_sermons_add_video_poster',true);
if (!empty($custom['imic_sermons_add_vimeo_url'][0])){
$add_vimeo_video_url = $custom['imic_sermons_add_vimeo_url'][0];
}
if (!empty($custom['imic_sermons_add_youtube_url'][0])){
$add_youtube_video_url = $custom['imic_sermons_add_youtube_url'][0];
}
if (!empty($custom['imic_sermons_add_soundcloud_url'][0])){
$add_soundcloud_audio_url = $custom['imic_sermons_add_soundcloud_url'][0];
}

$sermon_video_option= get_post_meta(get_the_ID(),'imic_sermons_video_upload_option',true);
$custom_video_mp4= get_post_meta(get_the_ID(),'imic_sermons_video_mp4',true);
$custom_video_webm= get_post_meta(get_the_ID(),'imic_sermons_video_webm',true);
$custom_video_ogv= get_post_meta(get_the_ID(),'imic_sermons_video_ogv',true);
$custom_video_poster= get_post_meta(get_the_ID(),'imic_sermons_video_poster',true);
if (!empty($custom['imic_sermons_url'][0]) && $sermon_video_option == 1) {
    $Final_Video_URL = $custom['imic_sermons_url'][0];
} else if (!empty($custom_video_mp4)&& $sermon_video_option == 2) {
	$Final_Video_URL = $custom_video_mp4;
} else if (!empty($custom['imic_sermons_url'][0])) {
	$Final_Video_URL = $custom['imic_sermons_url'][0];
} else {
	$Final_Video_URL= '#';
}
if (!empty($custom_video_mp4) || !empty($custom['imic_sermons_url'][0])) {
echo '<a href="' . $Final_Video_URL . '" class="play-video-link" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Video', 'framework') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
}
$attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
if(!empty($attach_full_audio)) {
echo'<a href="' . $attach_full_audio . '" class="play-audio-link" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Audio', 'framework') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
}
if (!empty($attach_full_audio)) {
echo '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_full_audio . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download Audio', 'framework') . '" rel="tooltip"><i class="fa fa-download"></i></a>';
}
$attach_pdf= imic_sermon_attach_full_pdf(get_the_ID());
if (!empty($attach_pdf)){
echo '<a href="' . get_template_directory_uri() . '/download/download.php?file=' . $attach_pdf . '" data-placement="top" data-toggle="tooltip" data-original-title="' . __('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
}
?>
</div>
<h2 class="post-title"><?php the_title(); ?></h2>
</header>
<article class="post-content">
<?php
if (!empty($custom['imic_sermons_url'][0]) && $sermon_video_option == 1) {
    echo '<div class="video-container">' . imic_video_embed($custom['imic_sermons_url'][0], '200', '150') . '</div>';
} else if (!empty($custom_video_mp4)&& $sermon_video_option == 2) { ?>
<div class="video-container">
	<video width="320" height="240" poster="<?php echo $custom_video_poster; ?>" controls preload="none" class="custom-video">
    <!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
    <source type="video/mp4" src="<?php echo $custom_video_mp4; ?>" />
    <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
    <source type="video/webm" src="<?php echo $custom_video_webm; ?>" />
    <!-- Ogg/Vorbis for older Firefox and Opera versions -->
    <source type="video/ogg" src="<?php echo $custom_video_ogv; ?>" />
    <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
    <object width="320" height="240" type="application/x-shockwave-flash" data="flashmediaelement.swf">
        <param name="movie" value="<?php get_template_directory_uri() ?>/plugins/mediaelementflashmediaelement.swf" />
        <param name="flashvars" value="controls=true&file=<?php echo $custom_video_mp4; ?>" />
    </object>
	</video>
</div>
<?php } else if (!empty($custom['imic_sermons_url'][0])) {
    echo '<div class="video-container">' . imic_video_embed($custom['imic_sermons_url'][0], '200', '150') . '</div>';
}
if (!empty($attach_full_audio)) {
?>
<div class="audio-container">
<audio class="audio-player" src="<?php echo $attach_full_audio; ?>" type="audio/mp3" controls></audio>
</div>
<?php } ?>
<!-- Additional Media Attachments -->
<div class="tabs" id="additional-media-sermons">
  	<ul class="nav nav-tabs">
    	<?php if (!empty($add_custom_video_mp4)){ ?><li> <a data-toggle="tab" href="#addvideo"> <i class="fa fa-video-camera"></i> </a> </li><?php } ?>
    	<?php if (!empty($add_vimeo_video_url)){ ?><li> <a data-toggle="tab" href="#addvimeo"> <i class="fa fa-vimeo-square"></i> </a> </li><?php } ?>
    	<?php if (!empty($add_youtube_video_url)){ ?><li> <a data-toggle="tab" href="#addyoutube"> <i class="fa fa-youtube"></i> </a> </li><?php } ?>
    	<?php if (!empty($add_soundcloud_audio_url)){ ?><li> <a data-toggle="tab" href="#addsoundcloud"> <i class="fa fa-soundcloud"></i> </a> </li><?php } ?>
   	</ul>
  	<div class="tab-content">
    	<?php if (!empty($add_custom_video_mp4)){ ?><div id="addvideo" class="tab-pane">
        	<video width="320" height="240" poster="<?php echo $add_custom_video_poster; ?>" controls preload="none" class="custom-video">
                <!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
                <source type="video/mp4" src="<?php echo $add_custom_video_mp4; ?>" />
                <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                <source type="video/webm" src="<?php echo $add_custom_video_webm; ?>" />
                <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                <source type="video/ogg" src="<?php echo $add_custom_video_ogv; ?>" />
                <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
                <object width="320" height="240" type="application/x-shockwave-flash" data="flashmediaelement.swf">
                    <param name="movie" value="<?php get_template_directory_uri() ?>/plugins/mediaelementflashmediaelement.swf" />
                    <param name="flashvars" value="controls=true&file=<?php echo $add_custom_video_mp4; ?>" />
                </object>
            </video>
    	</div><?php } ?>
    	<?php if (!empty($add_vimeo_video_url)){ ?><div id="addvimeo" class="tab-pane">
      		<?php echo imic_video_embed($custom['imic_sermons_add_vimeo_url'][0], '200', '150'); ?>
      		
    	</div><?php } ?>
    	<?php if (!empty($add_youtube_video_url)){ ?><div id="addyoutube" class="tab-pane">
      		<?php echo imic_video_embed($custom['imic_sermons_add_youtube_url'][0], '200', '150'); ?>
      		
    	</div><?php } ?>
    	<?php if (!empty($add_soundcloud_audio_url)){ ?><div id="addsoundcloud" class="tab-pane">
        	<?php $soundcloud_audio_code = imic_audio_soundcloud($add_soundcloud_audio_url,"100%",250);
           	echo $soundcloud_audio_code; ?>
    	</div><?php } ?>
  	</div>
</div>

<?php

while (have_posts()):the_post();
echo '<div class="page-content">';
the_content();
echo '</div>';
/** Sermon Tags * */
$tag= get_the_term_list(get_the_ID(), 'sermons-tag', '', ', ', '');
if(!empty($tag)){
echo '<div class="post-meta">';
echo '<i class="fa fa-tags"></i>';
echo $tag;
echo '</div>';
}
endwhile;
?>
<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['4'] == '1') { ?>
	<?php imic_share_buttons(); ?>
<?php } ?>
</article>
<?php comments_template('', true); ?> 
</div>
<?php if(!empty($pageOptions['sidebar'])){ ?>
<!-- Start Sidebar -->
<div class="col-md-3 sidebar" id="sidebar-col">
    <?php dynamic_sidebar($pageOptions['sidebar']); ?>
</div>
<!-- End Sidebar -->
<?php } ?>
</div>
</div>
<?php get_footer(); ?>