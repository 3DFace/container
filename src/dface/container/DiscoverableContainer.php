<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

interface DiscoverableContainer extends ContainerInterface
{

	public function getNames() : iterable;

}
