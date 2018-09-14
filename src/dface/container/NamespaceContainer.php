<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

class NamespaceContainer extends BaseContainer
{

	/** @var ContainerInterface */
	protected $target;
	protected $namespace;

	public function __construct(ContainerInterface $target, $namespace)
	{
		$this->target = $target;
		$this->namespace = $namespace;
	}

	public function hasItem($name) : bool
	{
		$mod_name = $this->namespace.$name;
		if ($this->target->has($mod_name)) {
			return true;
		}
		if (strcmp($mod_name, $name)) {
			return $this->target->has($name);
		}
		return false;
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function getItem($name)
	{
		$mod_name = $this->namespace.$name;
		if ($this->target->has($mod_name)) {
			return $this->target->get($mod_name);
		}
		return $this->target->get($name);
	}

} 
