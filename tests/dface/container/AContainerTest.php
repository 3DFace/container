<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

class AContainerTest extends \PHPUnit_Framework_TestCase {

	public function testA() : void
	{
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

	public function testLocalOverweight() : void
	{
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
