<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class CompositeContainer extends BaseContainer implements DiscoverableContainer
{

	/** @var DiscoverableContainer[] */
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

	public function has(string $id) : bool
	{
		if ($this->hasLinkedItem($id)) {
			return true;
		}
		return $this->parent !== null && $this->parent->has($id);
	}

	/**
	 * @param string $id
	 * @return mixed
	 * @throws ContainerExceptionInterface
	 */
	public function get(string $id) : mixed
	{
		if ($owner = $this->hasLinkedItem($id)) {
			return $owner->get($id);
		}
		return $this->parent->get($id);
	}

	public function getNames() : iterable
	{
		foreach ($this->links as $link){
			yield from $link->getNames();
		}
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
