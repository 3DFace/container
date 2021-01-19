<?php

namespace dface\container;

class FileContainerFactory
{

	public static function getFor(string $dir_name, $parent) : GenericContainer
	{
		$loader = static function ($dir_name) {
			return static function ($parent) use ($dir_name) {
				return self::getFor($dir_name, $parent);
			};
		};
		$src = new FileDefinitionSource($dir_name, $loader);
		return new GenericContainer($src, $parent);
	}

}
