<?php
namespace Platypus\Routing;

require_once __DIR__ . "/../c/base.controller.class.php";
require_once __DIR__ . "/converter.class.php";

use Platypus\Controller\BaseController;
use Platypus\Utilities\Converter;

class BaseRouter {
	private const routing_original_config = __DIR__ . "/../config/routes.ini";
	private const routing_cache_config = __DIR__ . "/../config/routes.cached";

	protected BaseController $baseController;

	public function __construct() {
		$this->baseController = new BaseController();

		if($this->routes_need_recaching()) {
			if(!$this->recache_routes()) {
				die("Unable to cache routes");
			}
		}
	}

	protected function parse_config(string $target): array|false {
	    if(!file_exists($target)) {
	        return false;
	    }

	    $inidata = parse_ini_file($target, true, INI_SCANNER_TYPED);

	    if($inidata) {
	    	foreach($inidata as $index => &$values) {
	    		$controller = $this->baseController->find_owner_of($values["function"]);

	    		if(!is_null($controller)) {
	    			$values["controller"] = $controller;
	    		}
	    	}
	    }

	    return $inidata;
	}

	protected function get_routes(): array|false {
		return parse_ini_file(
			self::routing_cache_config,
			true,
			INI_SCANNER_TYPED
		);
	}

	private function routes_need_recaching(): bool {
		if(!file_exists(self::routing_cache_config)) {
			return true;
		}

		return filesize(self::routing_original_config) 
			!= filesize(self::routing_cache_config);
	}

	private function recache_routes(): bool {
		try {
			$data = $this->parse_config(self::routing_original_config);

			if(!$data) {
				return false;
			}

			$data = Converter::array2ini($data);
			$data = trim($data);

			return file_put_contents(self::routing_cache_config, $data) !== false;
		} catch(Exception $e) {
			return false;
		}
	}
}