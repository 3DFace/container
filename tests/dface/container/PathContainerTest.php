<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class PathContainerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @throws ContainerException
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function testLevel0() : void
	{
		$f = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$c = new PathContainer($f);
		$this->assertTrue($c->hasItem('a'));
		$this->assertFalse($c->hasItem('a/a'));
		$this->assertFalse($c->hasItem('b'));
		$this->assertEquals(1, $c->getItem('a'));
	}

	/**
	 * @throws ContainerException
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function testLevel1() : void
	{
		$f = new FactoryContainer([
			'a' => function(){
				return new FactoryContainer([
					'a' => function(){
						return 1;
					}
				]);
			},
		]);
		$c = new PathContainer($f);
		$this->assertTrue($c->hasItem('a'));
		$this->assertTrue($c->hasItem('a/a'));
		$this->assertTrue($c->getItem('a')->hasItem('a'));
		$this->assertFalse($c->hasItem('a/b'));
		$this->assertEquals(1, $c->getItem('a/a'));
		$this->assertEquals(1, $c->getItem('a')->getItem('a'));
	}

}
