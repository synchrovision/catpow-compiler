<?php

putenv('PATH='.getenv('PATH').':'.__DIR__.':'.__DIR__.'/node_modules/.bin');
putenv('NODE_PATH='.getenv('NODE_PATH').':'.__DIR__.'/node_modules');
passthru('cd '.__DIR__);

define('ABSPATH',dirname(__DIR__));

init();

while(true){
	$jsx_files=get_tgt_files('jsx');
	$scss_files=get_tgt_files('scss');
	$tmpl_files=get_tgt_files('tmpl.php');
	for($i=0;$i<20;$i++){
		cp_jsx_compile($jsx_files);
		Catpow\Scss::compile($scss_files);
		Catpow\Tmpl::compile($tmpl_files);
		sleep(3);
	}
}

function init(){
	spl_autoload_register(function($class){
		$f=__DIR__.'/classes/'.str_replace('\\','/',$class).'.php';
		if(file_exists($f)){include($f);}
	});
	foreach(glob(__DIR__.'/includes/*.php') as $inc_file){
		include $inc_file;
	}
	$default_dir=__DIR__.'/default';
	foreach(glob($default_dir.'/{*,.[!.]*}',GLOB_BRACE) as $default_file){
		if(is_dir($default_file)){
			$dir_name=basename($default_file);
			if(!is_dir(ABSPATH.'/'.$dir_name)){mkdir(ABSPATH.'/'.$dir_name);}
			foreach(glob($default_file.'/{*,.[!.]*}',GLOB_BRACE) as $default_child_file){
				$file_name=basename($default_child_file);
				if(!file_exists($f=ABSPATH.'/'.$dir_name.'/'.$file_name)){
					copy($default_child_file,$f);
				}
			}
		}
		else{
			$file_name=basename($default_file);
			if(!file_exists($f=ABSPATH.'/'.$file_name)){
				copy($default_file,$f);
			}
		}
	}
}

function get_tgt_files($ext){
	$files=glob(ABSPATH.'/[!_]*.'.$ext);
	foreach(glob(ABSPATH.'/*',GLOB_ONLYDIR) as $dir){
		if(in_array(basename($dir),['compiler','config'],true)){continue;}
		foreach(glob($dir.'{,/*,/*/*,/*/*/*}/[!_]*.'.$ext,GLOB_BRACE) as $file){
			$files[]=$file;
		}
	}
	return $files;
}

function cp_jsx_compile($jsx_files){
	foreach($jsx_files as $jsx_file){
		$js_file=substr($jsx_file,0,-1);
		if(!file_exists($jsx_file)){continue;}
		if(!file_exists($js_file) or filemtime($js_file) < filemtime($jsx_file)){
			passthru('babel '.$jsx_file.' -o '.$js_file.' > '.__DIR__.'/logs/result.txt');
			printf("build %s\n",substr($js_file,strlen(ABSPATH)));
			touch($js_file);
		}
	}
}