<?php

namespace dface\container;

class AContainer extends BaseContainer implements Container {

	/** @var Container */
	protected $lookupContainer;
	protected $definitions = [];
	protected $items = [];

	function __construct($definitions = [], Container $parent = null){
		$this->definitions = $definitions;
		$this->lookupContainer = $parent ? new ContainerLink($this, $parent) : $this;
		$this->parent = $parent;
	}

	function hasItem($name){
		return array_key_exists($name, $this->definitions) ? $this : false;
	}

	function getItem($name){
		if(array_key_exists($name, $this->definitions)){
			if(array_key_exists($name, $this->items)){
				return $this->items[$name];
			}else{
				return $this->initItem($name, $this->definitions[$name]);
			}
		}
		throw new ContainerException("Item '$name' not found");
	}

	protected function initItem($name, $definition){
		$this->definitions[$name] = function () use ($name){
			throw new ContainerException("Cyclic dependency, item '$name' not initialized yet, use reg-func (2nd param of factory) to make item early available");
		};
		$item = $this->constructItem($name, $definition);
		$this->definitions[$name] = $definition;
		$this->items[$name] = $item;
		return $item;
	}

	protected function constructItem($name, $definition){
		try{
			if(is_callable($definition)){
				return $definition($this->lookupContainer, function ($early) use ($name){
					$this->items[$name] = $early;
				});
			}else{
				return $definition;
			}
		}catch(\Exception $e){
			throw new ContainerException("Cant construct '$name': ".$e->getMessage());
		}
	}

}
