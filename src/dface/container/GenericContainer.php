<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class GenericContainer extends BaseContainer implements DiscoverableContainer
{

	private readonly FactoryContainer $factories;

	public function __construct(DefinitionSource $definitions, ?ContainerInterface $parent = null)
	{
		$lookup = $parent ? new BaseContainer(new ContainerJoin($this, $parent)) : $this;
		$this->factories = new FactoryContainer($definitions, $lookup);
		$container = new SingletonContainer($this->factories);
		parent::__construct($container);
	}

	public function getNames() : iterable
	{
		return $this->factories->getNames();
	}

}
