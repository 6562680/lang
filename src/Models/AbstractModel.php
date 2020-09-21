<?php

namespace Gzhegow\Lang\Models;

use Gzhegow\Lang\Libs\Php;

/**
 * Class AbstractEloquentModel
 */
abstract class AbstractModel
{
	/**
	 * Constructor
	 *
	 * @param mixed ...$args
	 */
	public function __construct(...$args)
	{
		$php = new Php();

		[ $kwargs ] = $php->kwargs(...$args);

		foreach ( $kwargs as $key => $val ) {
			if (property_exists($this, $key)) {
				$this->{$key} = $val;
			}
		}
	}


	/**
	 * @return array
	 */
	public function toDict() : array
	{
		return get_object_vars($this);
	}
}