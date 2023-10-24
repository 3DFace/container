<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class DefaultPathResolverTest extends TestCase {

	function testOneChar(){
		$pr = new DefaultPathResolver('/');
		list($container_name, $item_name) = $pr->resolve('asd/zxc');
		$this->assertEquals('asd', $container_name);
		$this->assertEquals('zxc', $item_name);
	}

	function testTwoChar(){
		$pr = new DefaultPathResolver('!@');
		list($container_name, $item_name) = $pr->resolve('asd!@zxc');
		$this->assertEquals('asd', $container_name);
		$this->assertEquals('zxc', $item_name);
	}

	function testNoPath(){
		$pr = new DefaultPathResolver('/');
		list($container_name, $item_name) = $pr->resolve('asd');
		$this->assertNull($container_name);
		$this->assertEquals('asd', $item_name);
	}

}
