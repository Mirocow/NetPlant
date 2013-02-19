<?php

$config = CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'), array(
	
    'theme'=>'classic',
	'components' => array(

	    ),
	
	

	'params' => array(
		
		),


));

$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.front.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.front.php'));
}

return $config;