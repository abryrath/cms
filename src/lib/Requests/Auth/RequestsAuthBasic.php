<?php

/**
 * Basic Authentication provider
 *
 * @package Requests
 * @subpackage Authentication
 */

/**
 * Basic Authentication provider
 *
 * Provides a handler for Basic HTTP authentication via the Authorization
 * header.
 *
 * @package Requests
 * @subpackage Authentication
 */
class RequestsAuthBasic implements RequestsAuth {
	/**
	 * Username
	 *
	 * @var string
	 */
	public $user;

	/**
	 * Password
	 *
	 * @var string
	 */
	public $pass;

	/**
	 * Constructor
	 *
	 * @throws RequestsException On incorrect number of arguments (`authbasicbadargs`)
	 * @param array|null $args Array of user and password. Must have exactly two elements
	 */
	public function __construct($args = null) {
		if (is_array($args)) {
			if (count($args) !== 2) {
				throw new RequestsException('Invalid number of arguments', 'authbasicbadargs');
			}

			list($this->user, $this->pass) = $args;
		}
	}

	/**
	 * Register the necessary callbacks
	 *
	 * @see curl_before_send
	 * @see fsockopen_header
	 * @param RequestsHooks $hooks Hook system
	 */
	public function register(RequestsHooks &$hooks) {
		$hooks->register('curl.before_send', array(&$this, 'curl_before_send'));
		$hooks->register('fsockopen.after_headers', array(&$this, 'fsockopen_header'));
	}

	/**
	 * Set cURL parameters before the data is sent
	 *
	 * @param resource $handle cURL resource
	 */
	public function curl_before_send(&$handle) {
		curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($handle, CURLOPT_USERPWD, $this->getAuthString());
	}

	/**
	 * Add extra headers to the request before sending
	 *
	 * @param string $out HTTP header string
	 */
	public function fsockopen_header(&$out) {
		$out .= "Authorization: Basic " . base64_encode($this->getAuthString()) . "\r\n";
	}

	/**
	 * Get the authentication string (user:pass)
	 *
	 * @return string
	 */
	public function getAuthString() {
		return $this->user . ':' . $this->pass;
	}
}
