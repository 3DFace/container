<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

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

	public function has($name) : bool
	{
		return $this->primary->has($name) || $this->secondary->has($name);
	}

	public function get($name)
	{
		if ($this->primary->has($name)) {
			return $this->primary->get($name);
		}
		return $this->secondary->get($name);
	}
}
