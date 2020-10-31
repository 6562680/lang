<?php

namespace Gzhegow\Lang\Di;

use Gzhegow\Lang\Domain\Lang;
use Gzhegow\Di\DeferableProvider;
use Gzhegow\Lang\Domain\LangInterface;
use Gzhegow\Lang\Repo\WordRepoInterface;
use Gzhegow\Lang\Repo\File\PhpFileWordRepo;

/**
 * Class LangProvider
 */
class LangProvider extends DeferableProvider
{
	/**
	 * @return void
	 */
	public function register() : void
	{
		$this->di->bind(WordRepoInterface::class, function () {
			return $this->di->create(PhpFileWordRepo::class, [
				'$path' => $this->defineRealpath('resources'),
			]);
		});

		$this->di->bindShared(LangInterface::class, function () {
			$config = require $this->defineRealpath('config');

			return $this->di->create(Lang::class, [
				'$languages' => $config[ 'languages' ],

				'$lang'        => $config[ 'lang' ],
				'$langDefault' => $config[ 'lang_default' ],

				'$localeNumeric' => $config[ 'locale_numeric' ],
				'$localeSuffix'  => $config[ 'locale_suffix' ],
			]);
		});
	}


	/**
	 * @return array
	 */
	public function provides() : array
	{
		return [
			Lang::class,
			LangInterface::class,
		];
	}


	/**
	 * @return array
	 */
	protected function define() : array
	{
		return [
			'config'    => __DIR__ . '/../../config/lang.php',
			'resources' => __DIR__ . '/../../storage/resources/lang',
		];
	}
}
