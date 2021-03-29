<?php
namespace Catpow;
class Scss{
	public static function compile($scss_files){
		if(version_compare(PHP_VERSION, '5.4')<0)return;
		static $scssc;
		$css_files=[];
		if(!file_exists($config_file=ABSPATH.'/compiler/config/style_config.scss')){$style_config_modified_time=0;}
		else{$style_config_modified_time=filemtime($config_file);}
		foreach($scss_files as $scss_file){
			$css_file=substr(str_replace(['/scss/','/_scss/'],'/css/',$scss_file),0,-4).'css';
			if(!is_dir(dirname($css_file))){mkdir(dirname($css_file),0777,true);}
			$css_files[]=$css_file;
			if(
				!file_exists($css_file) or
				filemtime($css_file) < max(
					filemtime($scss_file),
					$style_config_modified_time
				)
			){
				if(empty($scssc)){
					$scssc = new \ScssPhp\ScssPhp\Compiler();
					$scssc->addImportPath(ABSPATH.'/');
					$scssc->addImportPath(ABSPATH.'/compiler/config/');
					$scssc->addImportPath(ABSPATH.'/compiler/scss/');
					$scssc->setSourceMap(\ScssPhp\ScssPhp\Compiler::SOURCE_MAP_FILE);
					$scssc->setIgnoreErrors(true);
				}
				try{
					$scssc->setSourceMapOptions([
						'sourceMapWriteTo'=>$css_file.'.map',
						'sourceMapURL'=>'./'.basename($css_file).'.map',
						'sourceMapFilename'=>basename($css_file).'.map',
						'sourceMapBasepath'=>$_SERVER['DOCUMENT_ROOT'],
						'sourceRoot'=>'/'
					]);
					$css=$scssc->compile(file_get_contents($scss_file),$scss_file);
				}catch(Exception $e){
					echo $e->getMessage();
				}
				file_put_contents($css_file,$css);
				printf("build %s\n",substr($css_file,strlen(ABSPATH)));
			}
		}
	}
}