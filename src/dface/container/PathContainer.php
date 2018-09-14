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
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function hasItem($name) : bool
	{
		/** @var $container ContainerInterface */
		try{
			[$container, $resolved_name] = $this->splitContainerAndItemName($name);
		}catch (ContainerException $e){
			return false;
		}
		return $container->has($resolved_name);
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
		/** @var $container ContainerInterface */
		[$container, $resolved_name] = $this->splitContainerAndItemName($name);
		return $container->get($resolved_name);
	}

	/**
	 * @param $full_name
	 * @return array|null
	 * @throws ContainerException
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	protected function splitContainerAndItemName($full_name) : ?array
	{
		[$container_name, $item_name] = $this->path_resolver->resolve($full_name);
		if ($container_name !== null) {
			$container = $this->getItem($container_name);
			if (!$container instanceof ContainerInterface) {
				throw new ContainerException("Container '$container_name' in path '$full_name' must be an instance of ".ContainerInterface::class);
			}
			return [$container, $item_name];
		}
		return [$this->container, $full_name];
	}

}
