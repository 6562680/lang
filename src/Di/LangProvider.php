<?php

namespace Gzhegow\Lang\Di;

use Gzhegow\Lang\Domain\Lang;
use Gzhegow\Di\DeferableProvider;
use Gzhegow\Lang\Repo\File\FileWordRepo;
use Gzhegow\Lang\Repo\Memory\MemoryWordRepo;

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
		$this->di->bindShared(Lang::class, function () {
			$config = require __DIR__ . '/../../config/lang.php';

			return new Lang(
				new FileWordRepo(__DIR__ . '/../../storage/resources/lang'),
				new MemoryWordRepo(),

				$config[ 'locales' ],
				$config[ 'locale' ],
				$config[ 'locale_numeric' ],
				$config[ 'locale_fallback' ],
				$config[ 'locale_suffix' ]
			);
		});
	}

	/**
	 * @return void
	 */
	public function boot() : void
	{
	}

	/**
	 * @return array
	 */
	public function provides() : array
	{
		return [
			Lang::class,
		];
	}
}
