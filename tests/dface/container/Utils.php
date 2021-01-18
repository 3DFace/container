<?php

namespace dface\container;

class Utils
{

	public static function iterable_to_array(iterable $it, bool $use_keys = true) : array
	{
		if (\is_array($it)) {
			return $use_keys ? $it : \array_values($it);
		}
		/** @var \Traversable $it */
		return \iterator_to_array($it, $use_keys);
	}

}
