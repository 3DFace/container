<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class FileContainerFactoryTest extends TestCase
{

	private GenericContainer $container;

	protected function setUp() : void
	{
		parent::setUp();
		$this->container = FileContainerFactory::getFor(__DIR__.'/../data', null);
	}

	public function testNames() : void
	{
		self::assertEquals(['Bar', 'Foo', 'Baz'], Utils::iterable_to_array($this->container->getNames()));
	}

	public function testFoo() : void
	{
		$foo = $this->container['Foo'];
		self::assertEquals('Foo', $foo);
	}

	public function testBar() : void
	{
		$bar = $this->container['Bar'];
		self::assertEquals('Bar for Foo', $bar);
	}

	public function testBazMoo() : void
	{
		$moo = $this->container['Baz/Moo'];
		self::assertEquals('Moo for Bar for Foo', $moo);
	}

	public function testBazZooBoo() : void
	{
		$boo = $this->container['Baz/Zoo/Boo'];
		self::assertEquals('Boo for Moo for Bar for Foo', $boo);
	}

	public function testBazGoo() : void
	{
		$goo = $this->container['Baz/Goo'];
		self::assertEquals('Goo for Boo for Moo for Bar for Foo and Foo', $goo);
	}

}
