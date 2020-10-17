<?php

namespace Gzhegow\Lang\Tests;

use Gzhegow\Di\Di;
use Gzhegow\Lang\Di\LangProvider;
use Gzhegow\Lang\Domain\LangService;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Logic\InvalidArgumentException;

/**
 * Class LangTest
 */
class LangTest extends AbstractTestCase
{
	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function test1_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');

		self::assertEquals('Hello, World', $lang->get('@main.title.hello-world'));
		self::assertEquals(null, $lang->getOrNull('@main.title.hello-world2'));
		self::assertEquals('@main.title.hello-world2', $lang->getOrWord('@main.title.hello-world2', []));
		self::assertEquals('None', $lang->getOrDefault('@main.title.hello-world2', [], 'None'));
	}

	/**
	 * @return void
	 */
	public function test2_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');

		$words = $lang->collect([ '@main.title.hello-world' ]);

		self::assertEquals('Hello, World', $words[ 'main.title.hello-world' ]);
	}

	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function test3_()
	{
		$lang = $this->fixtureLang();

		$lang->load('main');

		$lang->setLocale('en');

		self::assertEquals('worlds', $lang->choice('@main.label.world', 0));
		self::assertEquals('worlds', $lang->choice('@main.label.world', '0'));

		self::assertEquals('world', $lang->choice('@main.label.world', 1));
		self::assertEquals('world', $lang->choice('@main.label.world', '1'));

		self::assertEquals('worlds', $lang->choice('@main.label.world', 1.5));
		self::assertEquals('worlds', $lang->choice('@main.label.world', '1.5'));

		$lang->setLocale('ru');

		self::assertEquals('миров', $lang->choice('@main.label.world', 0));
		self::assertEquals('миров', $lang->choice('@main.label.world', '0'));

		self::assertEquals('мир', $lang->choice('@main.label.world', 1));
		self::assertEquals('мир', $lang->choice('@main.label.world', '1'));

		self::assertEquals('мира', $lang->choice('@main.label.world', 1.5));
		self::assertEquals('мира', $lang->choice('@main.label.world', '1.5'));
		self::assertEquals('мира', $lang->choice('@main.label.world', 2));
		self::assertEquals('мира', $lang->choice('@main.label.world', '2'));

		self::assertEquals('миров', $lang->choice('@main.label.world', 5));
		self::assertEquals('миров', $lang->choice('@main.label.world', '5'));
		self::assertEquals('миров', $lang->choice('@main.label.world', 11));
		self::assertEquals('миров', $lang->choice('@main.label.world', '11'));
		self::assertEquals('миров', $lang->choice('@main.label.world', 15));
		self::assertEquals('миров', $lang->choice('@main.label.world', '15'));

		self::assertEquals('мир', $lang->choice('@main.label.world', 21));
		self::assertEquals('мир', $lang->choice('@main.label.world', '21'));

		self::assertEquals('мира', $lang->choice('@main.label.world', 22));
		self::assertEquals('мира', $lang->choice('@main.label.world', '22'));
	}


	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function testException1_()
	{
		$this->expectException(WordNotFoundError::class);

		$lang = $this->fixtureLang();

		$lang->get('@main.title.hello-world2');
	}

	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function testException2_()
	{
		$this->expectException(InvalidArgumentException::class);

		$lang = $this->fixtureLang();

		$lang->choice('@main.label.world', false);
	}

	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function testException3_()
	{
		$this->expectException(InvalidArgumentException::class);

		$lang = $this->fixtureLang();

		$lang->choice('@main.label.world', 'string');
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