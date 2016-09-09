<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class CompositeContainer extends BaseContainer {

	/** @var ContainerInterface[] */
	protected $links = [];
	/** @var ContainerInterface */
	protected $parent;

	function __construct($links = [], ContainerInterface $parent = null){
		$this->addContainers($links);
		$this->parent = $parent;
	}

	/**
	 * @param ContainerInterface $container
	 */
	function addContainer($container){
		$this->links[] = $container;
	}

	/**
	 * @param ContainerInterface[] $containers
	 */
	function addContainers($containers){
		foreach($containers as $container){
			$this->addContainer($container);
		}
	}

	function hasItem($name){
		if($owner = $this->hasLinkedItem($name)){
			return true;
		}else{
			return $this->parent !== null && $this->parent->has($name);
		}
	}

	function getItem($name){
		if($owner = $this->hasLinkedItem($name)){
			return $owner->get($name);
		}else{
			return $this->parent->get($name);
		}
	}

	/**
	 * @param $name
	 * @return ContainerInterface|null
	 */
	protected function hasLinkedItem($name){
		static $is_recursive = false;
		if(!$is_recursive){
			$is_recursive = true;
			try{
				foreach($this->links as $link){
					if($link->has($name)){
						return $link;
					}
				}
			}finally{
				$is_recursive = false;
			}
		}
		return null;
	}

}
