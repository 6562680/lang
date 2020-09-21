<?php

namespace Gzhegow\Lang\Libs;

/**
 * Class Str
 */
class Str
{
	/**
	 * @param string $haystack
	 * @param string $prepend
	 *
	 * @return string
	 */
	public function prepend(string $haystack, string $prepend) : string
	{
		return ( 0 === mb_stripos($haystack, $prepend) )
			? $haystack
			: $prepend . $haystack;
	}
}
