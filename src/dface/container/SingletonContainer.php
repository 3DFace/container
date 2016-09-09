<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class SingletonContainer extends BaseContainer {

	/** @var ContainerInterface */
	protected $factory;
	protected $items = [];

	/**
	 * SingletonContainer constructor.
	 * @param ContainerInterface $factory
	 */
	public function __construct(ContainerInterface $factory){
		$this->factory = $factory;
	}

	function hasItem($name){
		return array_key_exists($name, $this->items) || $this->factory->has($name);
	}

	function getItem($name){
		if(array_key_exists($name, $this->items)){
			return $this->items[$name];
		}else{
			$item = $this->factory->get($name);
			$this->items[$name] = $item;
			return $item;
		}
	}

}
