<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class OptionsContainer extends BaseContainer {

	protected $options;
	/** @var ContainerInterface */
	protected $parent;

	/**
	 * @param string|array $options
	 * @param ContainerInterface|null $parent
	 */
	function __construct($options, ContainerInterface $parent = null){
		$this->options =  is_array($options) ? $options : $this->parseOpts($options);
		$this->parent = $parent;
	}

	function hasItem($name){
		if(array_key_exists($name, $this->options)){
			return true;
		}else{
			return $this->parent !== null && $this->parent->has($name);
		}
	}

	function getItem($name){
		if(array_key_exists($name, $this->options)){
			return $this->options[$name];
		}else{
			if($this->parent !== null){
				return $this->parent->get($name);
			}else{
				throw new NotFoundException("Item '$name' not found");
			}
		}
	}

	protected function parseOpts($str){
		$opts = array();
		foreach(preg_split("|\n+|", $str) as $line){
			if(preg_match("|^\s*([\w\-.]+)\s*=\s*(\S+)\s*$|", $line, $match)){
				$opts[$match[1]] = $match[2];
			}
		}
		return $opts;
	}

} 
