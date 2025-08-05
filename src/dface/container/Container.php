<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;

/**
 * Use Psr\Container\ContainerInterface instead
 * @deprecated
 */
interface Container
{
	/**
	 * @throws ContainerExceptionInterface
	 */
	public function getItem(string $name) : mixed;

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function hasItem(string $name) : bool;
}
