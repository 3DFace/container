<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class FactoryContainerTest extends TestCase
{

	public function testPlainValue() : void
	{
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'a' => 1,
		]));
		self::assertEquals(1, $c->get('a'));
	}

	public function testNewInstances() : void
	{
		$i = 0;
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]));
		self::assertEquals(0, $c->get('a'));
		self::assertEquals(1, $c->get('a'));
		self::assertEquals(2, $c->get('a'));
	}

	public function testHasItem() : void
	{
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function () {
				return 1;
			},
		]));
		self::assertTrue($c->has('a'));
		self::assertFalse($c->has('b'));
	}

	/**
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function testGetItem() : void
	{
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function () {
				return 1;
			},
		]));
		self::assertEquals(1, $c->get('a'));
		$this->expectException(NotFoundException::class);
		$c->get('b');
	}

	/**
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function testCyclicDependency() : void
	{
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		]));
		$this->expectException(ContainerException::class);
		$c->get('a');
	}

	public function testLocalLookup() : void
	{
		$i = 1;
		$c = new FactoryContainer(new ArrayDefinitionSource([
			'b' => static function () use (&$i) {
				return $i++;
			},
			'a' => static function (ContainerInterface $c) {
				return $c->get('b');
			},
		]));
		self::assertEquals(1, $c->get('a'));
		self::assertEquals(2, $c->get('a'));
	}

	public function testExternalLookup() : void
	{
		$i = 1;
		$c1 = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]));
		$c2 = new FactoryContainer(new ArrayDefinitionSource([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		]), $c1);
		self::assertEquals(1, $c2->get('a'));
		self::assertEquals(2, $c2->get('a'));
	}

}
