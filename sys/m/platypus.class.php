<?php
namespace Platypus;

class Platypus {
	private const base_files = [
		"exceptions.class.php",
		"converter.class.php",
		"jsonmaid.class.php",
		"whistle.class.php",
		"mysqlpdo.class.php",
		"hasher.class.php",
		"base.router.class.php",
		"../c/base.controller.interface.php",
		"../c/base.controller.class.php"
	];

	protected Platypus\Database\MySQL $mysql;

	public function __construct() {
		# TODO: implement singleton

		Platypus\Database\MySQL::is_localhost(is_localhost);
		$this->mysql = new Platypus\Database\MySQL();
	}

	public static function import_base_files() {
		# TODO: import base files
	}
}