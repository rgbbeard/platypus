<?php
namespace Platypus\Controller;

require_once "base.controller.class.php";

class Controller extends BaseController {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @router(name="home", path="/")
	 */
	public function _home() {
		die("HOME!");
	}
}