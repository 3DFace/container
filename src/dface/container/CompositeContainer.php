<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;


class CompositeContainer extends BaseContainer implements Container {

	/** @var Container[] */
	protected $links = [];
	/** @var Container */
	protected $parent;
	/** @var bool */
	private $is_recursive = false;

	function __construct($links = [], $parent = null){
		$this->addContainers($links);
		$this->parent = $parent;
	}

	/**
	 * @param Container $container
	 */
	function addContainer($container){
		$this->links[] = $container;
	}

	/**
	 * @param Container[] $containers
	 */
	function addContainers($containers){
		foreach($containers as $container){
			$this->addContainer($container);
		}
	}

	function hasItem($name){
		if($owner = $this->hasLinkedItem($name)){
			return $owner;
		}else{
			return $this->parent ? $this->parent->hasItem($name) : false;
		}
	}

	function getItem($name){
		if($owner = $this->hasItem($name)){
			return $owner->getItem($name);
		}else{
			throw new ContainerException("Item '$name' not found");
		}
	}

	protected function hasLinkedItem($name){
		if(!$this->is_recursive){
			$this->is_recursive = true;
			try{
				foreach($this->links as $link){
					if($owner = $link->hasItem($name)){
						$this->is_recursive = false;
						return $owner;
					}
				}
			} catch(\Exception $e){
				$this->is_recursive = false;
				throw $e;
			}
			$this->is_recursive = false;
		}
		return false;
	}

}
