<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class NamespaceContainer extends BaseContainer {

	/** @var Container */
	protected $target;
	protected $namespace;

	function __construct(Container $target, $namespace){
		$this->target = $target;
		$this->namespace = $namespace;
	}

	function hasItem($name){
		$mod_name = $this->namespace.$name;
		if($owner = $this->target->hasItem($mod_name)){
			return $this;
		}else{
			if(strcmp($mod_name, $name)){
				return $this->target->hasItem($name);
			}else{
				return false;
			}
		}
	}

	function getItem($name){
		$mod_name = $this->namespace.$name;
		if($owner = $this->target->hasItem($mod_name)){
			return $owner->getItem($mod_name);
		}else{
			return $this->target->getItem($name);
		}
	}

} 
