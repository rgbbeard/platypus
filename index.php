<?php
define("localhost_base", (str_contains($_SERVER["HTTP_HOST"], "localhost") ? "" : ""));
define("is_localhost", (defined("localhost_base") && !empty(localhost_base)));

require_once "sys/utilities.php";
require_once "sys/m/platypus.class.php";

use Platypus\Platypus;

Platypus::import_base_files();

$core = new Platypus();