<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class FileContainerFactory
{

	public static function create(string $dir_name, ?ContainerInterface $parent = null) : GenericContainer
	{
		$loader = static function ($dir_name) {
			return static function ($parent) use ($dir_name) {
				return self::create($dir_name, $parent);
			};
		};
		$src = new FileDefinitionSource($dir_name, $loader);
		return new GenericContainer($src, $parent);
	}

}
