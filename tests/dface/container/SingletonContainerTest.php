<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Psr\Container\ContainerInterface;

class SingletonContainerTest extends \PHPUnit_Framework_TestCase
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
		$this->assertEquals(0, $c->get('a'));
		$this->assertEquals(0, $c->get('a'));
	}

	public function testHasItem() : void
	{
		$f = new FactoryContainer([
			'a' => static function () {
				throw new ContainerException("Must not be called on 'hasItem'");
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertTrue($c->has('a'));
		$this->assertFalse($c->has('b'));
	}

	public function testGetItem() : void
	{
		$f = new FactoryContainer([
			'a' => static function () {
				return 1;
			},
		]);
		$c = new SingletonContainer($f);
		$this->assertEquals(1, $c->get('a'));
		$this->setExpectedException(NotFoundException::class);
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
		$this->setExpectedException(ContainerException::class);
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
		$this->assertEquals(1, $c->get('a'));
		$this->assertEquals(1, $c->get('a'));
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
		$this->assertEquals(1, $c2->get('a'));
		$this->assertEquals(1, $c2->get('a'));
	}

}
