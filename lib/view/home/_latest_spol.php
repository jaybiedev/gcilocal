<?php
include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/Spol.php");

    $Featurebox = new Featurebox();
    $Spol = new Spol();
    
    $SpolPost = new stdClass();
    $SpolPost->post_type = 'youtube';
    
    
    if ($Spol->file == false) {
        $SpolPost->id = '_yB-bzRYnus';
        $SpolPost->caption = 'When God looks at us, he doesn’t see our “rust.”';
        $SpolPost->post_title = "Rust and Righteousness";
    }
    else {
        $SpolFirst = $Spol->getFirst();
        $SpolPost->id = $SpolFirst->id;
        $SpolPost->caption = $SpolFirst->caption;
        $SpolPost->post_title = $SpolFirst->post_title;
    }
    
    $Featurebox->Post = $SpolPost;
	echo "<div class=\"video-gallery\">";
        echo $Featurebox->render();
	echo "</div>";
