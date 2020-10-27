<?php

namespace Gzhegow\Lang\Models;

use Gzhegow\Support\Php;

/**
 * Class ModelFactory
 */
class ModelFactory
{
	/**
	 * @var Php
	 */
	protected $php;


	/**
	 * Constructor
	 *
	 * @param Php $php
	 */
	public function __construct(Php $php)
	{
		$this->php = $php;
	}


	/**
	 * @param mixed ...$args
	 *
	 * @return WordModel
	 */
	public function newWordModel(...$args) : WordModel
	{
		return new WordModel($this->php, ...$args);
	}
}