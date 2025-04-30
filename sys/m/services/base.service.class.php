<?php
namespace Platypus\Service;

use Platypus\Database\MySQL;

class BaseService extends Platypus {
	protected MySQL $conn;
	
	public function __construct() {
		$this->conn = $this->mysql;
	}
}