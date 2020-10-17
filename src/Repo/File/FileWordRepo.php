<?php

namespace Gzhegow\Lang\Repo\File;

use Gzhegow\Lang\Libs\Str;
use Gzhegow\Lang\Models\WordModel;
use Gzhegow\Lang\Repo\WordRepoInterface;
use Gzhegow\Lang\Exceptions\Logic\InvalidArgumentException;

/**
 * Class FileWordRepo
 */
class FileWordRepo implements WordRepoInterface
{
	/**
	 * @var string
	 */
	public $path;


	/**
	 * Constructor
	 *
	 * @param string $path
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $path)
	{
		if (! is_dir($path)) {
			throw new InvalidArgumentException('Dir not found: ');
		}

		$this->path = $path;
	}


	/**
	 * @param string      $group
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroup(string $group, string $locale = null) : array
	{
		$result = [];

		$str = new Str();

		foreach ( scandir($this->path) as $loc ) {
			if ('.' === $loc) continue;
			if ('..' === $loc) continue;

			if (! is_dir($dir = $this->path . '/' . $loc)) {
				continue;
			}

			if ($locale && ( $loc !== $locale )) {
				continue;
			}

			$words = require $this->path . '/' . $loc . '/' . sprintf('%s.php', $group);

			foreach ( $words as $key => $phrase ) {
				$row = [
					'key'    => $str->prepend($key, $group . '.'),
					'locale' => $loc,
					'group'  => $group,
					'words'  => (array) $phrase,
				];

				$result[] = $this->mapToModel($row);
			}
		}

		return $result;
	}

	/**
	 * @param array       $groups
	 * @param string|null $locale
	 *
	 * @return WordModel[]
	 */
	public function getByGroups(array $groups, string $locale = null) : array
	{
		$result = [];

		foreach ( $groups as $group ) {
			$result = array_merge($result, $this->getByGroup($group, $locale));
		}

		return $result;
	}


	/**
	 * @param array $record
	 *
	 * @return WordModel
	 */
	protected function mapToModel($record) : WordModel
	{
		return new WordModel($record);
	}
}
