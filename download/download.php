<?php
$file = $_GET['file'];
$ext = pathinfo($file, PATHINFO_EXTENSION);
$match_array =array('pdf','mp3','mpa','ra','wav','wma','mid','m4a','m3u','iff','aif');
if(in_array($ext,$match_array)){
header("Content-type: application/".$ext);
header("Content-Disposition: attachment; filename=". $file);
readfile($file);
}
?>