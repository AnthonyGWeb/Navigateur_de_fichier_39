<?php

define('ROOT_DIR', realpath(dirname(__FILE__)));

require_once ROOT_DIR . '/controller/MainAPI.php';
require_once ROOT_DIR . '/Routing.php';

use navigateur\api\controller\MainAPI as MainAPI;

try {
	$Routing = new Routing($_POST);
	$data = $Routing->getAction();	
}
catch (Exception $e) {
	die('Exception : ' . $e->getMessage() . "\n");
}

$MainAPI = new MainAPI\MainAPI();
$MainAPI->{$data['action']}($data['data']);
