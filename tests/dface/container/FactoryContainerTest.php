<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class FactoryContainerTest extends \PHPUnit_Framework_TestCase
{

	public function testPlainValue() : void
	{
		$c = new FactoryContainer([
			'a' => 1,
		]);
		$this->assertEquals(1, $c->get('a'));
	}

	public function testNewInstances() : void
	{
		$i = 0;
		$c = new FactoryContainer([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]);
		$this->assertEquals(0, $c->get('a'));
		$this->assertEquals(1, $c->get('a'));
		$this->assertEquals(2, $c->get('a'));
	}

	public function testHasItem() : void
	{
		$c = new FactoryContainer([
			'a' => static function () {
				return 1;
			},
		]);
		$this->assertTrue($c->has('a'));
		$this->assertFalse($c->has('b'));
	}

	/**
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function testGetItem() : void
	{
		$c = new FactoryContainer([
			'a' => static function () {
				return 1;
			},
		]);
		$this->assertEquals(1, $c->get('a'));
		$this->setExpectedException(NotFoundException::class);
		$c->get('b');
	}

	/**
	 * @throws ContainerException
	 * @throws NotFoundException
	 */
	public function testCyclicDependency() : void
	{
		$c = new FactoryContainer([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		]);
		$this->setExpectedException(ContainerException::class);
		$c->get('a');
	}

	public function testLocalLookup() : void
	{
		$i = 1;
		$c = new FactoryContainer([
			'b' => static function () use (&$i) {
				return $i++;
			},
			'a' => static function (ContainerInterface $c) {
				return $c->get('b');
			},
		]);
		$this->assertEquals(1, $c->get('a'));
		$this->assertEquals(2, $c->get('a'));
	}

	public function testExternalLookup() : void
	{
		$i = 1;
		$c1 = new FactoryContainer([
			'a' => static function () use (&$i) {
				return $i++;
			},
		]);
		$c2 = new FactoryContainer([
			'a' => static function (ContainerInterface $c) {
				return $c->get('a');
			},
		], $c1);
		$this->assertEquals(1, $c2->get('a'));
		$this->assertEquals(2, $c2->get('a'));
	}

}
