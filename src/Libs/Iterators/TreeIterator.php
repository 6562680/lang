<?php

namespace Gzhegow\Lang\Libs\Iterators;

class TreeIterator implements \Iterator
{
	/**
	 * @var iterable
	 */
	protected $iterable;
	/**
	 * @var int
	 */
	protected $flags;

	/**
	 * @var \Iterator
	 */
	protected $iterator;
	/**
	 * @var array
	 */
	protected $path = [];

	/**
	 * @var array
	 */
	protected $stack = [];
	/**
	 * @var array
	 */
	protected $pathes = [];


	/**
	 * Constructor
	 *
	 * @param iterable $iterable
	 * @param int      $flags
	 */
	public function __construct(iterable $iterable = [], $flags = 0)
	{
		$this->iterable = $iterable;
		$this->flags = $flags;

		$this->rewind();
	}


	/**
	 * @return iterable
	 */
	public function getIterable()
	{
		return $this->iterable;
	}

	/**
	 * @return int
	 */
	public function getFlags() : int
	{
		return $this->flags;
	}


	/**
	 * @return void
	 */
	public function rewind()
	{
		$this->iterator = new \ArrayIterator($this->iterable, $this->flags);
		$this->path = [];

		$this->stack = [];
		$this->pathes = [];
	}


	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->valid()
			? $this->iterator->current()
			: false;
	}

	/**
	 * @return array
	 */
	public function key()
	{
		$fullpath = $this->path ?? [];
		$fullpath[] = $this->iterator->key();

		return $fullpath;
	}


	/**
	 * @return void
	 */
	public function next()
	{
		$val = $this->current();
		$key = $this->key();

		if (is_iterable($val)) {
			$this->stack[] = new \ArrayIterator($val, $this->getFlags());
			$this->pathes[] = $key;
		}

		$this->iterator->next();

		if (! $this->valid()) {
			if (null !== key($this->stack)) {
				$this->iterator = array_shift($this->stack);
				$this->path = array_shift($this->pathes);
			}
		}
	}


	/**
	 * @return bool
	 */
	public function valid()
	{
		return isset($this->iterator) && ( null !== $this->iterator->key() );
	}
}