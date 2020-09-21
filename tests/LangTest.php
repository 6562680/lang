<?php

namespace Gzhegow\Lang\Tests;

use Gzhegow\Di\Di;
use Gzhegow\Lang\Di\LangProvider;
use Gzhegow\Lang\Domain\LangService;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundException;

/**
 * Class LangTest
 */
class LangTest extends AbstractTestCase
{
	/**
	 * @return void
	 * @throws WordNotFoundException
	 */
	public function test1_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');
		$result = $lang->get('@main.hello.world');

		self::assertEquals('Hello World', $result);
	}

	/**
	 * @return void
	 */
	public function test2_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');
		$result = $lang->getOrNull('@main.hello.world2');

		self::assertEquals(null, $result);
	}

	/**
	 * @return void
	 */
	public function test3_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');
		$result = $lang->getOrWord('@main.hello.world2', []);

		self::assertEquals('@main.hello.world2', $result);
	}

	/**
	 * @return void
	 */
	public function test4_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');
		$result = $lang->getOrDefault('@main.hello.world2', [], 'None');

		self::assertEquals('None', $result);
	}

	/**
	 * @return void
	 */
	public function test5_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');

		$words = $lang->collect([ '@main.hello.world' ]);

		self::assertEquals('Hello World', $words[ 'main.hello.world' ]);
	}


	/**
	 * @return void
	 * @throws WordNotFoundException
	 */
	public function testException1_()
	{
		$this->expectException(WordNotFoundException::class);

		$lang = $this->fixtureLang();

		$lang->get('@main.hello.world');
	}


	/**
	 * @return LangService
	 */
	protected function fixtureLang() : LangService
	{
		return Di::makeOrFail(LangService::class);
	}


	/**
	 * @return void
	 */
	protected static function boot() : void
	{
		$di = Di::getInstance();
		$di->registerProvider(LangProvider::class);
	}
}