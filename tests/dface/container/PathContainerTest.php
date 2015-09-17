<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class PathContainerTest extends \PHPUnit_Framework_TestCase {

	function testLevel0(){
		$f = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$c = new PathContainer($f);
		$this->assertEquals($c, $c->hasItem('a'));
		$this->assertFalse($c->hasItem('a/a'));
		$this->assertFalse($c->hasItem('b'));
		$this->assertEquals(1, $c->getItem('a'));
	}

	function testLevel1(){
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
		$this->assertEquals($c, $c->hasItem('a'));
		$this->assertEquals($c, $c->hasItem('a/a'));
		$this->assertInstanceOf(FactoryContainer::class, $c->getItem('a')->hasItem('a'));
		$this->assertFalse($c->hasItem('a/b'));
		$this->assertEquals(1, $c->getItem('a/a'));
	}

}
