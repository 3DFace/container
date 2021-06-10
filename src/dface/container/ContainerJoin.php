<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class ContainerJoin extends BaseContainer
{

	private ContainerInterface $primary;
	private ContainerInterface $secondary;

	public function __construct(ContainerInterface $primary, ContainerInterface $secondary)
	{
		$this->primary = $primary;
		$this->secondary = $secondary;
	}

	public function getPrimary() : ContainerInterface
	{
		return $this->primary;
	}

	public function getSecondary() : ContainerInterface
	{
		return $this->secondary;
	}

	public function has($id) : bool
	{
		return $this->primary->has($id) || $this->secondary->has($id);
	}

	public function get($id)
	{
		if ($this->primary->has($id)) {
			return $this->primary->get($id);
		}
		return $this->secondary->get($id);
	}

}
