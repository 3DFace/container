<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class Explorer
{

	private ContainerInterface $container;
	private string $descriptor;

	public function __construct(ContainerInterface $basePathContainer, string $descriptor)
	{
		$this->container = $basePathContainer;
		$this->descriptor = $descriptor;
	}

	/**
	 * @param string|null $containerName
	 * @return array
	 * @throws ContainerExceptionInterface
	 */
	public function getNames(?string $containerName = null) : array
	{
		$c = $containerName ? $this->container->get($containerName) : $this->container;
		$d = $this->getDescriptions($c);
		return \array_keys($d);
	}

	/**
	 * @param string|null $containerName
	 * @return array
	 * @throws ContainerExceptionInterface
	 */
	public function getServicesInfo(?string $containerName = null) : array
	{
		$c = $containerName ? $this->container->get($containerName) : $this->container;
		$d = $this->getDescriptions($c);
		$result = [];
		foreach ($d as $shortName => [$class, $desc]) {
			$result[] = self::extractServiceDetails($shortName, $class, $desc);
		}
		return $result;
	}

	/**
	 * @param string|null $containerName
	 * @param string $serviceShortName
	 * @return mixed
	 * @throws ContainerExceptionInterface
	 */
	public function getServiceDescription(?string $containerName, string $serviceShortName) : mixed
	{
		$c = $containerName ? $this->container->get($containerName) : $this->container;
		$d = $this->getDescriptions($c);
		if (isset($d[$serviceShortName])) {
			return $d[$serviceShortName];
		}
		throw new \InvalidArgumentException("Service $serviceShortName not described");
	}

	/**
	 * @param string|null $containerName
	 * @param string $serviceShortName
	 * @return array
	 * @throws ContainerExceptionInterface
	 */
	public function getServiceDetails(?string $containerName, string $serviceShortName) : array
	{
		$c = $containerName ? $this->container->get($containerName) : $this->container;
		$d = $this->getDescriptions($c);
		if (isset($d[$serviceShortName])) {
			[$class, $desc] = $d[$serviceShortName];
			return self::extractServiceDetails($serviceShortName, $class, $desc);
		}
		throw new \InvalidArgumentException("Service $serviceShortName not described");
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	private function getDescriptions(ContainerInterface $container) : array
	{
		return $container->has($this->descriptor) ? $container->get($this->descriptor) : [];
	}

	private static function extractServiceDetails(string $shortName, string $class, string $desc) : array
	{
		try{
			$reflectionClass = new \ReflectionClass($class);
		}catch (\ReflectionException $e){
			throw new \RuntimeException($e->getMessage(), 0, $e);
		}
		$methods = [];
		$is_container = \is_a($class, ContainerInterface::class, true);
		if (!$is_container) {
			foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
				if (!$method->isConstructor()) {
					$params = \array_map(static function (\ReflectionParameter $param) {
						return $param->getName();
					}, $method->getParameters());

					$methods[] = [
						'name' => $method->getName(),
						'doc' => \preg_replace('/^\t+/m', '', $method->getDocComment()),
						'params' => $params,
					];
				}
			}
		}
		return [
			'name' => $shortName,
			'className' => $class,
			'desc' => $desc,
			'methods' => $methods,
			'container' => $is_container,
		];

	}

}
