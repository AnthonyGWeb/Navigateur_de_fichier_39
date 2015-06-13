<?php

define('__HOME__', preg_filter('/\?.*/', '', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']));
define('__ROOT_DIR__', realpath(dirname(__FILE__)));

require_once __ROOT_DIR__ . '/src/model/controller.php';
require_once __ROOT_DIR__ . '/src/model/routing.php';
require_once __ROOT_DIR__ . '/src/controller/main.php';
require_once __ROOT_DIR__ . '/vendor/autoload.php';
require_once __ROOT_DIR__ . '/src/helpers/file.php';

//error_reporting(0); // Pour masquer les erreurs de script
session_start();

$ctrl = new MainController($_POST, $_GET, $_SESSION, $_SERVER, $_FILES);
$ctrl->handleRequest();
