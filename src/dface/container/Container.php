<?php

namespace dface\container;

interface Container
{

	/**
	 * @param $name string
	 * @return bool
	 */
	public function hasItem($name) : bool;

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getItem($name);

}
