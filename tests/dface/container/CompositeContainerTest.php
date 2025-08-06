<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class CompositeContainerTest  extends TestCase {

	public function testA() : void
	{
		$p = new AContainer([
			'x' => static function(){
				return 0;
			},
		]);

		$c1 = new AContainer([
			'a' => static function(){
				return 1;
			},
			'b' => static function(){
				return 1;
			},
			'inner' => static function(){
				return new AContainer([
					'a' => static function(){
						return 0;
					}
				]);
			}
		]);

		$c2 = new AContainer([
			'a' => static function(){
				return 2;
			},
			'c' => static function(){
				return 2;
			},
		]);

		$c3 = new AContainer([
			'a' => static function(){
				return 3;
			},
			'd' => static function(){
				return 3;
			},
		]);

		$comp = new CompositeContainer([$c1, $c2, $c3], $p);

		self::assertTrue($comp->has('x'));
		self::assertTrue($comp->has('a'));
		self::assertTrue($comp->has('b'));
		self::assertTrue($comp->has('c'));
		self::assertTrue($comp->has('d'));
		self::assertFalse($comp->has('e'));
		self::assertTrue($comp->has('inner'));
		self::assertTrue($comp->has('inner/a'));
		self::assertEquals(0, $comp->get('x'));
		self::assertEquals(1, $comp->get('a'));
		self::assertEquals(1, $comp->get('b'));
		self::assertEquals(2, $comp->get('c'));
		self::assertEquals(3, $comp->get('d'));
		self::assertEquals(0, $comp->get('inner/a'));
	}

}
