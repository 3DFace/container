<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class DefaultPathResolverTest extends \PHPUnit_Framework_TestCase {

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
