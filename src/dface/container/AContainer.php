<?php

namespace dface\container;

use Interop\Container\ContainerInterface;

class AContainer extends BaseContainer
{

	/** @var ContainerInterface */
	private $container;
	/** @var bool[] */
	private $has_cache = [];
	/** @var array */
	private $get_cache = [];

	public function __construct(array $definitions = [], ContainerInterface $parent = null)
	{
		$lookup = $parent ? new ContainerLink($this, $parent) : $this;
		$factories = new FactoryContainer($definitions, $lookup);
		$this->container = new SingletonContainer($factories);
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasItem($name) : bool
	{
		if (!isset($this->has_cache[$name])) {
			$this->has_cache[$name] = $this->container->has($name);
		}
		return $this->has_cache[$name];
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function getItem($name)
	{
		if (!isset($this->get_cache[$name])) {
			$this->get_cache[$name] = $this->container->get($name);
		}
		return $this->get_cache[$name];
	}

}
