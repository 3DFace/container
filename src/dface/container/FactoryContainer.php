<?php

namespace dface\container;

use Interop\Container\ContainerInterface;

class FactoryContainer implements ContainerInterface
{

	/** @var ContainerInterface */
	private $lookupContainer;
	private $definitions;

	public function __construct(array $definitions = [], ContainerInterface $lookupContainer = null)
	{
		$this->definitions = $definitions;
		$this->lookupContainer = $lookupContainer ?: $this;
	}

	public function has($name) : bool
	{
		return \array_key_exists($name, $this->definitions);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function get($name)
	{
		if (\array_key_exists($name, $this->definitions)) {
			return $this->initItem($name, $this->definitions[$name]);
		}
		throw new NotFoundException("Item '$name' not found");
	}

	/**
	 * @param $name
	 * @param $definition
	 * @return mixed
	 * @throws ContainerException
	 */
	private function initItem($name, $definition)
	{
		$this->definitions[$name] = static function () use ($name) {
			throw new ContainerException("Cyclic dependency, item '$name' already in construction phase");
		};
		$item = $this->constructItem($name, $definition);
		$this->definitions[$name] = $definition;
		return $item;
	}

	/**
	 * @param $name
	 * @param $definition
	 * @return mixed
	 * @throws ContainerException
	 */
	private function constructItem($name, $definition)
	{
		try{
			if (\is_callable($definition)) {
				return $definition($this->lookupContainer, $name);
			}
			return $definition;
		}catch (\Exception $e){
			throw new ContainerException("Cant construct '$name': ".$e->getMessage());
		}
	}

}
