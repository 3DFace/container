<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class Explorer
{

	/** @var BaseContainer */
	private $basePathContainer;
	private $descriptor;

	public function __construct($basePathContainer, $descriptor)
	{
		$this->basePathContainer = $basePathContainer;
		$this->descriptor = $descriptor;
	}

	/**
	 * @param null $containerName
	 * @return array
	 */
	public function getNames($containerName = null) : array
	{
		$c = $containerName ? $this->basePathContainer[$containerName] : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		return array_keys($d);
	}

	/**
	 * @param null $containerName
	 * @return array
	 */
	public function getServicesInfo($containerName = null) : array
	{
		$c = $containerName ? $this->basePathContainer[$containerName] : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		$result = [];
		foreach ($d as $shortName => [$class, $desc]) {
			$result[] = $this->extractServiceDetails($shortName, $class, $desc);
		}
		return $result;
	}

	/**
	 * @param $containerName
	 * @param $serviceShortName
	 * @return mixed
	 */
	public function getServiceDescription($containerName, $serviceShortName)
	{
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		if (isset($d[$serviceShortName])) {
			return $d[$serviceShortName];
		}
		throw new \InvalidArgumentException("Service $serviceShortName not described");
	}

	/**
	 * @param $containerName
	 * @param $serviceShortName
	 * @return array|null
	 */
	public function getServiceDetails($containerName, $serviceShortName) : ?array
	{
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		if (isset($d[$serviceShortName])) {
			[$class, $desc] = $d[$serviceShortName];
			return $this->extractServiceDetails($serviceShortName, $class, $desc);
		}
		throw new \InvalidArgumentException("Service $serviceShortName not described");
	}

	private function getDescriptions(Container $container)
	{
		return $container->hasItem($this->descriptor) ? $container->getItem($this->descriptor) : [];
	}

	private function extractServiceDetails($shortName, $class, $desc) : array
	{
		try{
			$reflectionClass = new \ReflectionClass($class);
		}catch (\ReflectionException $e){
			throw new \RuntimeException($e->getMessage(), 0, $e);
		}
		$methods = [];
		$is_container = is_a($class, Container::class, true);
		if (!$is_container) {
			foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
				if (!$method->isConstructor()) {
					$params = \array_map(static function (\ReflectionParameter $param) {
						return $param->getName();
					}, $method->getParameters());

					$methods[] = [
						'name' => $method->getName(),
						'doc' => preg_replace('/^\t+/m', '', $method->getDocComment()),
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
