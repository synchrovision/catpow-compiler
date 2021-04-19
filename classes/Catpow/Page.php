<?php
namespace Catpow;
class Page{
	public function __get($name){
		switch($name){
			case 'title':return '';
			case 'url':return '';
			case 'desc':return '';
			case 'image':return '';
		}
	}
}