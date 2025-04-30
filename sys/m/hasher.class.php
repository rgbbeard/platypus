<?php
namespace Platypus\Utilities;

use \Exception;

class Hasher {
	# reference: https://www.php.net/manual/en/function.openssl-get-cipher-methods.php
	private const aes_encryption_method = "aes-256-cbc";
	private const aes_iv = "ZauP7wr5mkm0IxUaVpOdEQ==";
	private const aes_key = "WuvjwMzUF6Aa7XU";
	
	public function __construct() {}
	
	public static function hash($raw_txt): string {
		$salt = "platypus";
		$saltc = str_split($salt);
		$startc = str_split($raw_txt);
		$hash = array();
		
		for($x = 0;$x < count($startc);$x++) {
			$hash[] = @$startc[$x] . @$saltc[$x];
		}
		
		$hash = implode("", $hash);
		
		return hash("sha256", $hash);
	}
	
	public static function aes_encrypt(string $target): string {
		try {
			$target = openssl_encrypt(
				$target,
				self::aes_encryption_method,
				self::aes_key,
				0,
				base64_decode(self::aes_iv)
			);
		} catch(Exception $e) {
			die($e->getMessage());
		} finally {
			return $target;
		}
	}
	
	public static function aes_decrypt(string $target): string {
		try {
			$target = openssl_decrypt($target,
				self::aes_encryption_method,
				self::aes_key,
				0,
				base64_decode(self::aes_iv)
			);
		} catch(Exception $e) {
			die($e->getMessage());
		} finally {
			return $target;
		}
	}
}