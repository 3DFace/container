<?php

namespace dface\container;

interface DefinitionSource
{

	public function hasDefinition(string $name) : bool;

	/**
	 * @param string $name
	 * @return callable
	 * @throws NotFoundException
	 */
	public function getDefinition(string $name) : callable;

	/**
	 * @return iterable
	 */
	public function getNames() : iterable;

}
