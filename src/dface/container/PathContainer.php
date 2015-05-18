<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class PathContainer extends BaseContainer {

	/** @var Container */
	protected $container;
	/** @var string */
	protected $path_separator;

	function __construct(Container $container, $path_separator = '|/|'){
		$this->container = $container;
		$this->path_separator = $path_separator;
	}

	function hasItem($name){
		/** @var $container Container */
		list($container, $item_name) = $this->splitContainerItemName($name);
		return $container->hasItem($item_name);
	}

	function getItem($name){
		/** @var $container Container */
		list($container, $item_name) = $this->splitContainerItemName($name);
		return $container->getItem($item_name);
	}

	function splitContainerItemName($full_name){
		$path = preg_split($this->path_separator, $full_name, -1, PREG_SPLIT_NO_EMPTY);
		$item_name = array_pop($path);
		$container = $this->container;
		foreach($path as $name){
			$container = $container->getItem($name);
		}
		return [$container, $item_name];
	}

}
