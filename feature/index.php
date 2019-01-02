<?php 
$img_array = glob("gallery/*.{gif,jpg,png}",GLOB_BRACE); 

$img = array_rand($img_array); 

$imgurl=$img_array[$img]; 

if($imgurl) { 
	header("Location: " . $imgurl);
	exit();
} else {
	exit('error');
}
?>
