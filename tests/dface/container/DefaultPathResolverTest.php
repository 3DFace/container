<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class DefaultPathResolverTest extends \PHPUnit_Framework_TestCase {

	public function testOneChar() : void
	{
		$pr = new DefaultPathResolver('/');
		[$container_name, $item_name] = $pr->resolve('asd/zxc');
		$this->assertEquals('asd', $container_name);
		$this->assertEquals('zxc', $item_name);
	}

	public function testTwoChar() : void
	{
		$pr = new DefaultPathResolver('!@');
		[$container_name, $item_name] = $pr->resolve('asd!@zxc');
		$this->assertEquals('asd', $container_name);
		$this->assertEquals('zxc', $item_name);
	}

	public function testNoPath() : void
	{
		$pr = new DefaultPathResolver('/');
		[$container_name, $item_name] = $pr->resolve('asd');
		$this->assertNull($container_name);
		$this->assertEquals('asd', $item_name);
	}

}
