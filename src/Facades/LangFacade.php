<?php

/**
 * This file is auto-generated.
 *
 * * @noinspection PhpUnhandledExceptionInspection
 * * @noinspection PhpDocMissingThrowsInspection
 */

namespace Gzhegow\Lang\Facades;

use Gzhegow\Di\Di;
use Gzhegow\Lang\Domain\Lang;
use Gzhegow\Lang\Exceptions\Error\WordNotFoundException;
use Gzhegow\Lang\Libs\Arr;
use Gzhegow\Lang\Libs\Php;
use Gzhegow\Lang\Repo\File\FileWordRepo;
use Gzhegow\Lang\Repo\Memory\MemoryWordRepo;

class LangFacade
{
    /**
     * @return string
     */
    public static function getLocale(): string
    {
        return static::getLang()->getLocale();
    }

    /**
     * @return string[]
     */
    public static function getLocales(): array
    {
        return static::getLang()->getLocales();
    }

    /**
     * @param string      $locale
     * @param string|null $localeNumeric
     */
    public static function setLocale(string $locale, string $localeNumeric = null): void
    {
        static::getLang()->setLocale($locale, $localeNumeric);
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
    public static function get(string $aword, array $placeholders = [], string $group = null, string $locale = null): string
    {
        return static::getLang()->get($aword, $placeholders, $group, $locale);
    }

    /**
     * @param string      $word
     * @param array       $placeholders
     * @param string|null $group
     * @param string|null $locale
     *
     * @return null|string
     */
    public static function getOrNull(string $word, array $placeholders = [], string $group = null, string $locale = null): ?string
    {
        return static::getLang()->getOrNull($word, $placeholders, $group, $locale);
    }

    /**
     * @param string      $word
     * @param array       $placeholders
     * @param string|null $group
     * @param string|null $locale
     *
     * @return null|string
     */
    public static function getOrWord(string $word, array $placeholders = [], string $group = null, string $locale = null): ?string
    {
        return static::getLang()->getOrWord($word, $placeholders, $group, $locale);
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
    public static function getOrDefault(string $word, array $placeholders = [], string $default = null, string $group = null, string $locale = null): ?string
    {
        return static::getLang()->getOrDefault($word, $placeholders, $default, $group, $locale);
    }

    /**
     * @param string $word
     * @param array  $placeholders
     *
     * @return string
     */
    public static function interpolate(string $word, array $placeholders): string
    {
        return static::getLang()->interpolate($word, $placeholders);
    }

    /**
     * @param array       $dct
     * @param array       $placeholders
     * @param string|null $group
     * @param string      $locale
     *
     * @return array
     */
    public static function translate(array $dct, array $placeholders = [], string $group = null, string $locale = null): array
    {
        return static::getLang()->translate($dct, $placeholders, $group, $locale);
    }

    /**
     * @param iterable    $iterable
     * @param array       $placeholders
     * @param string|null $group
     * @param string      $locale
     *
     * @return array
     */
    public static function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $locale = null): array
    {
        return static::getLang()->translateMany($iterable, $placeholders, $group, $locale);
    }

    /**
     * @param array $groups
     *
     * @return void
     */
    public static function load(...$groups): void
    {
        static::getLang()->load(...$groups);
    }

    /**
     * @return Lang
     */
    public static function getLang()
    {
        return Di::makeOrFail(Lang::class);
    }
}
