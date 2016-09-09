<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class PathContainer extends BaseContainer {

	/** @var ContainerInterface */
	protected $container;
	/** @var PathResolver */
	protected $path_resolver;

	function __construct(ContainerInterface $container, PathResolver $path_resolver = null){
		$this->container = $container;
		$this->path_resolver = $path_resolver ?: new DefaultPathResolver();
	}

	function hasItem($name){
		/** @var $container ContainerInterface */
		try{
			list($container, $resolved_name) = $this->splitContainerAndItemName($name);
		}catch(ContainerException $e){
			return false;
		}
		return $container->has($resolved_name);
	}

	function getItem($name){
		/** @var $container ContainerInterface */
		list($container, $resolved_name) = $this->splitContainerAndItemName($name);
		return $container->get($resolved_name);
	}

	protected function splitContainerAndItemName($full_name){
		list($container_name, $item_name) = $this->path_resolver->resolve($full_name);
		if($container_name !== null){
			$container = $this->getItem($container_name);
			if(!$container instanceof ContainerInterface){
				throw new ContainerException("Container '$container_name' in path '$full_name' must be an instance of ".ContainerInterface::class);
			}
			return [$container, $item_name];
		}else{
			return [$this->container, $full_name];
		}
	}

}
