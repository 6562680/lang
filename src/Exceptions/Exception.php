<?php

namespace Gzhegow\Lang\Exceptions;

/**
 * Class Exception
 */
class Exception extends \Exception
{
	/**
	 * @var array
	 */
	protected $payload = [];


	/**
	 * Constructor
	 *
	 * @param string          $message
	 * @param array           $payload
	 * @param \Throwable|null $previous
	 */
	public function __construct($message = "", $payload = [], \Throwable $previous = null)
	{
		$payload = (array) $payload;

		array_walk_recursive($payload, function (&$v) {
			if (! is_scalar($v)) {
				if (is_null($v)) {
					$v = '{ NULL }';
				} elseif (is_resource($v)) {
					$v = '{ Resource #' . intval($v) . ' }';
				} else {
					$v = '{ #' . spl_object_id($v) . ' ' . get_class($v) . ' }';
				}
			}
		});

		$message .= PHP_EOL . print_r($payload, 1);

		parent::__construct($message, -1, $previous);
	}
}