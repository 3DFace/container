<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Psr\Container\ContainerInterface;

class CompositeContainer extends BaseContainer
{

	/** @var ContainerInterface[] */
	private array $links = [];
	private ?ContainerInterface $parent;

	public function __construct(array $links = [], ContainerInterface $parent = null)
	{
		$this->addContainers($links);
		$this->parent = $parent;
	}

	public function addContainer(ContainerInterface $container) : void
	{
		$this->links[] = $container;
	}

	/**
	 * @param ContainerInterface[] $containers
	 */
	public function addContainers(array $containers) : void
	{
		foreach ($containers as $container) {
			$this->addContainer($container);
		}
	}

	public function has($name) : bool
	{
		if ($owner = $this->hasLinkedItem($name)) {
			return true;
		}
		return $this->parent !== null && $this->parent->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function get($name)
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
