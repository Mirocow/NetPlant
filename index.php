<?php

// This is a base bootstrap for DotPlant CMS

// Change this, if you whould like another time zone
date_default_timezone_set('Europe/Moscow');

// Currently we work only in utf-8. Will be changed later after better testing.
header("Content-Type: text/html; charset=utf-8");

// change the following paths if necessary
// but in base setup of DotPlant CMS it is not needed
$yii=dirname(__FILE__).'/protected/extensions/bethrezen/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/baseConfig/front.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',1);

require_once($yii);

$app = new CWebApplication($config);
$app->run();
