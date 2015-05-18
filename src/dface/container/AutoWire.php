<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class AutoWire {
	
	static function construct(Container $container, $class, $explicit_arguments = []){
		$reflection_class = new \ReflectionClass($class);
		$constructor = $reflection_class->getConstructor();
		$args = [];
		foreach($constructor->getParameters() as $i=>$parameter){
			$parameter_name = $parameter->getName();
			$parameter_class = $parameter->getClass();
			if($parameter_class){
				$parameter_class_name = $parameter_class->getShortName();
				$parameter_class_name = strtolower($parameter_class_name[0]).substr($parameter_class_name, 1);
			}else{
				$parameter_class_name = null;
			}
			if($parameter_class_name && array_key_exists($parameter_class_name, $explicit_arguments)){
				$args[$i] = $explicit_arguments[$parameter_class_name];
			}elseif(array_key_exists($parameter_name, $explicit_arguments)){
				$args[$i] = $explicit_arguments[$parameter_name];
			}elseif($parameter_class_name && ($owner = $container->hasItem($parameter_class_name))){
				$args[$i] = $owner->getItem($parameter_class_name);
			}elseif($owner = $container->hasItem($parameter_name)){
				$args[$i] = $owner->getItem($parameter_name);
			}elseif($parameter->isDefaultValueAvailable()){
				$args[$i] = $parameter->getDefaultValue();
			}else{
				throw new \Exception("no argument supplied for '$parameter_name' while constructing '$class'");
			}
		}
		return $reflection_class->newInstanceArgs($args);
	}

	static function setProperties(Container $container, $object, $exclude = []){
		foreach(get_class_methods($object) as $name){
			if(strlen($name) > 3 && substr($name, 0, 3) === 'set'){
				$property = strtolower($name[3]).substr($name, 4);
				if(!in_array($property, $exclude)){
					if($owner = $container->hasItem($property)){
						$item = $owner->getItem($property);
						$object->$name($item);
					}
				}
			}
		}
		return $object;
	}

}
