<?php

namespace dface\container;

class ArrayDefinitionSource implements DefinitionSource
{

	private array $definitions;

	/**
	 * @param array $definitions - string => callable
	 */
	public function __construct(array $definitions)
	{
		$this->definitions = $definitions;
	}

	public function hasDefinition(string $name) : bool
	{
		return \array_key_exists($name, $this->definitions);
	}

	/**
	 * @param string $name
	 * @return callable
	 * @throws NotFoundException
	 */
	public function getDefinition(string $name) : callable
	{
		if (!\array_key_exists($name, $this->definitions)) {
			throw new NotFoundException("Item '$name' not found");
		}
		$def = $this->definitions[$name];
		return \is_callable($def) ? $def : static function () use ($def) {
			return $def;
		};
	}

	public function getNames() : iterable
	{
		return \array_keys($this->definitions);
	}

}
