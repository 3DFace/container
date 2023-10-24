<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class FactoryContainerTest extends TestCase {

	function testPlainValue(){
		$c = new FactoryContainer([
			'a' => 1,
		]);
		$this->assertEquals(1, $c['a']);
	}

	function testNewInstances(){
		$i = 0;
		$c = new FactoryContainer([
			'a' => function() use (&$i){
				return $i++;
			},
		]);
		$this->assertEquals(0, $c['a']);
		$this->assertEquals(1, $c['a']);
		$this->assertEquals(2, $c['a']);
	}

	function testHasItem(){
		$c = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$this->assertTrue($c->hasItem('a'));
		$this->assertFalse($c->hasItem('b'));
	}

	function testGetItem(){
		$c = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$this->assertEquals(1, $c->getItem('a'));
		$this->expectException(NotFoundException::class);
		$c->getItem('b');
	}

	function testCyclicDependency(){
		$c = new FactoryContainer([
			'a' => function($c){
				return $c['a'];
			},
		]);
		$this->expectException(ContainerException::class);
		$c->getItem('a');
	}

	function testLocalLookup(){
		$i = 1;
		$c = new FactoryContainer([
			'b'=>function() use (&$i){
				return $i++;
			},
			'a' => function($c){
				return $c['b'];
			},
		]);
		$this->assertEquals(1, $c['a']);
		$this->assertEquals(2, $c['a']);
	}

	function testExternalLookup(){
		$i = 1;
		$c1 = new FactoryContainer([
			'a' => function() use (&$i){
				return $i++;
			},
		]);
		$c2 = new FactoryContainer([
			'a' => function($c){
				return $c['a'];
			},
		], $c1);
		$this->assertEquals(1, $c2['a']);
		$this->assertEquals(2, $c2['a']);
	}

}
