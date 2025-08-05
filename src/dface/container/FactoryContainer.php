<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class FactoryContainer implements DiscoverableContainer
{

	private ContainerInterface $lookupContainer;
	private DefinitionSource $definitions;
	private array $construction_track = [];

	public function __construct(DefinitionSource $definitions, ?ContainerInterface $lookupContainer = null)
	{
		$this->definitions = $definitions;
		$this->lookupContainer = $lookupContainer ?? $this;
	}

	public function has(string $id) : bool
	{
		return $this->definitions->hasDefinition($id);
	}

	/**
	 * @param string $id
	 * @return mixed
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function get(string $id) : mixed
	{
		if (isset($this->construction_track[$id])) {
			throw new ContainerException("Cyclic dependency, item '$id' already in construction phase");
		}
		$definition = $this->definitions->getDefinition($id);
		$this->construction_track[$id] = true;
		try {
			$item = $definition($this->lookupContainer, $id);
		} catch (\Exception $e) {
			throw new ContainerException("Cant construct '$id': ".$e->getMessage());
		} finally {
			unset($this->construction_track[$id]);
		}
		return $item;
	}

	public function getNames() : iterable
	{
		return $this->definitions->getNames();
	}

}
