<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class FactoryContainerTest extends \PHPUnit_Framework_TestCase {

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
		$this->assertEquals($c, $c->hasItem('a'));
		$this->assertFalse($c->hasItem('b'));
	}

	function testGetItem(){
		$c = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$this->assertEquals(1, $c->getItem('a'));
		$this->setExpectedException(ContainerException::class);
		$c->getItem('b');
	}

	function testCyclicDependency(){
		$c = new FactoryContainer([
			'a' => function($c){
				return $c['a'];
			},
		]);
		$this->setExpectedException(ContainerException::class);
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
