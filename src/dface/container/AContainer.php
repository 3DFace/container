<?php

namespace dface\container;

use Interop\Container\ContainerInterface;

class AContainer extends BaseContainer {

	/** @var Container */
	protected $container;

	function __construct(array $definitions = [], ContainerInterface $parent = null){
		$lookup = $parent ? new ContainerLink($this, $parent) : $this;
		$factories = new FactoryContainer($definitions, $lookup);
		$singletons = new SingletonContainer($factories);
		$this->container = new PathContainer($singletons);
	}

	function hasItem($name){
		return $this->container->hasItem($name);
	}

	function getItem($name){
		return $this->container->getItem($name);
	}

}
