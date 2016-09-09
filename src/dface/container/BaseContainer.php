<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

abstract class BaseContainer implements Container, \ArrayAccess, ContainerInterface {

	public function offsetExists($offset){
		return $this->hasItem($offset);
	}

	public function offsetGet($offset){
		return $this->getItem($offset);
	}

	public function offsetSet($offset, $value){
		throw new \Exception("Unsupported container access");
	}

	public function offsetUnset($offset){
		throw new \Exception("Unsupported container access");
	}

	public function get($id){
		return $this->getItem($id);
	}

	public function has($id){
		return (bool)$this->hasItem($id);
	}

	function __invoke($id){
		return $this->getItem($id);
	}

}
