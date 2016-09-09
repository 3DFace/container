<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class NamespaceContainer extends BaseContainer {

	/** @var ContainerInterface */
	protected $target;
	protected $namespace;

	function __construct(ContainerInterface $target, $namespace){
		$this->target = $target;
		$this->namespace = $namespace;
	}

	function hasItem($name){
		$mod_name = $this->namespace.$name;
		if($this->target->has($mod_name)){
			return true;
		}else{
			if(strcmp($mod_name, $name)){
				return $this->target->has($name);
			}else{
				return false;
			}
		}
	}

	function getItem($name){
		$mod_name = $this->namespace.$name;
		if($this->target->has($mod_name)){
			return $this->target->get($mod_name);
		}else{
			return $this->target->get($name);
		}
	}

} 
