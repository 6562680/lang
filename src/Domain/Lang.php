<?php

namespace Gzhegow\Lang\Domain;

use Gzhegow\Lang\Libs\Arr;
use Gzhegow\Lang\Libs\Php;
use Gzhegow\Lang\Libs\Str;
use Gzhegow\Lang\Libs\Bcmath;
use Gzhegow\Lang\Repo\WordRepoInterface;
use Gzhegow\Lang\Repo\Memory\MemoryWordRepo;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Error\LocaleNotFoundError;
use Gzhegow\Lang\Exceptions\Logic\InvalidArgumentException;

/**
 * Class Lang
 */
class Lang implements LangInterface
{
	/**
	 * @var WordRepoInterface
	 */
	protected $wordRepo;
	/**
	 * @var MemoryWordRepo
	 */
	protected $memoryWordRepo;

	/**
	 * @var Bcmath
	 */
	protected $bcmath;
	/**
	 * @var Str
	 */
	protected $str;
	/**
	 * @var Php
	 */
	protected $php;

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
	protected $localeDefault;
	/**
	 * @var string
	 */
	protected $localeSuffix;

	/**
	 * @var array
	 */
	protected $loadedGroups = [];


	/**
	 * Constructor
	 *
	 * @param WordRepoInterface $wordRepo
	 * @param MemoryWordRepo    $memoryWordRepo
	 *
	 * @param Bcmath            $bcmath
	 * @param Str               $str
	 * @param Php               $php
	 *
	 * @param array             $locales
	 * @param string            $locale
	 * @param string|null       $localeNumeric
	 * @param string|null       $localeFallback
	 * @param string|null       $localeSuffix
	 *
	 * @throws LocaleNotFoundError
	 */
	public function __construct(
		WordRepoInterface $wordRepo,
		MemoryWordRepo $memoryWordRepo,

		Bcmath $bcmath,
		Str $str,
		Php $php,

		array $locales,
		string $locale,
		string $localeNumeric = null,
		string $localeFallback = null,
		string $localeSuffix = null
	)
	{
		$this->wordRepo = $wordRepo;
		$this->memoryWordRepo = $memoryWordRepo;

		$this->bcmath = $bcmath;
		$this->str = $str;
		$this->php = $php;

		$this->locales = $locales;

		$this->localeSuffix = $localeSuffix;

		$this->setLocaleDefault($locale);
		$this->setLocale($locale, $localeNumeric);

		$this->localeFallback = $localeFallback ?? $this->locale;
	}


	/**
	 * @return string[]
	 */
	public function getLocales() : array
	{
		return $this->locales;
	}


	/**
	 * @return string
	 */
	public function getLoc() : string
	{
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getLocDefault() : string
	{
		return $this->localeDefault;
	}

	/**
	 * @return string
	 */
	public function getLocFallback() : string
	{
		return $this->localeFallback;
	}


	/**
	 * @param string $locale
	 *
	 * @return array
	 */
	public function getLocaleFor(string $locale) : array
	{
		return $this->locales[ $locale ];
	}

	/**
	 * @return array
	 */
	public function getLocale() : array
	{
		return $this->getLocaleFor($this->locale);
	}

	/**
	 * @return array
	 */
	public function getLocaleDefault() : array
	{
		return $this->getLocaleFor($this->localeDefault);
	}

	/**
	 * @return array
	 */
	public function getLocaleFallback() : array
	{
		return $this->getLocaleFor($this->localeFallback);
	}


	/**
	 * @return string
	 */
	public function getLocaleNumeric() : string
	{
		return $this->localeNumeric;
	}


	/**
	 * @return string
	 */
	public function getLocaleSuffix() : string
	{
		return $this->localeSuffix;
	}


	/**
	 * @param string      $locale
	 * @param string|null $localeNumeric
	 *
	 * @throws LocaleNotFoundError
	 */
	public function setLocale(string $locale, string $localeNumeric = null) : void
	{
		if ('' === $locale) {
			throw new InvalidArgumentException('Locale should be not empty');
		}

		if ('' === $localeNumeric) {
			throw new InvalidArgumentException('LocaleNumeric should be not empty');
		}

		if (! isset($this->locales[ $locale ])) {
			throw new LocaleNotFoundError('Locale not exists: ' . $locale, $this->locales);
		}

		$this->locale = $locale;
		$this->localeNumeric = $localeNumeric ?? 'C';

		setlocale(LC_COLLATE, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_CTYPE, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_TIME, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_MONETARY, $this->locales[ $this->locale ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_NUMERIC, $this->localeNumeric ?? 'C');
	}

	/**
	 * @param string $locale
	 *
	 * @throws LocaleNotFoundError
	 */
	public function setLocaleDefault(string $locale) : void
	{
		if ('' === $locale) {
			throw new InvalidArgumentException('Locale should be not empty');
		}

		if (! isset($this->locales[ $locale ])) {
			throw new LocaleNotFoundError('Locale not exists: ' . $locale, $this->locales);
		}

		$this->localeDefault = $locale;
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function get(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string
	{
		$locales = array_filter([
			$locale,
			$this->locale,
			$this->localeFallback,
		]);

		$loc = null;
		$plurals = null;
		while ( ! $plurals && $locales ) {
			if ($this->has($aword, $loc = array_shift($locales), $group, $word, $plurals)) {
				break;
			}
		}

		if (! $plurals) {
			throw new WordNotFoundError('Word not found', func_get_args());
		}

		$result = $this->interpolate($plurals[ 0 ], $placeholders[ $aword ] ?? $placeholders);

		return $result;
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string
	{
		return $this->getOrDefault($aword, $placeholders, null, $group, $locale, $word);
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string
	{
		return $this->getOrDefault($aword, $placeholders, $aword, $group, $locale, $word);
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string
	{
		try {
			return $this->get($aword, $placeholders, $group, $locale, $word);
		}
		catch ( WordNotFoundError $e ) {
			return $default;
		}
	}


	/**
	 * @param string      $aword
	 * @param string      $locale
	 * @param string|null $group
	 * @param string|null $word
	 * @param string      $result
	 *
	 * @return bool
	 */
	public function has(string $aword, string $locale, string $group = null, string &$word = null, string &$result = null) : bool
	{
		if ($aword[ 0 ] !== '@') {
			$word = $aword;

			return $aword;
		}

		$this->syncWords();

		$word = mb_substr($aword, 1);

		if (! $result = $this->memoryWordRepo->first($word, $group, $locale)) {
			return false;
		}

		$result = $result->words;

		return true;
	}


	/**
	 * @param string $locale
	 *
	 * @return \Closure
	 */
	protected function getLocalePluralFor(string $locale) : \Closure
	{
		return $this->locales[ $locale ][ 'plural' ];
	}

	/**
	 * @return \Closure
	 */
	protected function getLocalePlural() : \Closure
	{
		return $this->getLocalePluralFor($this->locale);
	}

	/**
	 * @return \Closure
	 */
	protected function getLocaleDefaultPlural() : \Closure
	{
		return $this->getLocalePluralFor($this->localeDefault);
	}

	/**
	 * @return \Closure
	 */
	protected function getLocaleFallbackPlural() : \Closure
	{
		return $this->getLocalePluralFor($this->localeFallback);
	}


	/**
	 * @param string|null $locale
	 * @param string|null $url
	 * @param array       $q
	 * @param string|null $ref
	 *
	 * @return string
	 */
	public function localePath(string $locale = null, string $url = null, array $q = null, string $ref = null) : string
	{
		$locale = $locale ?? $this->locale;
		$q = $q ?? [];

		$info = parse_url($url)
			+ [
				'path'     => null,
				'query'    => null,
				'fragment' => null,
			];

		$ref = $ref ?? $info[ 'fragment' ];

		parse_str($info[ 'query' ], $data);

		$q += $data;

		$localePath = '/'
			. ltrim($info[ 'path' ], '/');

		foreach ( array_keys($this->locales) as $loc ) {
			if ($localePath === '/' . $loc) {
				$localePath = '';
				break;
			}

			if (null !== ( $result = $this->str->starts($localePath, '/' . $loc . '/') )) {
				$localePath = '/' . $result;
				break;
			}
		}

		$localePath = ( $locale !== $this->localeDefault )
			? '/' . $locale . $localePath
			: $localePath;

		$localePath = $localePath
			. rtrim('?' . http_build_query($q), '?')
			. rtrim('#' . $ref, '#');

		return $localePath ?: '/';
	}


	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 * @param string|null $word
	 *
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string
	{
		if (! ( 0
			|| ( false !== filter_var($number, FILTER_VALIDATE_INT) )
			|| ( false !== filter_var($number, FILTER_VALIDATE_FLOAT) )
		)) {
			throw new InvalidArgumentException('Number should be int or float');
		}

		$number = $this->bcmath->bcabs($number);

		$locales = array_filter([
			$locale,
			$this->locale,
			$this->localeFallback,
		]);

		$loc = null;
		$plurals = null;
		while ( ! $plurals && $locales ) {
			if ($this->has($aword, $loc = array_shift($locales), $group, $word, $plurals)) {
				break;
			}
		}

		if (! $plurals) {
			throw new WordNotFoundError('Word not found', func_get_args());
		}

		$pluralKey = $this->getLocalePluralFor($loc)($number);

		$result = $this->interpolate($plurals[ $pluralKey ] ?? $plurals[ 0 ], $placeholders[ $aword ] ?? $placeholders);

		return $result;
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string
	{
		return $this->choiceOrDefault($aword, $number, $placeholders, null, $group, $locale, $word);
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string
	{
		return $this->choiceOrDefault($aword, $number, $placeholders, $aword, $group, $locale, $word);
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string
	{
		try {
			return $this->choice($aword, $number, $placeholders, $group, $locale, $word);
		}
		catch ( WordNotFoundError $e ) {
			return $default;
		}
	}


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

		foreach ( $arr->walk($dct) as $fullpath => $aword ) {
			if (is_iterable($aword)) continue;

			$result[ $arr->key($fullpath) ] = $this->getOrWord($aword, $placeholders, $group, $locale);
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

		foreach ( $words as $aword ) {
			$phrase = $this->getOrWord($aword, $placeholders, $group, $locale, $word);

			$result[ $word ] = $phrase;
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

		$models = $this->wordRepo->getByGroups($groups);

		$this->memoryWordRepo->saveMany($models);
	}
}