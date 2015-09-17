<?php

namespace dface\container;

class AContainer extends BaseContainer implements Container {

	/** @var Container */
	protected $container;

	function __construct(array $definitions = [], Container $parent = null){
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
