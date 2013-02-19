<?php

$config = CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'), array(
	
    'commandMap' => array(
        'migrate' => array(
            // псевдоним директории, в которую распаковано расширение
            'class' => 'application.extensions.yiiext.migrate-command.EMigrateCommand',
            // путь для хранения общих миграций
            'migrationPath' => 'application.protected.migrations',
            // имя таблицы с версиями
            'migrationTable' => 'Migrations',
            // имя псевдомодуля для общих миграций. По умолчанию равно "core".
            'applicationModuleName' => 'core',
            // определяем все модули, для которых нужны миграции  (в противном случае, модули будут взяты из конфигурации Yii)
            'modulePaths' => array(
              
            ),
            // можно задать имя поддиректории для хранения миграций в директории модуля
            'migrationSubPath' => 'migrations',
            // отключаем некоторые модули
            'disabledModules' => array(
               
            ),
            // название компонента для подключения к базе данных
            'connectionID'=>'db',
           
        ),

    ),


));

$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.console.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.console.php'));
}

return $config;

	