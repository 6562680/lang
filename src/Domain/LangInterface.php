<?php

namespace Gzhegow\Lang\Domain;


use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Logic\Error\LanguageNotFoundError;

/**
 * Class Lang
 */
interface LangInterface
{
	/**
	 * @param array $groups
	 *
	 * @return Lang
	 */
	public function load(...$groups);

	/**
	 * @return string[]
	 */
	public function getLanguages() : array;

	/**
	 * @return string
	 */
	public function getLang() : string;

	/**
	 * @return string
	 */
	public function getLangDefault() : string;

	/**
	 * @param string $lang
	 *
	 * @return array
	 */
	public function getLanguageFor(string $lang) : array;

	/**
	 * @return array
	 */
	public function getLanguage() : array;

	/**
	 * @return array
	 */
	public function getLanguageDefault() : array;

	/**
	 * @param string $lang
	 *
	 * @return string
	 */
	public function getLocaleFor(string $lang) : string;

	/**
	 * @return string
	 */
	public function getLocale() : string;

	/**
	 * @return string
	 */
	public function getLocaleDefault() : string;

	/**
	 * @param string      $lang
	 * @param string|null $localeNumeric
	 *
	 * @return Lang
	 * @throws LanguageNotFoundError
	 */
	public function setLang(string $lang, string $localeNumeric = null);

	/**
	 * @param string $lang
	 *
	 * @return Lang
	 * @throws LanguageNotFoundError
	 */
	public function setLangDefault(string $lang);

	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @param string|null $word
	 *
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function get(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : string;

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
	public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string;

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
	public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string;

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
	public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string;

	/**
	 * @param string      $aword
	 * @param string      $lang
	 * @param string|null $group
	 * @param string|null $word
	 * @param string      $result
	 *
	 * @return bool
	 */
	public function has(string $aword, string $lang, string $group = null, string &$word = null, string &$result = null) : bool;

	/**
	 * @param string|null $lang
	 * @param string|null $url
	 * @param array       $q
	 * @param string|null $ref
	 *
	 * @return string
	 */
	public function languagePath(string $lang = null, string $url = null, array $q = null, string $ref = null) : string;

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
	public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : string;

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
	public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string;

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
	public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string;

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
	public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string;

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
	 * @param string      $lang
	 *
	 * @return array
	 */
	public function translate(array $dct, array $placeholders = [], string $group = null, string $lang = null) : array;

	/**
	 * @param iterable    $iterable
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string      $lang
	 *
	 * @return array
	 */
	public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $lang = null) : array;

	/**
	 * @param array       $words
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @return array
	 */
	public function collect(array $words, array $placeholders = [], string $group = null, string $lang = null) : array;
}