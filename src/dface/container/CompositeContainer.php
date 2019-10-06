<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

class CompositeContainer extends BaseContainer
{

	/** @var ContainerInterface[] */
	private $links = [];
	/** @var ContainerInterface */
	private $parent;

	public function __construct(array $links = [], ContainerInterface $parent = null)
	{
		$this->addContainers($links);
		$this->parent = $parent;
	}

	/**
	 * @param ContainerInterface $container
	 */
	public function addContainer($container) : void
	{
		$this->links[] = $container;
	}

	/**
	 * @param ContainerInterface[] $containers
	 */
	public function addContainers($containers) : void
	{
		foreach ($containers as $container) {
			$this->addContainer($container);
		}
	}

	public function hasItem($name) : bool
	{
		if ($owner = $this->hasLinkedItem($name)) {
			return true;
		}
		return $this->parent !== null && $this->parent->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function getItem($name)
	{
		if ($owner = $this->hasLinkedItem($name)) {
			return $owner->get($name);
		}
		return $this->parent->get($name);
	}

	/**
	 * @param $name
	 * @return ContainerInterface|null
	 */
	private function hasLinkedItem($name) : ?ContainerInterface
	{
		static $is_recursive = false;
		if (!$is_recursive) {
			$is_recursive = true;
			try{
				foreach ($this->links as $link) {
					if ($link->has($name)) {
						return $link;
					}
				}
			}finally{
				$is_recursive = false;
			}
		}
		return null;
	}

}
