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
	public $lang;
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
	public $plurals = [];
}