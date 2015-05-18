<?php

namespace dface\container;

interface Container {

	/**
	 * @param $name string
	 * @return Container | null
	 */
	function hasItem($name);

	/**
	 * @param $name
	 * @return mixed
	 */
	function getItem($name);

}
