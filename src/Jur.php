<?php
	namespace Khalyomede;

	use InvalidArgumentException;
	use ParseError;
	use StdClass;

	/**
	 * Returns a JSON Uniform Response (JUR) according to your setup.
	 * 
	 * @example
	 * require(__DIR__ . '/vendor/autoload.php');
	 * 
	 * $response = jur()->request('get')->data("hello world")->resolved()->toJson();
	 * // or
	 * $response = jur()->issued()->request('get')->data("hello world")->resolved()->toJson();
	 */
	class Jur {
		/**
		 * GET request.
		 * 
		 * @var string
		 */
		const REQUEST_GET = 'get';

		/**
		 * POST request.
		 * 
		 * @var string
		 */
		const REQUEST_POST = 'post';

		/**
		 * PUT request.
		 * 
		 * @var string
		 */
		const REQUEST_PUT = 'put';

		/**
		 * PATCH request.
		 * 
		 * @var string
		 */
		const REQUEST_PATCH = 'patch';

		/**
		 * DELETE request.
		 * 
		 * @var string
		 */
		const REQUEST_DELETE = 'delete';

		/**
		 * Stores the microtime for the begining of the process.
		 * 
		 * @var int
		 */
		protected $issued_at;

		/**
		 * Stores the microtime for the end of the process.
		 * 
		 * @var int
		 */
		protected $resolved_at;

		/**
		 * Stores the elapsed time for processing the request.
		 * 
		 * @var int
		 */
		protected $elapsed;

		/**
		 * Stores the message.
		 * 
		 * @var string
		 */
		protected $message;

		/**
		 * Stores the data.
		 * 
		 * @var mixed
		 */
		protected $data;

		/**
		 * Stores the type of request.
		 * 
		 * @var string
		 */
		protected $request;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->issued_at = $this->timestampInMicroseconds();
			$this->resolved_at = null;
			$this->elapsed = 0;
			$this->message = null;
			$this->data = null;
			$this->request = null;
		}

		/**
		 * Return the timestamps in microseconds.
		 * 
		 * @return int
		 */
		private function timestampInMicroseconds(): float {
			$timestamp = explode(' ', microtime());

			return ($timestamp[1] * 1000000) + ($timestamp[0] * 1000000);
		}

		/**
		 * Set the start of the process.
		 * Note that you do not have to necessarily use this method, the constructor does it for you.
		 * 
		 * @return Khalyomede\Jur
		 */
		public function issued(): Jur {
			$this->issued_at = $this->timestampInMicroseconds();
			
			return $this;
		}

		/**
		 * Set the type of request.
		 * 
		 * @param string	$type	The type of request.
		 * @return Khalyomede\Jur
		 * @throws InvalidArgumentException If the type of request is not supported.
		 * @see static::REQUEST_*
		 */
		public function request(string $type): Jur {
			if( in_array($type, static::supportedTypes()) === false ) {
				throw new InvalidArgumentException('type not supported (use either ' . implode(', ', static::supportedTypes()) . ')');
			}

			$this->request = $type;

			return $this;
		}

		/**
		 * Stores the data.
		 * 
		 * @param mixed	$data	The data to store.
		 * @return Khalyomede\Jur
		 */
		public function data($data): Jur {
			$this->data = $data;

			return $this;
		}

		/**
		 * Set the time when the request have been processed successfuly.
		 * 
		 * @return Khalyomede\Jur
		 */
		public function resolved(): Jur {
			$this->resolved_at = $this->timestampInMicroseconds();

			return $this;
		}

		/**
		 * Return the JUR response in the form of a JSON string.
		 * 
		 * @return string
		 * @throws ParseError If the json encoding have failed.
		 */
		public function toJson(): string {
			$json = json_encode($this->arrayResponse());

			if( $json === false ) {
				throw new ParseError( json_last_error_msg() );
			}

			return $json;
		}

		/**
		 * Return the JUR response in the form of an array.
		 * 
		 * @return array
		 */
		public function toArray(): array {
			return $this->arrayResponse();
		}

		/**
		 * Return the JUR response in the form of an object.
		 * 
		 * @return object
		 */
		public function toObject(): object {
			return $this->objectResponse();
		}

		/**
		 * Stores the message.
		 * 
		 * @param string	$message	The message to store.
		 * @return Khalyomede\Jur
		 */
		public function message(string $message): Jur {
			$this->message = $message;
			
			return $this;
		}

		/**
		 * Get the response in the form of an array.
		 * 
		 * @return array
		 */
		private function arrayResponse(): array {
			$this->fillMissingFields();
			$this->computeFields();

			return [
				'message' => $this->message,
				'request' => $this->request,
				'data' => $this->data,
				'debug' => [
					'elapsed' => $this->elapsed,
					'issued_at' => $this->issued_at,
					'resolved_at' => $this->resolved_at
				]
			];
		}

		/**
		 * Get the response in the form of an object.
		 * 
		 * @return object
		 */
		private function objectResponse(): object {
			$this->fillMissingFields();
			$this->computeFields();

			$debug = new StdClass;
			$debug->elapsed = $this->elapsed;
			$debug->issued_at = $this->issued_at;
			$debug->resolved_at = $this->resolved_at;
			
			$response = new StdClass;
			$response->message = $this->message;
			$response->request = $this->request;
			$response->data = $this->data;
			$response->debug = $debug;
			
			return $response;
		}

		/**
		 * Returns the supported request types.
		 * 
		 * @return array<string>
		 */
		private static function supportedTypes(): array {
			return [
				static::REQUEST_GET,
				static::REQUEST_POST,
				static::REQUEST_PUT,
				static::REQUEST_PATCH,
				static::REQUEST_DELETE
			];
		}

		/**
		 * FIll the fields: resolved_at, if not filled yet.
		 * 
		 * @return void
		 */
		private function fillMissingFields(): void {
			if( is_null($this->resolved_at) === true ) {
				$this->resolved_at = $this->timestampInMicroseconds();
			}
		}

		/**
		 * Compute necessary fields, like elapsed.
		 * 
		 * @return void
		 */
		private function computeFields() {
			$this->elapsed = $this->resolved_at - $this->issued_at;
		}
	}
?>