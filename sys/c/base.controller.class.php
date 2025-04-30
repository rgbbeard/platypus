<?php
namespace Platypus\Controller;

require_once "base.controller.interface.php";

use \stdClass;

class BaseController implements BaseControllerInterface {
	protected array $children = [];

	public function __construct() {
		$this->list_child_classes();		
	}

	public function list_child_classes() {
	    foreach (get_declared_classes() as $class) {
	        if (is_subclass_of($class, __CLASS__)) {
	            $this->children[] = $class;
	        }
	    }
	}

	public function find_owner_of(string $method): ?string {
        if(!empty($this->children)) {
        	foreach($this->children as $child) {
        		if(method_exists($child, $method)) {
        			return $child;
        		}
        	}
        }
        
        return null;
    }
	
	# A POST request has been sent
	public static function has_post_data(): bool {
		return $_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST);
	}

	# A GET request has been sent
	public static function has_get_data(): bool {
		return $_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET);
	}

	public static function get_post_data($index = null) {
		$params = self::convert_params($_POST);

		if(!is_null($index)) {
			return $params[$index];
		}

		return $params;
	}

	public static function get_get_data($index = null) {
		$params = self::convert_params($_GET);

		if(!is_null($index)) {
			return $params[$index];
		}

		return $params;
	}

	/**
	 * request parameters' values are sent
	 * without their associated name
	 */
	protected static function convert_params(array $params): array {
		$tmp = [];

		foreach($params as $name => $value) {
			$tmp[$name] = $value;
		}

		return $tmp;
	}

	public function render_http_response(stdClass $response) {

	}
}