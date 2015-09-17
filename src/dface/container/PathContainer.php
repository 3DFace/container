<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class PathContainer extends BaseContainer {

	/** @var Container */
	protected $container;
	/** @var PathResolver */
	protected $path_resolver;

	function __construct(Container $container, PathResolver $path_resolver = null){
		$this->container = $container;
		$this->path_resolver = $path_resolver ?: new DefaultPathResolver();
	}

	function hasItem($name){
		/** @var $container Container */
		try{
			list($container, $resolved_name) = $this->splitContainerAndItemName($name);
		}catch(ContainerException $e){
			return false;
		}
		return $container->hasItem($resolved_name) ? $this : false;
	}

	function getItem($name){
		/** @var $container Container */
		list($container, $resolved_name) = $this->splitContainerAndItemName($name);
		return $container->getItem($resolved_name);
	}

	protected function splitContainerAndItemName($full_name){
		list($container_name, $item_name) = $this->path_resolver->resolve($full_name);
		if($container_name !== null){
			$container = $this->getItem($container_name);
			if(!$container instanceof Container){
				throw new ContainerException("Container '$container_name' in path '$full_name' must be an instance of ".Container::class);
			}
			return [$container, $item_name];
		}else{
			return [$this->container, $full_name];
		}
	}

}
