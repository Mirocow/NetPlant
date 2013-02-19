<?php

$config = array(
    'components' => array(
        'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=netplant',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'schemaCachingDuration' => 3600,
//			'enableProfiling' => true,
//            'enableParamLogging' => true,
            'tablePrefix'=>'',
		),
    )
);


$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.db-test.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.db-test.php'));
}

return $config;