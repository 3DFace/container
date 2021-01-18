<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class AContainer extends GenericContainer
{

	public function __construct(array $definitions_arr = [], ContainerInterface $parent = null)
	{
		$definitions = new ArrayDefinitionSource($definitions_arr);
		parent::__construct($definitions, $parent);
	}

}
