<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class ContainerLink extends BaseContainer
{

	private ContainerInterface $target;
	private array $id_mapping;

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
		return PathResolver::containerGetPath($this->target, $id);
	}

	public function has($id) : bool
	{
		return isset($this->id_mapping[$id]);
	}

}
