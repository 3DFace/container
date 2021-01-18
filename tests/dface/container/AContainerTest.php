<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class AContainerTest extends TestCase {

	public function testA() : void
	{
		$c = new AContainer([
			'a' => static function(){
				return 1;
			},
			'b' => static function($c){
				return new AContainer([
					'c' => static function($c){
						return $c['a'];
					},
				], $c);
			}
		]);
		self::assertEquals(1, $c['b/c']);
		self::assertEquals(1, $c('a'));
		self::assertEquals(['a', 'b'], $c->getNames());
	}

	public function testLocalOverweight() : void
	{
		$c = new AContainer([
			'a' => static function(){
				return 1;
			},
			'b' => static function($c){
				return new AContainer([
					'a' => static function(){
						return 2;
					},
					'c' => static function($c){
						return $c['a'];
					},
				], $c);
			}
		]);
		self::assertEquals(2, $c['b']['c']);
	}

	public function testPathUse() : void
	{
		$c = new AContainer([
			'a' => static function(){
				return new AContainer([
					'b' => static function(){
						return new AContainer([
							'c' => static function(){
								return 3;
							},
						]);
					},
				]);
			},
			'b' => static function($c){
				return new AContainer([
					'c' => static function(){
						return 2;
					},
					'd' => static function($c){
						return $c['b/c'] + $c['a/b/c'];
					},
				], $c);
			}
		]);
		self::assertEquals(5, $c['b']['d']);
	}

	public function testHierarchy() : void
	{
		$c = new AContainer([
			'a' => static function($c){
				return new AContainer([
					'b' => static function($c){
						return new AContainer([
							'c' => static function($c){
								return $c['b/d'];
							},
							'd' => static function(){
								return 1;
							},
						], $c);
					},
				], $c);
			}
		]);
		self::assertEquals(1, $c['a/b/c']);
	}

}
