<?php

namespace Gzhegow\Lang\Domain;

use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Error\LocaleNotFoundError;

class LangService
{
	/**
	 * @var LangInterface
	 */
	protected $lang;


	/**
	 * Constructor
	 *
	 * @param LangInterface $lang
	 */
	public function __construct(LangInterface $lang)
	{
		$this->lang = $lang;
	}


	/**
	 * @return string[]
	 */
	public function getLocales() : array
	{
		return $this->lang->getLocales();
	}


	/**
	 * @return string
	 */
	public function getLoc() : string
	{
		return $this->lang->getLoc();
	}

	/**
	 * @return string
	 */
	public function getLocFallback() : string
	{
		return $this->lang->getLocFallback();
	}


	/**
	 * @param string $locale
	 *
	 * @return array
	 */
	public function getLocaleFor(string $locale) : array
	{
		return $this->lang->getLocaleFor($locale);
	}

	/**
	 * @return array
	 */
	public function getLocale() : array
	{
		return $this->lang->getLocale();
	}

	/**
	 * @return array
	 */
	public function getLocaleFallback() : array
	{
		return $this->lang->getLocaleFallback();
	}

	/**
	 * @return string
	 */
	public function getLocaleNumeric() : string
	{
		return $this->lang->getLocaleNumeric();
	}

	/**
	 * @return string
	 */
	public function getLocaleSuffix() : string
	{
		return $this->lang->getLocaleSuffix();
	}


	/**
	 * @param string      $locale
	 * @param string|null $localeNumeric
	 */
	public function setLocale(string $locale, string $localeNumeric = null) : void
	{
		$this->lang->setLocale($locale, $localeNumeric);
	}

	/**
	 * @param string $locale
	 *
	 * @throws LocaleNotFoundError
	 */
	public function setLocaleDefault(string $locale) : void
	{
		$this->lang->setLocaleDefault($locale);
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
		return $this->lang->get($aword, $placeholders, $group, $locale, $word);
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
		return $this->lang->getOrNull($aword, $placeholders, $group, $locale, $word);
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
		return $this->lang->getOrWord($aword, $placeholders, $group, $locale, $word);
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
		return $this->lang->getOrDefault($aword, $placeholders, $default, $group, $locale, $word);
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
		return $this->lang->has($aword, $locale, $group, $word, $result);
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
		return $this->lang->localePath($locale, $url, $q, $ref);
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
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string
	{
		return $this->lang->choice($aword, $number, $placeholders, $group, $locale, $word);
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
		return $this->lang->choiceOrNull($aword, $number, $placeholders, $group, $locale, $word);
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
		return $this->lang->choiceOrWord($aword, $number, $placeholders, $group, $locale, $word);
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
		return $this->lang->choiceOrDefault($aword, $number, $placeholders, $group, $locale, $word);
	}


	/**
	 * @param string $word
	 * @param array  $placeholders
	 *
	 * @return string
	 */
	public function interpolate(string $word, array $placeholders) : string
	{
		return $this->lang->interpolate($word, $placeholders);
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
		return $this->lang->translate($dct, $placeholders, $group, $locale);
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
		return $this->lang->translateMany($iterable, $placeholders, $group, $locale);
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
		return $this->lang->collect($words, $placeholders, $group, $locale);
	}


	/**
	 * @param array $groups
	 *
	 * @return void
	 */
	public function load(...$groups) : void
	{
		$this->lang->load(...$groups);
	}
}
