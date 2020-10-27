<?php

namespace Gzhegow\Domain\Lang;

use Gzhegow\Lang\Domain\Lang;
use Gzhegow\Lang\Domain\LangInterface;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundError;
use Gzhegow\Lang\Exceptions\Logic\Error\LanguageNotFoundError;

/**
 * Class LangService
 */
class LangService implements LangInterface
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
		return $this->lang = $lang;
	}


	/**
	 * @param mixed ...$groups
	 *
	 * @return Lang
	 */
	public function load(...$groups)
	{
		return $this->lang->load(...$groups);
	}


	/**
	 * @return array
	 */
	public function getLanguages() : array
	{
		return $this->lang->getLanguages();
	}


	/**
	 * @return string
	 */
	public function getLang() : string
	{
		return $this->lang->getLang();
	}


	/**
	 * @return string
	 */
	public function getLangDefault() : string
	{
		return $this->lang->getLangDefault();
	}


	/**
	 * @param string $lang
	 *
	 * @return array
	 */
	public function getLanguageFor(string $lang) : array
	{
		return $this->lang->getLanguageFor($lang);
	}


	/**
	 * @return array
	 */
	public function getLanguage() : array
	{
		return $this->lang->getLanguage();
	}


	/**
	 * @return array
	 */
	public function getLanguageDefault() : array
	{
		return $this->lang->getLanguageDefault();
	}


	/**
	 * @param string $lang
	 *
	 * @return string
	 */
	public function getLocaleFor(string $lang) : string
	{
		return $this->lang->getLocaleFor($lang);
	}


	/**
	 * @return string
	 */
	public function getLocale() : string
	{
		return $this->lang->getLocale();
	}


	/**
	 * @return string
	 */
	public function getLocaleDefault() : string
	{
		return $this->lang->getLocaleDefault();
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
		return $this->lang->setLang($lang, $localeNumeric);
	}


	/**
	 * @param string $lang
	 *
	 * @return Lang
	 * @throws LanguageNotFoundError
	 */
	public function setLangDefault(string $lang)
	{
		return $this->lang->setLangDefault($lang);
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return string
	 * @throws WordNotFoundError
	 */
	public function get(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : string
	{
		return $this->lang->get($aword, $placeholders = [], $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->getOrNull($aword, $placeholders = [], $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->getOrWord($aword, $placeholders = [], $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->getOrDefault($aword, $placeholders = [], $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param string      $lang
	 * @param string|null $group
	 * @param string|null $word
	 * @param string|null $result
	 *
	 * @return bool
	 */
	public function has(string $aword, string $lang, string $group = null, string &$word = null, string &$result = null) : bool
	{
		return $this->lang->has($aword, $lang, $group, $lang, $word);
	}


	/**
	 * @param string|null $lang
	 * @param string|null $url
	 * @param array|null  $q
	 * @param string|null $ref
	 *
	 * @return string
	 */
	public function languagePath(string $lang = null, string $url = null, array $q = null, string $ref = null) : string
	{
		return $this->lang->languagePath($lang, $url, $q, $ref);
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
		return $this->lang->choice($aword, $number, $placeholders, $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->choiceOrNull($aword, $number, $placeholders, $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->choiceOrWord($aword, $number, $placeholders, $group, $lang, $word);
	}


	/**
	 * @param string      $aword
	 * @param string      $number
	 * @param array       $placeholders
	 * @param string|null $default
	 * @param string|null $group
	 * @param string|null $lang
	 * @param string|null $word
	 *
	 * @return null|string
	 */
	public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $lang = null, string &$word = null) : ?string
	{
		return $this->lang->choiceOrDefault($aword, $number, $placeholders, $group, $lang, $word);
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
	 * @param string|null $lang
	 *
	 * @return array
	 */
	public function translate(array $dct, array $placeholders = [], string $group = null, string $lang = null) : array
	{
		return $this->lang->translate($dct, $placeholders, $group, $lang);
	}


	/**
	 * @param iterable    $iterable
	 * @param array       $placeholders
	 * @param string|null $group
	 * @param string|null $lang
	 *
	 * @return array
	 */
	public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $lang = null) : array
	{
		return $this->lang->translateMany($iterable, $placeholders, $group, $lang);
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
		return $this->lang->collect($words, $placeholders, $group, $lang);
	}
}