<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class CompositeContainer implements DiscoverableContainer
{

	/** @var DiscoverableContainer[] */
	private array $links = [];

	public function __construct(array $links = [])
	{
		$this->addContainers($links);
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
		return $this->hasLinkedItem($id) !== null;
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
		throw new NotFoundException($id);
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
