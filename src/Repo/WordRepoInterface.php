<?php

namespace Gzhegow\Lang\Repo;

use Gzhegow\Lang\Models\WordModel;

/**
 * Interface WordRepoInterface
 */
interface WordRepoInterface
{
	/**
	 * @param string      $group
	 * @param string|null $lang
	 *
	 * @return WordModel[]
	 */
	public function getByGroup(string $group, string $lang = null) : array;

	/**
	 * @param array       $groups
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroups(array $groups, string $locale = null) : array;
}