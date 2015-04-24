<?php
define ('__ROOT_DIR__', realpath(dirname(__FILE__)));

require_once __ROOT_DIR__ . '/src/model/controller.php';
require_once __ROOT_DIR__ . '/src/model/routing.php';
require_once __ROOT_DIR__ . '/src/controller/main.php';
require_once __ROOT_DIR__ . '/vendor/autoload.php';
require_once __ROOT_DIR__ . '/src/helpers/file.php';

$ctrl = new MainController($_POST, $_GET, $_SERVER);
$ctrl->handleRequest();
