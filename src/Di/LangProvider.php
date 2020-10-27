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
			$config = require __DIR__ . '/../../config/lang.php';

			return $this->di->create(PhpFileWordRepo::class, [
				'$path' => $config[ 'path' ],
			]);
		});

		$this->di->bindShared(LangInterface::class, function () {
			$config = require __DIR__ . '/../../config/lang.php';

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
}
