<?php

namespace Gzhegow\Lang\Domain;

use Gzhegow\Lang\Libs\Arr;
use Gzhegow\Lang\Libs\Php;
use Gzhegow\Lang\Repo\File\FileWordRepo;
use Gzhegow\Lang\Repo\Memory\MemoryWordRepo;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundException;

/**
 * Class Lang
 */
class Lang
{
	/**
	 * @var array
	 */
	protected $loadedGroups = [];


	/**
	 * @var string[]
	 */
	protected $locales = [];

	/**
	 * @var string
	 */
	protected $locale;
	/**
	 * @var string
	 */
	protected $localeFallback;
	/**
	 * @var string
	 */
	protected $localeNumeric;

	/**
	 * @var string
	 */
	protected $localeSuffix;


	/**
	 * @var FileWordRepo
	 */
	protected $fileWordRepo;
	/**
	 * @var MemoryWordRepo
	 */
	protected $memoryWordRepo;


	/**
	 * Constructor
	 *
	 * @param FileWordRepo   $fileWordRepo
	 * @param MemoryWordRepo $memoryWordRepo
	 * @param array          $locales
	 * @param string         $locale
	 * @param string|null    $localeFallback
	 * @param string|null    $localeSuffix
	 * @param string|null    $localeNumeric
	 */
	public function __construct(
		FileWordRepo $fileWordRepo,
		MemoryWordRepo $memoryWordRepo,
		array $locales,
		string $locale,
		string $localeNumeric = null,
		string $localeFallback = null,
		string $localeSuffix = null
	)
	{
		$this->fileWordRepo = $fileWordRepo;
		$this->memoryWordRepo = $memoryWordRepo;

		$this->locales = $locales;

		$this->localeSuffix = $localeSuffix;

		$this->setLocale($locale, $localeNumeric);

		$this->localeFallback = $localeFallback ?? $this->locale;
	}


	/**
	 * @return string
	 */
	public function getLocale() : string
	{
		return $this->locale;
	}

	/**
	 * @return string[]
	 */
	public function getLocales() : array
	{
		return $this->locales;
	}


	/**
	 * @param string      $locale
	 * @param string|null $localeNumeric
	 */
	public function setLocale(string $locale, string $localeNumeric = null) : void
	{
		$this->locale = $locale;
		$this->localeNumeric = $localeNumeric ?? 'C';

		setlocale(LC_COLLATE, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_CTYPE, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_TIME, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_MONETARY, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_NUMERIC, $this->localeNumeric ?? 'C');
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return string
	 * @throws WordNotFoundException
	 */
	public function get(string $aword, array $placeholders = [], string $group = null, string $locale = null) : string
	{
		if ($aword[ 0 ] !== '@') {
			return $aword;
		}

		$this->syncWords();

		$word = mb_substr($aword, 1);

		$locales = array_filter([
			$locale,
			$this->locale,
			$this->localeFallback,
		]);

		$pluralKey = 0;

		$result = null;
		while ( ! $result && $locales ) {
			$result = $this->memoryWordRepo->first($word, $group, array_shift($locales));

			if ($result) {
				$result = $result->words[ $pluralKey ];
				break;
			}
		}

		if (! $result) {
			throw new WordNotFoundException('Word not found', func_get_args());
		}

		$result = $this->interpolate($result, $placeholders[ $aword ] ?? $placeholders);

		return $result;
	}

	/**
	 * @param string      $word
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return null|string
	 */
	public function getOrNull(string $word, array $placeholders = [], string $group = null, string $locale = null) : ?string
	{
		return $this->getOrDefault($word, $placeholders, null, $group, $locale);
	}

	/**
	 * @param string      $word
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return null|string
	 */
	public function getOrWord(string $word, array $placeholders = [], string $group = null, string $locale = null) : ?string
	{
		return $this->getOrDefault($word, $placeholders, $word, $group, $locale);
	}

	/**
	 * @param string      $word
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return null|string
	 */
	public function getOrDefault(string $word, array $placeholders = [], string $default = null, string $group = null, string $locale = null) : ?string
	{
		try {
			return $this->get($word, $placeholders, $group, $locale);
		}
		catch ( WordNotFoundException $e ) {
			return $default;
		}
	}


	// public function choice(string $word, $number, array $placeholders = [], string $group = null, string $locale = null) : string
	// {
	// 	return $this->get($word, $locale);
	// }


	/**
	 * @param string $word
	 * @param array  $placeholders
	 *
	 * @return string
	 */
	public function interpolate(string $word, array $placeholders) : string
	{
		$php = new Php();

		[ $kwargs, $args ] = $php->kwargs($placeholders);

		$replacements = [];

		foreach ( array_keys($kwargs) as $key ) {
			$replacements[] = ':' . $key;
		}

		$interpolated = str_replace($replacements, array_values($kwargs), $word);

		if (1
			&& $args
			&& ( false !== ( $pos = mb_strpos($interpolated, ':') ) )
			&& ( ctype_alpha($interpolated[ $pos + 1 ]) )
		) {
			$interpolated = preg_replace_callback('~[:][\p{L}][\p{L}\p{N}_]*~', function () use (&$args) {
				return array_shift($args);
			}, $interpolated);
		}

		return $interpolated;
	}


	/**
	 * @param array       $dct
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $locale
	 *
	 * @return array
	 */
	public function translate(array $dct, array $placeholders = [], string $group = null, string $locale = null) : array
	{
		$result = [];

		$arr = new Arr();

		foreach ( $arr->walk($dct) as $fullpath => $value ) {
			if (is_iterable($value)) continue;

			$result[ $arr->key($fullpath) ] = $this->getOrWord($value, $placeholders, $group, $locale);
		}

		return $result;
	}

	/**
	 * @param iterable    $iterable
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $locale
	 *
	 * @return array
	 */
	public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $locale = null) : array
	{
		$result = [];

		foreach ( $iterable as $key => $array ) {
			$result[ $key ] = $this->translate($array, $placeholders, $group, $locale);
		}

		return $result;
	}


	/**
	 * @param array       $words
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return array
	 */
	public function collect(array $words, array $placeholders = [], string $group = null, string $locale = null) : array
	{
		$result = [];

		foreach ( $words as $word ) {
			$result[ mb_substr($word, 1) ] = $this->getOrWord($word, $placeholders, $group, $locale);
		}

		return $result;
	}


	/**
	 * @param array $groups
	 *
	 * @return void
	 */
	public function load(...$groups) : void
	{
		array_walk_recursive($groups, function (string $group) {
			if (isset($this->loadedGroups[ $group ])) {
				return;
			}

			$this->loadedGroups[ $group ] = false;
		});
	}


	/**
	 * @return void
	 */
	protected function syncWords() : void
	{
		$groups = [];
		foreach ( $this->loadedGroups as $group => $bool ) {
			if ($bool) continue;

			$groups[] = $group;
			$this->loadedGroups[ $group ] = true;
		}

		$models = $this->fileWordRepo->getByGroups($groups);

		$this->memoryWordRepo->saveMany($models);
	}
}