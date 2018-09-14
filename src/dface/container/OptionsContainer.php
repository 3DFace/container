<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;

class OptionsContainer extends BaseContainer
{

	protected $options;
	/** @var ContainerInterface */
	protected $parent;

	/**
	 * @param string|array $options
	 * @param ContainerInterface|null $parent
	 */
	public function __construct($options, ContainerInterface $parent = null)
	{
		$this->options = \is_array($options) ? $options : $this->parseOpts($options);
		$this->parent = $parent;
	}

	public function hasItem($name) : bool
	{
		if (array_key_exists($name, $this->options)) {
			return true;
		}
		return $this->parent !== null && $this->parent->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws NotFoundException
	 * @throws ContainerException
	 */
	public function getItem($name)
	{
		if (array_key_exists($name, $this->options)) {
			return $this->options[$name];
		}
		if ($this->parent !== null) {
			return $this->parent->get($name);
		}
		throw new NotFoundException("Item '$name' not found");
	}

	protected function parseOpts($str) : array
	{
		$opts = array();
		foreach (preg_split("|\n+|", $str) as $line) {
			if (preg_match("|^\s*([\w\-.]+)\s*=\s*(\S+)\s*$|", $line, $match)) {
				$opts[$match[1]] = $match[2];
			}
		}
		return $opts;
	}

} 
