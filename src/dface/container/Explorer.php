<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class Explorer {

	/** @var PathContainer */
	protected $basePathContainer;
	protected $descriptor;

	function __construct($basePathContainer, $descriptor){
		$this->basePathContainer = $basePathContainer;
		$this->descriptor = $descriptor;
	}

	function getNames($containerName = null){
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		return array_keys($d);
	}

	function getServicesInfo($containerName = null){
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		$result = [];
		foreach($d as $shortName=>$arr){
			list($class, $desc) = $arr;
			$result[] = $this->extractServiceDetails($shortName, $class, $desc);
		}
		return $result;
	}

	function getServiceDescription($containerName, $serviceShortName){
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		if(isset($d[$serviceShortName])){
			return $d[$serviceShortName];
		}else{
			throw new \InvalidArgumentException("Service $serviceShortName not described");
		}
	}

	function getServiceDetails($containerName, $serviceShortName){
		$c = $containerName ? $this->basePathContainer->getItem($containerName) : $this->basePathContainer;
		$d = $this->getDescriptions($c);
		if(isset($d[$serviceShortName])){
			list($class, $desc) = $d[$serviceShortName];
			return $this->extractServiceDetails($serviceShortName, $class, $desc);
		}else{
			throw new \InvalidArgumentException("Service $serviceShortName not described");
		}
	}

	protected function getDescriptions(Container $container){
		return $container->hasItem($this->descriptor) ? $container->getItem($this->descriptor) : [];
	}

	protected function extractServiceDetails($shortName, $class, $desc){
		$reflectionClass = new \ReflectionClass($class);
		$methods = [];
		$is_container = is_a($class, Container::class, true);
		if(!$is_container){
			foreach($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
				if(!$method->isConstructor()){
					$params = array_map(function (\ReflectionParameter $param){
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
