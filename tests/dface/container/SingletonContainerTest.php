<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class SingletonContainerTest extends TestCase
{

	public function testSingleInstance() : void
	{
		$i = 0;
		$f = new FactoryContainer([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]);
		$c = new SingletonContainer($f);
		self::assertEquals(0, $c->get('a'));
		self::assertEquals(0, $c->get('a'));
	}

	public function testHasItem() : void
	{
		$f = new FactoryContainer([
			'a' => static function () {
				throw new ContainerException("Must not be called on 'hasItem'");
			},
		]);
		$c = new SingletonContainer($f);
		self::assertTrue($c->has('a'));
		self::assertFalse($c->has('b'));
	}

	public function testGetItem() : void
	{
		$f = new FactoryContainer([
			'a' => static function () {
				return 1;
			},
		]);
		$c = new SingletonContainer($f);
		self::assertEquals(1, $c->get('a'));
		$this->expectException(NotFoundException::class);
		$c->get('b');
	}

	public function testCyclicDependency() : void
	{
		$f = new FactoryContainer([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		]);
		$c = new SingletonContainer($f);
		$this->expectException(ContainerException::class);
		$c->get('a');
	}

	public function testLocalLookup() : void
	{
		$i = 1;
		$f = new FactoryContainer([
			'b' => static function () use (&$i) {
				return $i++;
			},
			'a' => static function (ContainerInterface $c) {
				return $c->get('b');
			},
		]);
		$c = new SingletonContainer($f);
		self::assertEquals(1, $c->get('a'));
		self::assertEquals(1, $c->get('a'));
	}

	public function testExternalLookup() : void
	{
		$i = 1;
		$f1 = new FactoryContainer([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]);
		$c1 = new SingletonContainer($f1);
		$f2 = new FactoryContainer([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		], $c1);
		$c2 = new SingletonContainer($f2);
		self::assertEquals(1, $c2->get('a'));
		self::assertEquals(1, $c2->get('a'));
	}

}
