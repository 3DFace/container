<?php

namespace dface\container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerLink extends BaseContainer
{

	/** @var ContainerInterface */
	private $target;
	/** @var array */
	private $id_mapping;

	public function __construct(ContainerInterface $target, array $id_mapping)
	{
		$this->target = $target;
		$this->id_mapping = $id_mapping;
	}

	public function get($id)
	{
		if (!isset($this->id_mapping[$id])) {
			throw new NotFoundException("Link '$id' not defined");
		}
		$id = $this->id_mapping[$id];
		try{
			[$container, $item_name] = self::getDeepestContainerAndItemName($this->target, $id);
			if (!$container instanceof ContainerInterface) {
				$type = \gettype($container);
				$relative_name = \substr($id, 0, -\strlen($item_name));
				throw new ContainerException("'$relative_name' expected to be a ContainerInterface, got '$type'");
			}
			return $container->get($item_name);
		}catch (NotFoundExceptionInterface $e){
			throw new NotFoundException("'$id' not found", 0, $e);
		}
	}

	public function has($id) : bool
	{
		return isset($this->id_mapping[$id]);
	}

}
