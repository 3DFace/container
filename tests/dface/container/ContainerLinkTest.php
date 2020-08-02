<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class ContainerLinkTest extends TestCase
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
		self::assertEquals(1, $link['q']);
		self::assertEquals(2, $link['b']);
	}

	public function testInvalidLink1() : void
	{
		$c = new AContainer([]);
		$link = new ContainerLink($c, [
			'b' => 'b',
		]);
		$this->expectException(NotFoundException::class);
		$link->get('b');
	}

	public function testInvalidLink2() : void
	{
		$c = new AContainer([]);
		$link = new ContainerLink($c, []);
		$this->expectException(NotFoundException::class);
		$link->get('b');
	}
}
