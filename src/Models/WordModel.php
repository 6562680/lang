<?php

namespace Gzhegow\Lang\Models;

/**
 * Class WordModel
 */
class WordModel extends AbstractModel
{
	/**
	 * @var string
	 */
	public $locale;
	/**
	 * @var string
	 */
	public $group;
	/**
	 * @var string
	 */
	public $key;

	/**
	 * @var string[]
	 */
	public $words = [];
}