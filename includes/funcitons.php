<?php
namespace Catpow;

function picture($name,$alt){
	printf('<picture><source media="(max-width: 767px)" srcset="images/%1$s_sp.png"><img src="images/%1$s.png" alt="%2$s"/></picture>',$name,$alt);
}
function simple_md($text,$param=[]){
	$param=array_merge(
		['link_class'=>'link','image_class'=>'image'],
		$param
	);
	$text=preg_replace('/!\[(.+?)\]\((.+?)\)/','<img class="'.$param['image_class'].'" src="$2" alt="$1"/>',$text);
	$text=preg_replace('/\[(.+?)\]\((.+?)\)/','<a class="'.$param['link_class'].'" href="$2" target="_brank">$1</a>',$text);
	return $text;
}

function do_shortcode($str){
	return ShortCode::do_shortcode($str);
}
function add_shortcode($name,$function){
	return ShortCode::add_shortcode($name,$function);
}