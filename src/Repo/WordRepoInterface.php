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
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroup(string $group, string $locale = null) : array;

	/**
	 * @param array       $groups
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroups(array $groups, string $locale = null) : array;
}