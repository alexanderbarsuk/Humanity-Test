<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('html_errors', '1');

require_once __DIR__ . "/vendor/autoload.php";

use App\Main;
use Tools\Config\Builder\NativeBuilder;
use Tools\Config\Config;
use Tools\Log\Logger;
use Tools\Log\Storage\InFileStorage;

$appConfig = new Config(new NativeBuilder("config/app.php"));
$appLogger = new Logger();
$appLogger->addStorage(new InFileStorage("data/log/myLog.log"));

Main::start($appConfig, $appLogger);
