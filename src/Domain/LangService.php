<?php

namespace Gzhegow\Lang\Domain;

use Gzhegow\Lang\Exceptions\Error\WordNotFoundException;

class LangService
{
	/**
	 * @var Lang
	 */
	protected $lang;


	/**
	 * Constructor
	 *
	 * @param Lang $lang
	 */
	public function __construct(Lang $lang)
	{
		$this->lang = $lang;
	}


	/**
	 * @return string
	 */
	public function getLocale() : string
	{
		return $this->lang->getLocale();
	}

	/**
	 * @return string[]
	 */
	public function getLocales() : array
	{
		return $this->lang->getLocales();
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
		return $this->lang->get($aword, $placeholders, $group, $locale);
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
		return $this->lang->getOrNull($word, $placeholders, $group, $locale);
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
		return $this->lang->getOrWord($word, $placeholders, $group, $locale);
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
		return $this->lang->getOrDefault($word, $placeholders, $default, $group, $locale);
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
	 * @param array $groups
	 *
	 * @return void
	 */
	public function load(...$groups) : void
	{
		$this->lang->load($groups);
	}
}
