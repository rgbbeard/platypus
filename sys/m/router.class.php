<?php
namespace Platypus\Routing;

require_once "base.router.class.php";
require_once "services/http.service.class.php";

use Platypus\Service\HTTPService;

class Router extends BaseRouter {
	public string $current_route = "";
	public array|false $routes = [];

	public function __construct() {
		parent::__construct();

		$this->routes = parent::get_routes();

		if(!$this->routes) {
			die("Invalid routing configuration");
		}

		$params = $this->baseController->has_get_data() ? 
			$this->baseController->get_get_data() :
			(
				$this->baseController->has_post_data() ?
					$this->baseController->get_post_data() :
					[]
			);

		$this->watch(self::get_urlc(), $params);
	}

	private static function get_urlc(): ?array {
		$path = preg_replace("/.*\.php/", "", $_SERVER['REQUEST_URI']);
		$path = preg_replace("/\/$/", "", $path);
		return explode('/', ltrim($path, "/"));
	}

	private function extract_url_params(
		string $route_name, 
		?string $url = null
	): array {
	    # parse the url
	    $path = parse_url($url ?? $_SERVER["REQUEST_URI"], PHP_URL_PATH);
	    $path_segments = explode("/", trim($path, "/"));

	    # remove localhost
	    if(defined("localhost_base")) {
	    	$path_segments = array_exclude($path_segments, 0);
	    }

	    foreach($this->routes as $name => $route) {
	        if($name === $route_name) {
	        	if(str_contains($route["path"], "/")) {
	        		foreach(explode("/", trim($route["path"], "/")) as $rp) {
	        			foreach($path_segments as $index => $ps) {
	        				if($ps == $rp) {
							    $path_segments = array_exclude(
							    	$path_segments, 
							    	$index
							    );
				    		}
	        			}
	        		}
	        	} elseif($path_segments[0] == $route["path"]) {
				    $path_segments = array_exclude($path_segments, 0);
	    		}

	    		$tmp = [];

	    		foreach($path_segments as $parameter) {
	    			foreach($route["params"] as $name => $p) {
	    				if($match = sscanf($parameter, $p)) {
	    					$tmp[$name] = $match[0];
	    				}
	    			}
	    		}

	    		return $tmp;
	        }
	    }

	    return [];
	}

	public function get_current_route(): string {
		return $this->current_route;
	}

	public function watch($path, array $params) {
		$match = false;

		if(defined("localhost_base") && !empty(localhost_base)) {
			array_shift($path);
		}

		$path = implode("/", $path);

		try {
			$name = "home";
			$route = $this->routes[$name];

			# no specific path given, going home
			if(!empty($path)) {
				foreach($this->routes as $n => $r) {
					$regex = str_replace("/", "\/", $r["path"]);

					if(preg_match("/^$regex/", $path)) {
						if(empty($r["path"])) {
							continue;
						}

						$path_diff = str_replace($r["path"], "", $path);
						$path_diff = preg_replace("/^\//", "", $path_diff);
						$path_params = array_clear(explode("/", $path_diff));

						if($r["match_type"] === "regex") {
							if(count($path_params) === count($r["params"])) {
								$name = $n;
								$route = $r;
								break;
							}
						} else {
							if(empty($path_diff)) {
								$name = $n;
								$route = $r;
								break;
							}
						}
					}
				}
			}

			if(!empty($name) && !empty($route)) {
				$match = true;
				
				if($route["match_type"] === "regex") {
					$params = $this->extract_url_params($name);
				}

				$this->current_route = $name;
				$this->goto($route, $params);
			} else {
				die("No route found, going 404..");
			}
		} catch(Exception $ignore) {}

		if(!$match && http_response_code() !== 400) {
			# no route found
			HTTPService::set_404_header();
		}
		
		# catch http response
		if(http_response_code() !== 200) {
			$httpr = new HTTPService(http_response_code());
			# Display server error page
			$this->baseController->render_http_response($httpr->get_http_response());
		}
	}

	public function goto(
		array $route,
		?array $params
	) {
		# TODO: call method of the correct controller
		dd($route, $params);
	}
}