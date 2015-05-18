<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class ContainerLink extends BaseContainer {

	/** @var Container */
	protected $primary;
	/** @var Container */
	protected $secondary;

	function __construct(Container $primary, Container $secondary){
		$this->primary = $primary;
		$this->secondary = $secondary;
	}

	function hasItem($name){
		if($owner = $this->primary->hasItem($name)){
			return $owner;
		}else{
			return $this->secondary->hasItem($name);
		}
	}

	function getItem($name){
		if($owner = $this->hasItem($name)){
			return $owner->getItem($name);
		} else{
			throw new \InvalidArgumentException("Item '$name' not found");
		}
	}
}
