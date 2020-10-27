<?php

namespace Gzhegow\Lang\Repo\Memory;

use Gzhegow\Lang\Models\WordModel;

/**
 * Class MemoryWordRepo
 */
class MemoryWordRepo
{
	/**
	 * @var array
	 */
	protected $words = [];

	/**
	 * @var array
	 */
	protected $wordsIndex = [];
	/**
	 * @var array
	 */
	protected $wordsIndexByGroup = [];
	/**
	 * @var array
	 */
	protected $wordsIndexByLocale = [];
	/**
	 * @var array
	 */
	protected $wordsIndexByLocaleGroup = [];


	/**
	 * @param string      $word
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function get(string $word, string $group = null, string $locale = null) : array
	{
		if ($group && $locale) {
			$ref =& $this->wordsIndexByLocaleGroup[ $locale . $group ][ $word ];

		} elseif ($group) {
			$ref =& $this->wordsIndexByGroup[ $group ][ $word ];

		} elseif ($locale) {
			$ref =& $this->wordsIndexByLocale[ $locale ][ $word ];

		} else {
			$ref =& $this->wordsIndex[ $word ];

		}

		$result = [];

		foreach ( $ref ?? [] as $idx ) {
			$result[] = $this->words[ $idx ];
		}

		return $result;
	}

	/**
	 * @param string      $word
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return null|WordModel
	 */
	public function first(string $word, string $group = null, string $locale = null) : ?WordModel
	{
		$result = $this->get($word, $group, $locale);

		return ( null !== key($result) )
			? reset($result)
			: null;
	}


	/**
	 * @param WordModel $model
	 *
	 * @return void
	 */
	public function save(WordModel $model) : void
	{
		$this->words[] = $model;
		$lastKey = array_key_last($this->words);

		$this->wordsIndex[ $model->key ][] = $lastKey;
		$this->wordsIndexByGroup[ $model->group ][ $model->key ][] = $lastKey;
		$this->wordsIndexByLocale[ $model->lang ][ $model->key ][] = $lastKey;
		$this->wordsIndexByLocaleGroup[ $model->lang . $model->group ][ $model->key ][] = $lastKey;
	}

	/**
	 * @param WordModel[] $models
	 *
	 * @return void
	 */
	public function saveMany(array $models) : void
	{
		foreach ( $models as $model ) {
			$this->save($model);
		}
	}
}
