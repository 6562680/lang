<?php

namespace Gzhegow\Lang\Libs;

/**
 * Class Php
 */
class Php
{
	/**
	 * @param mixed ...$arguments
	 *
	 * @return array
	 */
	public function kwargs(...$arguments) : array
	{
		$kwargs = [];
		$args = [];
		foreach ( $arguments as $argument ) {
			foreach ( (array) $argument as $key => $val ) {
				if (! is_int($key)) {
					$kwargs[ $key ] = $val;
				} else {
					$args[] = $val;
				}
			}
		}

		return [ $kwargs, $args ];
	}
}
