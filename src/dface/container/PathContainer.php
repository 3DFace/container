<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class PathContainer extends BaseContainer
{

	/** @var ContainerInterface */
	protected $container;
	/** @var PathResolver */
	protected $path_resolver;

	public function __construct(ContainerInterface $container, PathResolver $path_resolver = null)
	{
		$this->container = $container;
		$this->path_resolver = $path_resolver ?: new DefaultPathResolver();
	}

	/**
	 * @param string $name
	 * @return bool
	 * @throws \Interop\Container\Exception\ContainerException
	 */
	public function hasItem($name) : bool
	{
		[$container_name, $item_name] = $this->path_resolver->resolve($name);
		if($container_name !== null) {
			if ($this->container->hasItem($container_name)) {
				/** @var $container ContainerInterface */
				$container = $this->container->getItem($container_name);
				if (!$container instanceof ContainerInterface) {
					throw new ContainerException("Container '$container_name' in path '$name' must be an instance of ".ContainerInterface::class);
				}
				return $container->has($item_name);
			}
			return false;
		}
		return $this->container->hasItem($item_name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws ContainerException
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function getItem($name)
	{
		[$container_name, $item_name] = $this->path_resolver->resolve($name);
		if($container_name !== null) {
			/** @var $container ContainerInterface */
			$container = $this->container->getItem($container_name);
			if (!$container instanceof ContainerInterface) {
				throw new ContainerException("Container '$container_name' in path '$name' must be an instance of ".ContainerInterface::class);
			}
			return $container->get($item_name);
		}
		return $this->container->get($item_name);
	}

}
