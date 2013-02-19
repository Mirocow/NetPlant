<?php

$config = array(
    'components' => array(
        'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=netplant',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'schemaCachingDuration' => 86400,
			'enableProfiling' => true,
            'enableParamLogging' => true,
            'tablePrefix'=>'',
		),
    )
);


$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.db.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.db.php'));
}

return $config;