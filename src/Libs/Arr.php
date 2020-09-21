<?php

namespace Gzhegow\Lang\Libs;

use Gzhegow\Lang\Libs\Iterators\TreeIterator;

/**
 * Class Arr
 */
class Arr
{
	const ERROR_MISSING_KEY  = 1;
	const ERROR_NOT_AN_ARRAY = 2;
	const ERROR_NO_ERROR     = 0;

	const PATH_SEPARATOR = '.';


	/**
	 * @param string|array $path
	 * @param array        $src
	 * @param null         $default
	 *
	 * @return mixed
	 */
	public function get($path, array &$src, $default = null) // : mixed
	{
		$result = ( 3 === func_num_args() )
			? $this->ref($path, $src, $default)
			: $this->ref($path, $src);

		return $result;
	}


	/**
	 * @param string|array $path
	 * @param array        $src
	 *
	 * @return bool
	 */
	public function has($path, array &$src) : bool
	{
		$this->traverse($src, $path, $error);

		return ( $error = static::ERROR_NO_ERROR )
			? true
			: false;
	}


	/**
	 * @param array        $src
	 * @param string|array $path
	 * @param mixed        $value
	 *
	 * @return Arr
	 */
	public function set(array &$src, $path, $value) // : mixed
	{
		$this->put($src, $path, $value);

		return $this;
	}


	/**
	 * @param array        $src
	 * @param string|array $path
	 *
	 * @return Arr
	 */
	public function del(array &$src, ...$path)
	{
		$fullpath = $this->path($path);

		$ref =& $src;

		$prev = null;
		$node = null;
		foreach ( $fullpath as &$node ) {
			$prev =& $ref;

			if (! array_key_exists($node, $ref)) {
				unset($prev);
				break;
			}

			$ref = &$ref[ $node ];
		}

		if (1
			&& isset($prev)
			&& ( 0
				|| isset($prev[ $node ])
				|| array_key_exists($node, $prev)
			)
		) {
			unset($prev[ $node ]);
		}
		unset($prev);
		unset($node);

		unset($ref);

		return $this;
	}


	/**
	 * @param string|array $path
	 * @param array        $src
	 * @param null         $default
	 *
	 * @return mixed
	 */
	public function &ref($path, array &$src, $default = null) // : mixed
	{
		$result = $this->traverse($src, $path, $error);

		if (! $error) {
			return $result;
		}

		if (3 === func_num_args()) {
			return $this->put($src, $path, $default);
		}

		throw new \InvalidArgumentException('Path not found');
	}

	/**
	 * @param array        $src
	 * @param string|array $path
	 * @param mixed        $value
	 *
	 * @return mixed
	 */
	public function &put(array &$src, $path, $value) // : mixed
	{
		$fullpath = $this->path($path);
		$last = array_pop($fullpath);

		$valueRef =& $src;

		if ($fullpath) {
			while ( null !== key($fullpath) ) {
				$valueRef =& $valueRef[ current($fullpath) ];

				next($fullpath);
			}
		}

		$valueRef[ $last ] = $value;

		return $valueRef[ $last ];
	}


	/**
	 * @param iterable $iterable
	 *
	 * @return array
	 */
	public function dot(iterable $iterable) : array
	{
		$result = [];

		$generator = $this->walk($iterable, \RecursiveIteratorIterator::SELF_FIRST);

		foreach ( $generator as $fullpath => $value ) {
			if (is_iterable($value)) continue;

			$result[ $this->key($fullpath) ] = $value;
		}

		return $result;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function undot(array $data) : array
	{
		$result = [];

		foreach ( $data as $dot => $value ) {
			$path = $this->path($dot);

			$ref =& $result;
			while ( null !== key($path) ) {
				$ref =& $ref[ current($path) ];

				next($path);
			}
			$ref = $value;

			unset($ref);
		}

		return $result;
	}


	/**
	 * @param array $path
	 *
	 * @return string
	 */
	public function key(...$path) : string
	{
		$result = $this->concat($path);

		$result = implode(static::PATH_SEPARATOR, $result);

		return $result;
	}

	/**
	 * @param mixed ...$path
	 *
	 * @return array
	 */
	public function path(...$path) : array
	{
		$result = $this->concat($path);

		return $result;
	}


	/**
	 * @param iterable $iterable
	 * @param int      $flags
	 *
	 * @return \Generator
	 */
	public function walk(iterable $iterable, int $flags = 0) : \Generator
	{
		$it = new TreeIterator($iterable, $flags);

		foreach ( $it as $fullpath => $val ) {
			yield $fullpath => $val;
		}

		return $this;
	}


	/**
	 * @param array $ref
	 * @param array $path
	 * @param int   $error
	 *
	 * @return mixed
	 */
	protected function &traverse(array &$ref, $path, int &$error = self::ERROR_NO_ERROR) // : mixed
	{
		$p = $this->path($path);

		while ( null !== key($p) ) {
			$key = array_shift($p);

			if (! array_key_exists($key, $ref)) {
				$error = static::ERROR_MISSING_KEY;

				return null;
			}

			if ($p && ! is_array($ref[ $key ])) {
				$error = static::ERROR_MISSING_KEY + static::ERROR_NOT_AN_ARRAY;

				return null;
			}

			$ref =& $ref[ $key ];
		}

		return $ref;
	}


	/**
	 * @param mixed ...$path
	 *
	 * @return array
	 */
	protected function concat(...$path) : array
	{
		$result = [];

		array_walk_recursive($path, function ($step) use (&$result) {
			if (is_int($step)) {
				$result[] = $step;

			} elseif (is_string($step)) {
				$steps = explode(static::PATH_SEPARATOR, $step);

				foreach ( $steps as $step ) {
					if ('' === $step) {
						// step is empty
						// step begins with dot
						// step ends with dot
						// step contains double dot
						throw new \InvalidArgumentException('Step should be not empty');
					}

					$result[] = $step;
				}
			} else {
				throw new \InvalidArgumentException('Step should be string or int');
			}
		});

		return $result;
	}
}
