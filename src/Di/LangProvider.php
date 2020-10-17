<?php

namespace Gzhegow\Lang\Di;

use Gzhegow\Lang\Libs\Php;
use Gzhegow\Lang\Libs\Str;
use Gzhegow\Lang\Domain\Lang;
use Gzhegow\Lang\Libs\Bcmath;
use Gzhegow\Di\DeferableProvider;
use Gzhegow\Lang\Domain\LangInterface;
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
		$this->di->bindShared(LangInterface::class, function () {
			$config = require __DIR__ . '/../../config/lang.php';

			$str = new Str();

			return new Lang(
				new FileWordRepo($str, __DIR__ . '/../../storage/resources/lang'),
				new MemoryWordRepo(),

				new Bcmath(),
				new Str(),
				new Php(),

				$config[ 'locales' ],
				$config[ 'locale' ],
				$config[ 'locale_numeric' ],
				$config[ 'locale_fallback' ],
				$config[ 'locale_suffix' ]
			);
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
