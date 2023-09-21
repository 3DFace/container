<?php

namespace dface\container;

class FileDefinitionSource implements DefinitionSource
{

	private string $dir;
	/** @var callable|null */
	private $dir_loader;
	private array $resolved = [];

	public function __construct(string $dir, ?callable $dir_loader = null)
	{
		$this->dir = \realpath($dir);
		$this->dir_loader = $dir_loader;
	}

	/**
	 * @param string $name
	 * @return bool
	 * @throws ContainerException
	 */
	public function hasDefinition(string $name) : bool
	{
		return $this->resolve($name) !== null;
	}

	/**
	 * @param string $name
	 * @return callable
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function getDefinition(string $name) : callable
	{
		$loader = $this->resolve($name);
		if ($loader === null) {
			throw new NotFoundException("'$name' not found in '$this->dir'");
		}
		return $loader();
	}

	public function getNames() : iterable
	{
		$glob_files = \glob($this->dir.'/*.php');
		$file_defs = [];
		foreach ($glob_files as $file_name) {
			$def_name = \substr(\basename($file_name), 0, -4);
			$file_defs[$def_name] = true;
			yield $def_name;
		}
		if($this->dir_loader){
			$glob_dirs = \glob($this->dir.'/*', GLOB_ONLYDIR);
			foreach ($glob_dirs as $dir_name){
				$def_name = \basename($dir_name);
				if(!isset($file_defs[$def_name])){
					yield $def_name;
				}
			}
		}
	}

	/**
	 * @param $name
	 * @return callable|null
	 * @throws ContainerException
	 */
	private function resolve($name) : ?callable
	{
		if (!\array_key_exists($name, $this->resolved)) {
			$result = $this->checkFile($name);
			if(!$result && $this->dir_loader){
				$result = $this->checkDir($name);
			}
			$this->resolved[$name] = $result;
			return $result;
		}
		return $this->resolved[$name];
	}

	/**
	 * @param $name
	 * @return \Closure|null
	 * @throws ContainerException
	 */
	private function checkFile($name) : ?callable
	{
		$this->preventNamingAbusing($name);
		$file_name = $this->dir.'/'.$name.'.php';
		if (\is_file($file_name)) {
			return static function() use ($file_name){
				return include $file_name;
			};
		}
		return null;
	}

	/**
	 * @param $name
	 * @return \Closure|null
	 * @throws ContainerException
	 */
	private function checkDir($name) : ?callable
	{
		$this->preventNamingAbusing($name);
		$dir_name = $this->dir.'/'.$name;
		if (\is_dir($dir_name)) {
			return function() use ($dir_name){
				return ($this->dir_loader)($dir_name);
			};
		}
		return null;
	}

	/**
	 * @param string $name
	 * @throws ContainerException
	 */
	private function preventNamingAbusing(string $name) : void {
		if(\basename($name) !== $name){
			throw new ContainerException("'$name' violates naming restriction");
		}
	}

}
