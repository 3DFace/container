<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

abstract class BaseContainer implements Container, \ArrayAccess {

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

}
