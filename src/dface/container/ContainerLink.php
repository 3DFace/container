<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class ContainerLink extends BaseContainer {

	/** @var ContainerInterface */
	protected $primary;
	/** @var ContainerInterface */
	protected $secondary;

	function __construct(ContainerInterface $primary, ContainerInterface $secondary){
		$this->primary = $primary;
		$this->secondary = $secondary;
	}

	function hasItem($name){
		return $this->primary->has($name) || $this->secondary->has($name);
	}

	function getItem($name){
		if($this->primary->has($name)){
			return $this->primary->get($name);
		}else{
			return $this->secondary->get($name);
		}
	}
}
