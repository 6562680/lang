<?php

namespace Gzhegow\Lang\Domain;

use Gzhegow\Support\Arr;
use Gzhegow\Support\Php;
use Gzhegow\Support\Str;
use Gzhegow\Support\Type;
use Gzhegow\Support\Bcmath;
use Gzhegow\Lang\Repo\WordRepoInterface;
use Gzhegow\Lang\Repo\Memory\MemoryWordRepo;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Logic\InvalidArgumentException;
use Gzhegow\Lang\Exceptions\Logic\Error\LanguageNotFoundError;

/**
 * Class Lang
 */
class Lang implements LangInterface
{
	/**
	 * @var Arr
	 */
	protected $arr;
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
	 * @var Type
	 */
	protected $type;

	/**
	 * @var WordRepoInterface
	 */
	protected $wordRepo;
	/**
	 * @var MemoryWordRepo
	 */
	protected $memoryWordRepo;

	/**
	 * @var string[]
	 */
	protected $languages = [];

	/**
	 * @var string
	 */
	protected $lang;
	/**
	 * @var string
	 */
	protected $langDefault;

	/**
	 * @var string
	 */
	protected $localeNumeric;

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
	 * @param Arr               $arr
	 * @param Bcmath            $bcmath
	 * @param Str               $str
	 * @param Php               $php
	 * @param Type              $type
	 *
	 * @param WordRepoInterface $wordRepo
	 * @param MemoryWordRepo    $memoryWordRepo
	 *
	 * @param array             $languages
	 *
	 * @param string            $lang
	 * @param string|null       $langDefault
	 *
	 * @param string|null       $localeNumeric
	 * @param string|null       $localeSuffix
	 *
	 * @throws LanguageNotFoundError
	 */
	public function __construct(
		Arr $arr,
		Bcmath $bcmath,
		Str $str,
		Php $php,
		Type $type,

		WordRepoInterface $wordRepo,
		MemoryWordRepo $memoryWordRepo,

		array $languages,

		string $lang,
		string $langDefault = null,

		string $localeNumeric = null,
		string $localeSuffix = null
	)
	{
		$this->arr = $arr;
		$this->bcmath = $bcmath;
		$this->str = $str;
		$this->php = $php;
		$this->type = $type;

		$this->wordRepo = $wordRepo;
		$this->memoryWordRepo = $memoryWordRepo;

		$this->languages = $languages;

		$this->localeSuffix = $localeSuffix;

		$this->setLangDefault($langDefault ?? $lang);
		$this->setLang($lang, $localeNumeric);
	}


	/**
	 * @param array $groups
	 *
	 * @return Lang
	 */
	public function load(...$groups)
	{
		array_walk_recursive($groups, function (string $group) {
			if (isset($this->loadedGroups[ $group ])) {
				return;
			}

			$this->loadedGroups[ $group ] = false;
		});

		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getLanguages() : array
	{
		return $this->languages;
	}


	/**
	 * @return string
	 */
	public function getLang() : string
	{
		return $this->lang;
	}

	/**
	 * @return string
	 */
	public function getLangDefault() : string
	{
		return $this->langDefault;
	}


	/**
	 * @param string $lang
	 *
	 * @return array
	 */
	public function getLanguageFor(string $lang) : array
	{
		return $this->languages[ $lang ];
	}

	/**
	 * @return array
	 */
	public function getLanguage() : array
	{
		return $this->getLanguageFor($this->lang);
	}

	/**
	 * @return array
	 */
	public function getLanguageDefault() : array
	{
		return $this->getLanguageFor($this->langDefault);
	}


	/**
	 * @param string $lang
	 *
	 * @return string
	 */
	public function getLocaleFor(string $lang) : string
	{
		return $this->languages[ $lang ][ 'locale' ];
	}

	/**
	 * @return string
	 */
	public function getLocale() : string
	{
		return $this->getLocaleFor($this->lang);
	}

	/**
	 * @return string
	 */
	public function getLocaleDefault() : string
	{
		return $this->getLocaleFor($this->langDefault);
	}


	/**
	 * @param string $lang
	 *
	 * @return \Closure
	 */
	protected function getLanguagePluralFor(string $lang) : \Closure
	{
		return $this->languages[ $lang ][ 'plural' ];
	}


	/**
	 * @param string      $lang
	 * @param string|null $localeNumeric
	 *
	 * @return Lang
	 * @throws LanguageNotFoundError
	 */
	public function setLang(string $lang, string $localeNumeric = null)
	{
		if ('' === $lang) {
			throw new InvalidArgumentException('Lang should be not empty');
		}

		if ('' === $localeNumeric) {
			throw new InvalidArgumentException('LocaleNumeric should be not empty');
		}

		if (! isset($this->languages[ $lang ])) {
			throw new LanguageNotFoundError('Language not exists: ' . $lang, $this->languages);
		}

		$this->lang = $lang;

		$this->localeNumeric = $localeNumeric ?? 'C';

		setlocale(LC_COLLATE, $this->languages[ $this->lang ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_CTYPE, $this->languages[ $this->lang ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_TIME, $this->languages[ $this->lang ][ 'locale' ] . $this->localeSuffix);
		setlocale(LC_MONETARY, $this->languages[ $this->lang ][ 'locale' ] . $this->localeSuffix);

		setlocale(LC_NUMERIC, $this->localeNumeric ?? 'C');

		return $this;
	}

	/**
	 * @param string $lang
	 *
	 * @return Lang
	 * @throws LanguageNotFoundError
	 */
	public function setLangDefault(string $lang)
	{
		if ('' === $lang) {
			throw new InvalidArgumentException('Lang should be not empty');
		}

		if (! isset($this->languages[ $lang ])) {
			throw new LanguageNotFoundError('Language not exists: ' . $lang, $this->languages);
		}

		$this->langDefault = $lang;

		return $this;
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return string
	 * @throws \Gzhegow\Lang\Exceptions\Error\WordNotFoundError
	 */
	public function get(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : string
	{
		$langs = array_filter([
			$lang,
			$this->lang,
			$this->langDefault,
		]);

		$langCurrent = null;
		$plurals = null;
		while ( ! $plurals && $langs ) {
			if ($this->has($aword, $langCurrent = array_shift($langs), $group, $word, $plurals)) {
				break;
			}
		}

		if (! $plurals) {
			throw new WordNotFoundError('Word not found: ' . $aword, func_get_args());
		}

		$result = $this->interpolate($plurals[ 0 ], $placeholders[ $aword ] ?? $placeholders);

		return $result;
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->getOrDefault($aword, $placeholders, null, $group, $lang, $word);
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->getOrDefault($aword, $placeholders, $aword, $group, $lang, $word);
	}

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string
	{
		try {
			return $this->get($aword, $placeholders, $group, $lang, $word);
		}
		catch ( WordNotFoundError $e ) {
			return $default;
		}
	}


	/**
	 * @param string      $aword
	 * @param string      $lang
	 * @param string|null $group
	 * @param string|null $word
	 * @param string      $result
	 *
	 * @return bool
	 */
	public function has(string $aword, string $lang, string $group = null, string &$word = null, string &$result = null) : bool
	{
		if ($aword[ 0 ] !== '@') {
			$word = $aword;

			return $aword;
		}

		$this->syncWords();

		$word = mb_substr($aword, 1);

		if (! $result = $this->memoryWordRepo->first($word, $group, $lang)) {
			return false;
		}

		$result = $result->plurals;

		return true;
	}


	/**
	 * @param string|null $lang
	 * @param string|null $url
	 * @param array       $q
	 * @param string|null $ref
	 *
	 * @return string
	 */
	public function languagePath(string $lang = null, string $url = null, array $q = null, string $ref = null) : string
	{
		$lang = $lang ?? $this->lang;
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

		$langPath = '/'
			. ltrim($info[ 'path' ], '/');

		foreach ( array_keys($this->languages) as $loc ) {
			if ($langPath === '/' . $loc) {
				$langPath = '';
				break;
			}

			if (null !== ( $result = $this->str->starts($langPath, '/' . $loc . '/') )) {
				$langPath = '/' . $result;
				break;
			}
		}

		$langPath = ( $lang !== $this->langDefault )
			? '/' . $lang . $langPath
			: $langPath;

		$langPath = $langPath
			. rtrim('?' . http_build_query($q), '?')
			. rtrim('#' . $ref, '#');

		return $langPath
			?: '/';
	}


	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : string
	{
		if (! ( 0
			|| ( false !== filter_var($number, FILTER_VALIDATE_INT) )
			|| ( false !== filter_var($number, FILTER_VALIDATE_FLOAT) )
		)) {
			throw new InvalidArgumentException('Number should be int or float', func_get_args());
		}

		$number = $this->bcmath->bcabs($number);

		$langs = array_filter([
			$lang,
			$this->lang,
			$this->langDefault,
		]);

		$loc = null;
		$plurals = null;
		while ( ! $plurals && $langs ) {
			if ($this->has($aword, $loc = array_shift($langs), $group, $word, $plurals)) {
				break;
			}
		}

		if (! $plurals) {
			throw new WordNotFoundError('Word not found: ' . $aword, func_get_args());
		}

		$pluralKey = $this->getLanguagePluralFor($loc)($number);

		$result = $this->interpolate($plurals[ $pluralKey ] ?? $plurals[ 0 ], $placeholders[ $aword ] ?? $placeholders);

		return $result;
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->choiceOrDefault($aword, $number, $placeholders, null, $group, $lang, $word);
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->choiceOrDefault($aword, $number, $placeholders, $aword, $group, $lang, $word);
	}

	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string
	{
		try {
			return $this->choice($aword, $number, $placeholders, $group, $lang, $word);
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
	 * @param string      $lang
	 *
	 * @return array
	 */
	public function translate(array $dct, array $placeholders = [], string $group = null, string $lang = null) : array
	{
		$result = [];

		foreach ( $this->arr->walk($dct) as $fullpath => $aword ) {
			if (is_iterable($aword)) continue;

			$result[ $this->arr->key($fullpath) ] = $this->getOrWord($aword, $placeholders, $group, $lang);
		}

		return $result;
	}

	/**
	 * @param iterable    $iterable
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $lang
	 *
	 * @return array
	 */
	public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $lang = null) : array
	{
		$result = [];

		foreach ( $iterable as $key => $array ) {
			$result[ $key ] = $this->translate($array, $placeholders, $group, $lang);
		}

		return $result;
	}


	/**
	 * @param array       $words
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @return array
	 */
	public function collect(array $words, array $placeholders = [], string $group = null, string $lang = null) : array
	{
		$result = [];

		foreach ( $words as $aword ) {
			$phrase = $this->getOrWord($aword, $placeholders, $group, $lang, $word);

			$result[ $word ] = $phrase;
		}

		return $result;
	}


	/**
	 * @return Lang
	 */
	protected function syncWords()
	{
		$groups = [];
		foreach ( $this->loadedGroups as $group => $bool ) {
			if ($bool) continue;

			$groups[] = $group;
			$this->loadedGroups[ $group ] = true;
		}

		$models = $this->wordRepo->getByGroups($groups);

		$this->memoryWordRepo->saveMany($models);

		return $this;
	}
}