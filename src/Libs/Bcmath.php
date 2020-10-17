<?php

namespace Gzhegow\Lang\Libs;

/**
 * Class Bcmath
 */
class Bcmath
{
	/**
	 * @param string $n
	 *
	 * @return bool
	 */
	public function bcabs(string $n)
	{
		return $this->bcnegative($n)
			? strpos($n, 1)
			: $n;
	}

	/**
	 * @param string $n
	 *
	 * @return string
	 */
	public function bcceil(string $n)
	{
		return $this->bcnegative($n)
			? ( ( $v = $this->bcfloor(substr($n, 1)) )
				? "-$v"
				: $v )
			: bcadd(strtok($n, '.'), strtok('.') != 0);
	}

	/**
	 * @param string $n
	 *
	 * @return string
	 */
	public function bcfloor(string $n)
	{
		return $this->bcnegative($n)
			? '-' . $this->bcceil(substr($n, 1))
			: strtok($n, '.');
	}

	/**
	 * @param string $n
	 *
	 * @return bool
	 */
	public function bcnegative(string $n)
	{
		return strpos($n, '-') === 0; // Is the number less than 0?
	}

	/**
	 * @param string $n
	 * @param int    $p
	 *
	 * @return null|string
	 */
	public function bcround(string $n, $p = 0)
	{
		$e = bcpow(10, $p + 1);

		return bcdiv(bcadd(bcmul($n, $e, 0), $this->bcnegative($n)
			? -5
			: 5), $e, $p);
	}
}
