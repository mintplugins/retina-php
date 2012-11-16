<?php
/**
 * load image function for retina
 */
function retina_php_load_image($url){
	$path_parts = pathinfo($url);
	$url = $path_parts['dirname'] . '/' . $path_parts['filename'] . '@2x.' . $path_parts['extension'];
	echo ($url);
}//retina_php_load_image



