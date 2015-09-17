<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class DefaultPathResolver implements PathResolver {

	/** @var string */
	protected $path_separator;
	/** @var int */
	protected $path_separator_length;

	/**
	 * DefaultPathResolver constructor.
	 * @param string $path_separator
	 */
	public function __construct($path_separator = '/'){
		$this->path_separator = $path_separator;
		$this->path_separator_length = strlen($path_separator);
	}

	function resolve($path_name){
		$pos = strpos($path_name, $this->path_separator);
		if($pos !== false){
			$container_name = substr($path_name, 0, $pos);
			$inner_item_name =  substr($path_name, $pos + $this->path_separator_length);
			return [$container_name, $inner_item_name];
		}else{
			return [null, $path_name];
		}
	}

}
