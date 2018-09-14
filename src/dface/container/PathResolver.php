<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

interface PathResolver
{

	public function resolve($path_name) : array;

}
