<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class AContainerTest extends TestCase {

	function testA(){
		$c = new AContainer([
			'a' => function(){
				return 1;
			},
			'b' => function($c){
				return new AContainer([
					'c' => function($c){
						return $c['a'];
					},
				], $c);
			}
		]);
		$this->assertEquals(1, $c['b/c']);
		$this->assertEquals(1, $c('a'));
	}

	function testLocalOverweight(){
		$c = new AContainer([
			'a' => function(){
				return 1;
			},
			'b' => function($c){
				return new AContainer([
					'a' => function(){
						return 2;
					},
					'c' => function($c){
						return $c['a'];
					},
				], $c);
			}
		]);
		$this->assertEquals(2, $c['b/c']);
	}



}
