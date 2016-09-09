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
		$this->assertTrue($c->hasItem('a'));
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
		$this->assertTrue($c->hasItem('a'));
		$this->assertTrue($c->hasItem('a/a'));
		$this->assertTrue($c->getItem('a')->hasItem('a'));
		$this->assertFalse($c->hasItem('a/b'));
		$this->assertEquals(1, $c->getItem('a/a'));
		$this->assertEquals(1, $c->getItem('a')->getItem('a'));
	}

}
