<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class OptionsContainer extends BaseContainer {

	protected $options;
	/** @var Container */
	protected $parent;

	/**
	 * @param string|array $options
	 * @param Container|null $parent
	 */
	function __construct($options, $parent = null){
		$this->options =  is_array($options) ? $options : $this->parseOpts($options);
		$this->parent = $parent;
	}

	function hasItem($name){
		if(array_key_exists($name, $this->options)){
			return $this;
		}else{
			return $this->parent ? $this->parent->hasItem($name) : false;
		}
	}

	function getItem($name){
		if(array_key_exists($name, $this->options)){
			return $this->options[$name];
		}else{
			if($this->parent !== null){
				return $this->parent->getItem($name);
			}else{
				throw new \InvalidArgumentException("Item '$name' not found");
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
