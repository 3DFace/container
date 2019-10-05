<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException as InteropContainerException;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

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
	 */
	public function hasItem($name) : bool
	{
		[$container_name, $item_name] = $this->path_resolver->resolve($name);
		if($container_name !== null) {
			if ($this->container->hasItem($container_name)) {
				/** @var $container ContainerInterface */
				$container = $this->container->getItem($container_name);
				if (!$container instanceof ContainerInterface) {
					return false;
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
	 * @throws InteropContainerException
	 * @throws InteropNotFoundException
	 */
	public function getItem($name)
	{
		[$container_name, $item_name] = $this->path_resolver->resolve($name);
		if($container_name !== null) {
			try{
				/** @var $container ContainerInterface */
				$container = $this->container->get($container_name);
			}catch (InteropNotFoundException $e){
				throw new NotFoundException("Item '$name' not found", 0, $e);
			}
			if (!$container instanceof ContainerInterface) {
				throw new ContainerException("Container '$container_name' in path '$name' must be an instance of ".ContainerInterface::class);
			}
			return $container->get($item_name);
		}
		return $this->container->get($item_name);
	}

}
