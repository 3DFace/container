<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class AutoWire
{

	/**
	 * @param ContainerInterface $container
	 * @param $class
	 * @param array $explicit_arguments
	 * @return object
	 * @throws \ReflectionException
	 * @throws ContainerException
	 */
	public static function construct(ContainerInterface $container, $class, array $explicit_arguments = []) : object
	{
		$reflection_class = new \ReflectionClass($class);
		$constructor = $reflection_class->getConstructor();
		if($constructor === null){
			throw new ContainerException("$class do not have a constructor defined");
		}
		$args = [];
		foreach ($constructor->getParameters() as $i => $parameter) {
			$parameter_name = $parameter->getName();
			$parameter_class = $parameter->getClass();
			if ($parameter_class) {
				$parameter_class_name = $parameter_class->getShortName();
				$parameter_class_name = \strtolower($parameter_class_name[0]).\substr($parameter_class_name, 1);
			}else {
				$parameter_class_name = null;
			}
			if ($parameter_class_name && \array_key_exists($parameter_class_name, $explicit_arguments)) {
				$args[$i] = $explicit_arguments[$parameter_class_name];
			}elseif (\array_key_exists($parameter_name, $explicit_arguments)) {
				$args[$i] = $explicit_arguments[$parameter_name];
			}elseif ($parameter_class_name && $container->has($parameter_class_name)) {
				$args[$i] = $container->get($parameter_class_name);
			}elseif ($container->has($parameter_name)) {
				$args[$i] = $container->get($parameter_name);
			}elseif ($parameter->isDefaultValueAvailable()) {
				$args[$i] = $parameter->getDefaultValue();
			}else {
				throw new \RuntimeException("no argument supplied for '$parameter_name' while constructing '$class'");
			}
		}
		return $reflection_class->newInstanceArgs($args);
	}

	public static function setProperties(ContainerInterface $container, $object, array $exclude = [])
	{
		foreach (\get_class_methods($object) as $name) {
			if (\strlen($name) > 3 && \strpos($name, 'set') === 0) {
				$property = \strtolower($name[3]).\substr($name, 4);
				if (!\in_array($property, $exclude, true) && $container->has($property)) {
					$item = $container->get($property);
					$object->$name($item);
				}
			}
		}
		return $object;
	}

}
