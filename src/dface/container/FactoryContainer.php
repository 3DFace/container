<?php

namespace dface\container;

use Interop\Container\ContainerInterface;

class FactoryContainer extends BaseContainer {

	/** @var ContainerInterface */
	protected $lookupContainer;
	protected $definitions = [];

	function __construct($definitions = [], ContainerInterface $lookupContainer = null){
		$this->definitions = $definitions;
		$this->lookupContainer = $lookupContainer ?: $this;
	}

	function hasItem($name){
		return array_key_exists($name, $this->definitions);
	}

	function getItem($name){
		if(array_key_exists($name, $this->definitions)){
			return $this->initItem($name, $this->definitions[$name]);
		}
		throw new NotFoundException("Item '$name' not found");
	}

	protected function initItem($name, $definition){
		$this->definitions[$name] = function () use ($name){
			throw new ContainerException("Cyclic dependency, item '$name' already in construction phase");
		};
		$item = $this->constructItem($name, $definition);
		$this->definitions[$name] = $definition;
		return $item;
	}

	protected function constructItem($name, $definition){
		try{
			if(is_callable($definition)){
				return $definition($this->lookupContainer, $name);
			}else{
				return $definition;
			}
		}catch(\Exception $e){
			throw new ContainerException("Cant construct '$name': ".$e->getMessage());
		}
	}

}
