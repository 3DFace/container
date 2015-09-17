<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class SingletonContainer extends BaseContainer {

	/** @var Container */
	protected $factory;
	protected $items = [];

	/**
	 * SingletonContainer constructor.
	 * @param Container $factory
	 */
	public function __construct(Container $factory){
		$this->factory = $factory;
	}

	function hasItem($name){
		return array_key_exists($name, $this->items) || $this->factory->hasItem($name) ? $this : false;
	}

	function getItem($name){
		if(array_key_exists($name, $this->items)){
			return $this->items[$name];
		}else{
			$item = $this->factory->getItem($name);
			$this->items[$name] = $item;
			return $item;
		}
	}

}
