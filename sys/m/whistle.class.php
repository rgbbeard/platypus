<?php
namespace Platypus\Utilities;

use \CurlHandle;

class Whistle {
	protected ?CurlHandle $handle = null;
	protected mixed $result = null;
	protected int $response_code = 0;
	protected array $params = [];
	protected array $options = [];
	
	public function __construct(
		string $url,
		array $params = [],
		array $options = []
	) {
		$this->handle = curl_init();
		
		if($this->handle) {
			curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->handle, CURLOPT_HTTPGET, 1);
			
			$this->set_direction($url);
			$this->set_score($params, $options);
		}
	}
	
	public function __destruct() {
		if($this->handle) {
			$this->clear_settings();
			
			curl_close($this->handle);
		}
	}
	
	public function set_score(array $params = [], array $options) {
	    $this->params = $params;
	    $this->options = $options;

	    $headers = [];

	    if (!empty($options["json_encode"])) {
	        $params = json_encode($params);
	    }

	    if (!empty($options["headers"])) {
	        foreach ($options["headers"] as $header => $value) {
	            $headers[] = "$header: $value";
	        }
	    }

	    curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);

	    if (!empty($params)) {
	        curl_setopt($this->handle, CURLOPT_POSTFIELDS, $params);
	    }

	    if (!empty($options["method"]) && $options["method"] === CURLOPT_POST) {
	        curl_setopt($this->handle, CURLOPT_POST, 1);
	    }
	}
	
	public function set_direction(string $url) {
		curl_setopt($this->handle, CURLOPT_URL, $url);
	}
	
	public function play() {
		$this->result = curl_exec($this->handle);
		
		if($e = curl_errno($this->handle)) {
			$m = curl_error($this->handle);
			dd($e, $m);
		}
	}
	
	public function listen() {
		return $this->result;
	}

	public function play_and_listen() {
		$this->play();

		return $this->listen();
	}
	
	public function show_settings() {
		$settings = curl_getinfo($this->handle);
		
		$settings["whistle_score"]["params"] = $this->params;
		$settings["whistle_score"]["options"] = $this->options;
		
		return $settings;
	}
	
	public function clear_settings() {
		$this->result = null;
		$this->params = [];
		$this->options = [];
	}
}