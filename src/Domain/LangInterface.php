<?php

namespace Gzhegow\Lang\Domain;


use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Error\LocaleNotFoundError;

/**
 * Class Lang
 */
interface LangInterface
{
	/**
	 * @return string[]
	 */
	public function getLocales() : array;


	/**
	 * @return string
	 */
	public function getLoc() : string;

	/**
	 * @return string
	 */
	public function getLocFallback() : string;


	/**
	 * @param string $locale
	 *
	 * @return array
	 */
	public function getLocaleFor(string $locale) : array;

	/**
	 * @return array
	 */
	public function getLocale() : array;

	/**
	 * @return array
	 */
	public function getLocaleFallback() : array;


	/**
	 * @return string
	 */
	public function getLocaleNumeric() : string;

	/**
	 * @return string
	 */
	public function getLocaleSuffix() : string;


	/**
	 * @param string      $locale
	 * @param string|null $localeNumeric
	 */
	public function setLocale(string $locale, string $localeNumeric = null) : void;

	/**
	 * @param string $locale
	 *
	 * @throws LocaleNotFoundError
	 */
	public function setLocaleDefault(string $locale) : void;


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
	public function get(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string;

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
	public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;

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
	public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;

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
	public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string;


	/**
	 * @param string      $aword
	 * @param string      $locale
	 * @param string|null $group
	 * @param string|null $word
	 * @param string      $result
	 *
	 * @return bool
	 */
	public function has(string $aword, string $locale, string $group = null, string &$word = null, string &$result = null) : bool;


	/**
	 * @param string|null $locale
	 * @param string|null $url
	 * @param array       $q
	 * @param string|null $ref
	 *
	 * @return string
	 */
	public function localePath(string $locale = null, string $url = null, array $q = null, string $ref = null) : string;


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
	public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string;

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
	public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;

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
	public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;

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
	public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string;


	/**
	 * @param string $word
	 * @param array  $placeholders
	 *
	 * @return string
	 */
	public function interpolate(string $word, array $placeholders) : string;


	/**
	 * @param array       $dct
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $locale
	 *
	 * @return array
	 */
	public function translate(array $dct, array $placeholders = [], string $group = null, string $locale = null) : array;

	/**
	 * @param iterable    $iterable
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $locale
	 *
	 * @return array
	 */
	public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $locale = null) : array;


	/**
	 * @param array       $words
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $locale
	 *
	 * @return array
	 */
	public function collect(array $words, array $placeholders = [], string $group = null, string $locale = null) : array;


	/**
	 * @param array $groups
	 *
	 * @return void
	 */
	public function load(...$groups) : void;
}