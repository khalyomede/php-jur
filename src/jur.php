<?php

/**
 * Calling reset before all ensure it clean the data before re-making a new response
 */

namespace Khalyomede;

class JUR {
	const REQUEST_GET = 'get';
	const REQUEST_POST = 'post';
	const REQUEST_PUT = 'put';
	const REQUEST_DELETE = 'delete';

	const STATUS_SUCCESS = 'success';
	const STATUS_FAIL = 'fail';
	const STATUS_ERROR = 'error';

	const DEFAULT_REQUEST = '';
	const DEFAULT_REQUESTED = 0;
	const DEFAULT_RESOLVED = 0;
	const DEFAULT_ELAPSED = 0;
	const DEFAULT_STATUS = '';
	const DEFAULT_MESSAGE = '';
	const DEFAULT_CODE = 0;
	const DEFAULT_DATA = null;

	public static $request = self::DEFAULT_REQUEST;
	public static $requested = self::DEFAULT_REQUESTED;
	public static $resolved = self::DEFAULT_RESOLVED;
	public static $elapsed = self::DEFAULT_ELAPSED;
	public static $status = self::DEFAULT_STATUS;
	public static $message = self::DEFAULT_MESSAGE;
	public static $code = self::DEFAULT_CODE;
	public static $data = self::DEFAULT_DATA;

	private static $method = '';
	private static $arguments = [];

	/**
	 * @param string $method
	 * @param string $arguments
	 * @return self
	 * @see http://php.net/manual/fr/language.oop5.overloading.php#object.callstatic
	 */
	public static function __callStatic( $method, $arguments ) {
		self::$method = $method;

		self::checkMethod();

		return new self;
	}

	/**
	 * @param string $method
	 * @param string $arguments
	 * @return $this
	 * @see http://php.net/manual/fr/language.oop5.overloading.php#object.callstatic
	 */
	public function __call( $method, $arguments ) {
		self::$method = $method;

		self::checkMethod();

		return $this;
	}
	
	/**
	 * @param string $message
	 * @return self
	 */
	public static function message( $message ) {
		self::$message = (string) $message;

		return new self;
	}

	/**
	 * @param mixed $data
	 * @return self
	 */
	public static function data( $data ) {
		self::$data = $data;

		return new self;
	}

	/**
	 * @param int $code
	 * @return self
	 */
	public static function code( $code ) {
		self::$code = (int) $code;

		return new self;
	}

	/**
	 * @return mixed[]
	 */
	public static function toArray() {
		return self::buildResponse();
	}

	/**
	 * @return string
	 */
	public static function toJson() {
		return json_encode( self::buildResponse() );
	}

	/**
	 * @return object
	 */
	public function toObject() {
		return json_decode( json_encode( self::buildResponse() ) );
	}

	/**
	 * @return self
	 */
	public static function reset() {
		self::$request = self::DEFAULT_REQUEST;
		self::$requested = self::DEFAULT_REQUESTED;
		self::$resolved = self::DEFAULT_RESOLVED;
		self::$elapsed = self::DEFAULT_ELAPSED;
		self::$status = self::DEFAULT_STATUS;
		self::$message = self::DEFAULT_MESSAGE;
		self::$code = self::DEFAULT_CODE;
		self::$data = self::DEFAULT_DATA;

		return new self;
	}

	/**
	 * @return self
	 */
	public static function requested() {
		self::$requested = self::timestampsMilliseconds();

		return new self;
	}

	/**
	 * @return self
	 */
	public static function resolved() {
		self::$resolved = self::timestampsMilliseconds();
		self::$elapsed = self::$resolved - self::$requested;

		return new self;
	}

	private function checkMethod() {
		if( self::isRequestMethod() ) {
			self::$request = self::methodValue();
		}
		else if( self::isStatusMethod() ) {
			self::$status = self::methodValue();
		}
	}

	private function availableTimestampsMethods() {
		return [ 'requested', 'resolved' ];
	}

	private static function isRequestMethod() {
		return in_array( self::$method, self::availableRequestValues() );
	}

	private static function availableRequestValues() {
		return [ self::REQUEST_GET, self::REQUEST_POST, self::REQUEST_PUT, self::REQUEST_DELETE ];
	}

	private static function isStatusMethod() {
		return in_array( self::$method, self::availableStatusValues() );
	}

	private static function availableStatusValues() {
		return [ self::STATUS_SUCCESS, self::STATUS_FAIL, self::STATUS_ERROR ];
	}

	private static function methodValue() {
		return strtolower( self::$method );
	}	

	private static function buildResponse() {
		return [
			'request' => self::$request,
			'requested' => self::$requested,
			'resolved' => self::$resolved,
			'elapsed' => self::$elapsed,
			'status' => self::$status,
			'message' => self::$message,
			'code' => self::$code,
			'data' => self::$data
		];
	}

	private function timestampsMilliseconds() {
		return round(microtime(true) * 1000);
	}
}