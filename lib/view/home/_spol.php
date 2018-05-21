<?php

        $Featurebox = new Featurebox();
        $Spol = new stdClass();
        $Spol->post_type = 'youtube';
        $Spol->caption = 'Sample Speaking of life';
        $Spol->post_title = "The Great What If";
        $Featurebox->Post = $Spol;
        echo $Featurebox->render();
