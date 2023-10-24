<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class SingletonContainerTest extends TestCase {

	function testSingleInstance(){
		$i = 0;
		$f = new FactoryContainer([
			'a' => function() use (&$i){
				return $i++;
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertEquals(0, $c['a']);
		$this->assertEquals(0, $c['a']);
	}

	function testHasItem(){
		$f = new FactoryContainer([
			'a' => function(){
				throw new ContainerException("Must not be called on 'hasItem'");
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertTrue($c->hasItem('a'));
		$this->assertFalse($c->hasItem('b'));
	}

	function testGetItem(){
		$f = new FactoryContainer([
			'a' => function(){
				return 1;
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertEquals(1, $c->getItem('a'));
		$this->expectException(NotFoundException::class);
		$c->getItem('b');
	}

	function testCyclicDependency(){
		$f = new FactoryContainer([
			'a' => function($c){
				return $c['a'];
			},
		]);
		$c = new SingletonContainer($f);
		$this->expectException(ContainerException::class);
		$c->getItem('a');
	}

	function testLocalLookup(){
		$i = 1;
		$f = new FactoryContainer([
			'b'=>function() use (&$i){
				return $i++;
			},
			'a' => function($c){
				return $c['b'];
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertEquals(1, $c['a']);
		$this->assertEquals(1, $c['a']);
	}

	function testExternalLookup(){
		$i = 1;
		$f1 = new FactoryContainer([
			'a' => function() use (&$i){
				return $i++;
			},
		]);
		$c1 = new SingletonContainer($f1);
		$f2 = new FactoryContainer([
			'a' => function($c){
				return $c['a'];
			},
		], $c1);
		$c2 = new SingletonContainer($f2);
		$this->assertEquals(1, $c2['a']);
		$this->assertEquals(1, $c2['a']);
	}

}
