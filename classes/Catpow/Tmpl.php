<?php
namespace Catpow;
class Tmpl{
	public static $tmpl,$dir;
	public static function compile($tmpl_files){
		foreach($tmpl_files as $tmpl_file){
			$html_file=substr($tmpl_file,0,-8).'html';
			if(!file_exists($tmpl_file)){continue;}
			if(!file_exists($html_file) or filemtime($html_file) < filemtime($tmpl_file)){
				self::$tmpl=$tmpl_file;
				self::$dir=dirname($tmpl_file);
				ob_start();
				include $tmpl_file;
				file_put_contents($html_file,ob_get_clean());
				echo "build {$html_file}\n";
				touch($tmpl_file);
				self::$tmpl=null;
				self::$dir=null;
			}
		}
	}
}