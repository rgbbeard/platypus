<?php 
namespace Platypus\Exceptions;

use \Exception;

class InvalidSessionException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class ControllerNotFoundException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class RouteNotFoundException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class TemplateNotFoundException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class ApiNotFoundException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class InvalidApiConfigException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}

class DecryptionErrorException extends Exception {
	public function __construct(string $message) {
		parent::__construct($message);
	}
}