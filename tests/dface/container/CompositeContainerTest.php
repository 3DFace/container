<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class CompositeContainerTest  extends \PHPUnit_Framework_TestCase {

	function testA(){
		$p = new AContainer([
			'x' => function(){
				return 0;
			},
		]);

		$c1 = new AContainer([
			'a' => function(){
				return 1;
			},
			'b' => function(){
				return 1;
			},
		]);

		$c2 = new AContainer([
			'a' => function(){
				return 2;
			},
			'c' => function(){
				return 2;
			},
		]);

		$c3 = new AContainer([
			'a' => function(){
				return 3;
			},
			'd' => function(){
				return 3;
			},
		]);

		$comp = new CompositeContainer([$c1, $c2, $c3], $p);

		$this->assertTrue($comp->has('x'));
		$this->assertTrue($comp->has('a'));
		$this->assertTrue($comp->has('b'));
		$this->assertTrue($comp->has('c'));
		$this->assertTrue($comp->has('d'));
		$this->assertFalse($comp->has('e'));
		$this->assertEquals(0, $comp->get('x'));
		$this->assertEquals(1, $comp->get('a'));
		$this->assertEquals(1, $comp->get('b'));
		$this->assertEquals(2, $comp->get('c'));
		$this->assertEquals(3, $comp->get('d'));
	}

}
