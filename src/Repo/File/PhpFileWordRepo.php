<?php

namespace Gzhegow\Lang\Repo\File;

use Gzhegow\Support\Str;
use Gzhegow\Lang\Models\WordModel;
use Gzhegow\Lang\Models\ModelFactory;
use Gzhegow\Lang\Repo\WordRepoInterface;
use Gzhegow\Lang\Exceptions\Logic\InvalidArgumentException;

/**
 * Class PhpFileWordRepo
 */
class PhpFileWordRepo implements WordRepoInterface
{
	/**
	 * @var Str
	 */
	protected $str;

	/**
	 * @var ModelFactory
	 */
	protected $modelFactory;

	/**
	 * @var string
	 */
	protected $path;


	/**
	 * Constructor
	 *
	 * @param Str          $str
	 * @param ModelFactory $modelFactory
	 * @param string       $path
	 */
	public function __construct(
		Str $str,

		ModelFactory $modelFactory,

		string $path
	)
	{
		$this->str = $str;

		$this->modelFactory = $modelFactory;

		$this->setPath($path);
	}


	/**
	 * @param array $attributes
	 *
	 * @return WordModel
	 */
	protected function newModel(array $attributes = []) : WordModel
	{
		return $this->modelFactory->newWordModel($attributes);
	}


	/**
	 * @param string      $group
	 * @param string|null $lang
	 *
	 * @return WordModel[]
	 */
	public function getByGroup(string $group, string $lang = null) : array
	{
		$models = [];

		foreach ( scandir($this->path) as $dirLang ) {
			if ('.' === $dirLang) continue;
			if ('..' === $dirLang) continue;

			if (! is_dir($dir = $this->path . '/' . $dirLang)) {
				continue;
			}

			if ($lang && ( $dirLang !== $lang )) {
				continue;
			}

			$words = require $this->path . '/' . $dirLang . '/' . sprintf('%s.php', $group);

			foreach ( $words as $key => $phrase ) {
				$model = $this->newModel();

				$model->lang = $dirLang;
				$model->group = $group;
				$model->key = $this->str->prepend($key, $group . '.');
				$model->plurals = (array) $phrase;

				$models[] = $model;
			}
		}

		return $models;
	}

	/**
	 * @param array       $groups
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroups(array $groups, string $locale = null) : array
	{
		$result = [];

		foreach ( $groups as $group ) {
			$result = array_merge($result, $this->getByGroup($group, $locale));
		}

		return $result;
	}


	/**
	 * @param string $path
	 *
	 * @return PhpFileWordRepo
	 */
	public function setPath(string $path)
	{
		if ('' === $path) {
			throw new InvalidArgumentException('Path should be not empty');
		}

		if (! is_dir($path)) {
			throw new InvalidArgumentException('Path directory not found: ' . $path, func_get_args());
		}

		$this->path = $path;

		return $this;
	}
}
