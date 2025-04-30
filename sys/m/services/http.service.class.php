<?php
namespace Platypus\Service;

use Platypus\Service\BaseService;

class HTTPService extends BaseService {
	public ?\stdClass $httpr = null;
	
	public function __construct(int $error_code) {
		parent::__construct();
		
		$httpr = self::get_http_response($error_code);
		
		self::set_http_header($httpr);
		$this->httpr = $httpr;
	}
	
	public static function set_http_header(\stdClass $httpr) {
		header($httpr->protocol . " " . $httpr->code . " " . $httpr->message);
	}
	
	public static function set_404_header() {
		$httpr = self::get_http_response(404);
		self::set_http_header($httpr);
	}
	
	public static function json_response(
		$response, 
		bool $include_httpr = false, 
		int $httpr_code = 0
	): bool|string {
		$tmp = [];
		
		if(!empty($response)) {
			$tmp["response"] = $response;
		}
		
		return json_encode($tmp);
	}
	
	public static function get_http_response(?int $error_code = null): \stdClass {
		$httpr = new \stdClass();
		$httpr->icon = "sad.svg";
		
		$httpr->code = http_response_code();
		
		if(!empty($error_code)) {
			$httpr->code = $error_code;
		}
		
		switch($httpr->code) {
			case 100:
				$httpr->message = 'Continue';
				break;
			case 101:
				$httpr->message = 'Switching Protocols';
				break;
			case 203:
				$httpr->message = 'Non-Authoritative Information';
				break;
			case 204:
				$httpr->message = 'No Content';
				break;
			case 205:
				$httpr->message = 'Reset Content';
				break;
			case 206:
				$httpr->message = 'Partial Content';
				break;
			case 300:
				$httpr->message = 'Multiple Choices';
				break;
			case 301:
				$httpr->message = 'Moved Permanently';
				break;
			case 302:
				$httpr->message = 'Moved Temporarily';
				break;
			case 303:
				$httpr->message = 'See Other';
				break;
			case 304:
				$httpr->message = 'Not Modified';
				break;
			case 305:
				$httpr->message = 'Use Proxy';
				break;
			case 400:
				$httpr->message = 'Bad Request';
				break;
			case 401:
				$httpr->message = 'Unauthorized';
				break;
			case 402:
				$httpr->message = 'Payment Required';
				break;
			case 403:
				$httpr->message = 'Forbidden';
				break;
			case 404:
				$httpr->message = 'Not Found';
				break;
			case 405:
				$httpr->message = 'Method Not Allowed';
				break;
			case 406:
				$httpr->message = 'Not Acceptable';
				break;
			case 407:
				$httpr->message = 'Proxy Authentication Required';
				break;
			case 408:
				$httpr->message = 'Request Time-out';
				break;
			case 409:
				$httpr->message = 'Conflict';
				break;
			case 410:
				$httpr->message = 'Gone';
				break;
			case 411:
				$httpr->message = 'Length Required';
				break;
			case 412:
				$httpr->message = 'Precondition Failed';
				break;
			case 413:
				$httpr->message = 'Request Entity Too Large';
				break;
			case 414:
				$httpr->message = 'Request-URI Too Large';
				break;
			case 415:
				$httpr->message = 'Unsupported Media Type';
				break;
			case 500:
				$httpr->message = 'Internal Server Error';
				break;
			case 501:
				$httpr->message = 'Not Implemented';
				break;
			case 502:
				$httpr->message = 'Bad Gateway';
				break;
			case 503:
				$httpr->message = 'Service Unavailable';
				break;
			case 504:
				$httpr->message = 'Gateway Time-out';
				break;
			case 505:
				$httpr->message = 'HTTP Version not supported';
				break;
			case 600:
				$httpr->message = 'Template not found';
				break;
			default:
				return self::get_http_response(400);
		}
		
		$httpr->protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
		
		header($httpr->protocol . ' ' . $httpr->code . ' ' . $httpr->message);
		
		$GLOBALS['http_response_code'] = $httpr->code;
		
		return $httpr;
	}
}