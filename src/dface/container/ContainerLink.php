<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class ContainerLink extends BaseContainer
{

	/** @var ContainerInterface */
	private $primary;
	/** @var ContainerInterface */
	private $secondary;

	public function __construct(ContainerInterface $primary, ContainerInterface $secondary)
	{
		$this->primary = $primary;
		$this->secondary = $secondary;
	}

	public function hasItem($name) : bool
	{
		return $this->primary->has($name) || $this->secondary->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function getItem($name)
	{
		if ($this->primary->has($name)) {
			return $this->primary->get($name);
		}
		return $this->secondary->get($name);
	}
}
