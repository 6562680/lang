<?php

namespace Gzhegow\Lang\Models;

use Gzhegow\Support\Php;

/**
 * Class AbstractModel
 */
abstract class AbstractModel
{
	/**
	 * @var Php
	 */
	protected $php;


	/**
	 * Constructor
	 *
	 * @param Php   $php
	 * @param mixed ...$args
	 */
	public function __construct(Php $php, ...$args)
	{
		$this->php = $php;

		[ $kwargs ] = $this->php->kwargs(...$args);

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