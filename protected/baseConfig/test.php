<?php

$config = CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'), array(
	
    'theme'=>'classic',
	

	
	'defaultController'=>'Core/Site/Index',


	'params' => array(
		
		),


));
$config = CMap::mergeArray(
	$config,
    require(dirname(__FILE__).'/db-test.php')
    );

$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.test.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.test.php'));
}

return $config;