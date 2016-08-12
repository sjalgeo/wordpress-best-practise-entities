<?php

namespace FreshAmazonClient\Client;

use FreshAmazonClient\Client\Client;

Class WordpressClient implements Client {

	public function get($request_uri)
	{
		$this->api_response = wp_remote_request($request_uri);
		return $this->api_response;
	}
}