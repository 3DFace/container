<?php

namespace dface\container;

use PHPUnit\Framework\TestCase;

class FileDefinitionSourceTest extends TestCase
{

	private FileDefinitionSource $src;

	protected function setUp() : void
	{
		parent::setUp();
		$this->src = new FileDefinitionSource(__DIR__.'/../data');
	}

	public function testNames() : void
	{
		self::assertEquals(['Bar', 'Foo'], Utils::iterable_to_array($this->src->getNames()));
	}

	public function testFoo() : void
	{
		$fn = $this->src->getDefinition('Foo');
		$foo = $fn();
		self::assertEquals('Foo', $foo);
	}

	public function testBar() : void
	{
		$c = new GenericContainer($this->src);
		$bar = $c['Bar'];
		self::assertEquals('Bar for Foo', $bar);
	}

}
