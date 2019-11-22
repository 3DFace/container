<?php

namespace dface\container;

class ContainerLinkTest extends \PHPUnit_Framework_TestCase
{
	public function testValidLink() : void
	{
		$c = new AContainer([
			'a' => static function(){
				return 1;
			},
			'b' => static function(){
				return 2;
			},
		]);
		$link = new ContainerLink($c, [
			'q' => 'a',
			'b' => 'b',
		]);
		$this->assertEquals(1, $link['q']);
		$this->assertEquals(2, $link['b']);
	}

	public function testInvalidLink1() : void
	{
		$c = new AContainer([]);
		$link = new ContainerLink($c, [
			'b' => 'b',
		]);
		$this->setExpectedException(NotFoundException::class);
		$link->get('b');
	}

	public function testInvalidLink2() : void
	{
		$c = new AContainer([]);
		$link = new ContainerLink($c, []);
		$this->setExpectedException(NotFoundException::class);
		$link->get('b');
	}
}
