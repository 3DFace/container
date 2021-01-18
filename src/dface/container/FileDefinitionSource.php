<?php

namespace dface\container;

class FileDefinitionSource implements DefinitionSource
{

	private string $dir;
	private array $resolved = [];

	public function __construct(string $dir)
	{
		$this->dir = \realpath($dir);
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
		$filename = $this->resolve($name);
		if ($filename === null) {
			throw new NotFoundException("'$name' not found in '$this->dir'");
		}
		/** @noinspection PhpIncludeInspection */
		return include $filename;
	}

	public function getNames() : iterable
	{
		$glob_files = \glob($this->dir.'/*.php');
		foreach ($glob_files as $filename) {
			yield \substr(\basename($filename), 0, -4);
		}
	}

	/**
	 * @param $id
	 * @return string|null
	 * @throws ContainerException
	 */
	private function resolve($id) : ?string
	{
		if (!\array_key_exists($id, $this->resolved)) {
			$file_name = null;
			$check_name = $this->dir.'/'.$id.'.php';
			if (\file_exists($check_name)) {
				$file_name = \realpath($check_name);
				if(\strpos($file_name, $this->dir) !== 0){
					throw new ContainerException("'$id' is out of '$this->dir'");
				}
			}
			$this->resolved[$id] = $file_name;
		}
		return $this->resolved[$id];
	}

}
