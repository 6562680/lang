<?php

namespace Gzhegow\Lang\Tests;

use Gzhegow\Di\Di;
use Gzhegow\Lang\Domain\LangInterface;
use Gzhegow\Lang\Tests\Di\LangProvider;
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
		$lang = static::getLang();

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
		$lang = static::getLang();

		$lang->load('main');

		$words = $lang->collect([ '@main.title.hello-world' ]);

		self::assertEquals('Hello, World', $words[ 'main.title.hello-world' ]);
	}

	/**
	 * @return void
	 * @throws \Gzhegow\Lang\Exceptions\Error\WordNotFoundError
	 */
	public function test3_()
	{
		$lang = static::getLang();

		$lang->load('main');

		$lang->setLang('en');

		self::assertEquals('worlds', $lang->choice('@main.label.world', 0));
		self::assertEquals('worlds', $lang->choice('@main.label.world', '0'));

		self::assertEquals('world', $lang->choice('@main.label.world', 1));
		self::assertEquals('world', $lang->choice('@main.label.world', '1'));

		self::assertEquals('worlds', $lang->choice('@main.label.world', 1.5));
		self::assertEquals('worlds', $lang->choice('@main.label.world', '1.5'));

		$lang->setLang('ru');

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
	 */
	public function test4_()
	{
		$lang = static::getLang();

		$lang->setLangDefault('en');

		$lang->setLang('en');

		self::assertEquals('/', $lang->languagePath(null, '/'));
		self::assertEquals('/', $lang->languagePath(null, '/en'));
		self::assertEquals('/', $lang->languagePath(null, '/en/'));
		self::assertEquals('/english', $lang->languagePath(null, '/english'));
		self::assertEquals('/english/', $lang->languagePath(null, '/english/'));
		self::assertEquals('/english', $lang->languagePath(null, '/en/english'));
		self::assertEquals('/english/', $lang->languagePath(null, '/en/english/'));
		self::assertEquals('/russian', $lang->languagePath(null, '/russian'));
		self::assertEquals('/russian/', $lang->languagePath(null, '/russian/'));
		self::assertEquals('/russian', $lang->languagePath(null, '/ru/russian'));
		self::assertEquals('/russian/', $lang->languagePath(null, '/ru/russian/'));

		self::assertEquals('/ru/', $lang->languagePath('ru', '/'));
		self::assertEquals('/ru', $lang->languagePath('ru', '/en'));
		self::assertEquals('/ru/', $lang->languagePath('ru', '/en/'));
		self::assertEquals('/ru/english', $lang->languagePath('ru', '/english'));
		self::assertEquals('/ru/english/', $lang->languagePath('ru', '/english/'));
		self::assertEquals('/ru/english', $lang->languagePath('ru', '/en/english'));
		self::assertEquals('/ru/english/', $lang->languagePath('ru', '/en/english/'));
		self::assertEquals('/ru/russian', $lang->languagePath('ru', '/russian'));
		self::assertEquals('/ru/russian', $lang->languagePath('ru', '/ru/russian'));
		self::assertEquals('/ru/russian/', $lang->languagePath('ru', '/ru/russian/'));

		$lang->setLang('ru');

		self::assertEquals('/ru/', $lang->languagePath(null, '/'));
		self::assertEquals('/ru', $lang->languagePath(null, '/en'));
		self::assertEquals('/ru/', $lang->languagePath(null, '/en/'));
		self::assertEquals('/ru/english', $lang->languagePath(null, '/english'));
		self::assertEquals('/ru/english/', $lang->languagePath(null, '/english/'));
		self::assertEquals('/ru/english', $lang->languagePath(null, '/en/english'));
		self::assertEquals('/ru/english/', $lang->languagePath(null, '/en/english/'));
		self::assertEquals('/ru/russian', $lang->languagePath(null, '/russian'));
		self::assertEquals('/ru/russian/', $lang->languagePath(null, '/russian/'));
		self::assertEquals('/ru/russian', $lang->languagePath(null, '/ru/russian'));
		self::assertEquals('/ru/russian/', $lang->languagePath(null, '/ru/russian/'));

		self::assertEquals('/', $lang->languagePath('en', '/'));
		self::assertEquals('/', $lang->languagePath('en', '/en'));
		self::assertEquals('/', $lang->languagePath('en', '/en/'));
		self::assertEquals('/english', $lang->languagePath('en', '/english'));
		self::assertEquals('/english/', $lang->languagePath('en', '/english/'));
		self::assertEquals('/english', $lang->languagePath('en', '/en/english'));
		self::assertEquals('/english/', $lang->languagePath('en', '/en/english/'));
		self::assertEquals('/russian', $lang->languagePath('en', '/russian'));
		self::assertEquals('/russian/', $lang->languagePath('en', '/russian/'));
		self::assertEquals('/russian', $lang->languagePath('en', '/ru/russian'));
		self::assertEquals('/russian/', $lang->languagePath('en', '/ru/russian/'));
	}


	/**
	 * @return void
	 * @throws \Gzhegow\Lang\Exceptions\Error\WordNotFoundError
	 */
	public function testException1_()
	{
		$this->expectException(WordNotFoundError::class);

		$lang = static::getLang();

		$lang->get('@main.title.hello-world2');
	}

	/**
	 * @return void
	 * @throws WordNotFoundError
	 */
	public function testException2_()
	{
		$this->expectException(InvalidArgumentException::class);

		$lang = static::getLang();

		$lang->choice('@main.label.world', false);
	}

	/**
	 * @return void
	 * @throws \Gzhegow\Lang\Exceptions\Error\WordNotFoundError
	 */
	public function testException3_()
	{
		$this->expectException(InvalidArgumentException::class);

		$lang = static::getLang();

		$lang->choice('@main.label.world', 'string');
	}


	/**
	 * @return LangInterface
	 */
	protected static function getLang() : LangInterface
	{
		return static::$di->getOrFail(LangInterface::class);
	}


	/**
	 * @return void
	 */
	protected static function boot() : void
	{
		if (! isset(static::$di)) {
			static::$di = Di::getInstance();
			static::$di->registerProvider(LangProvider::class);
		}
	}


	/**
	 * @var Di
	 */
	protected static $di;
}