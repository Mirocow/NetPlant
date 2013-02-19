<?php

$config = CMap::mergeArray(
	require(dirname(__FILE__).'/db.php'), array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'protected',
	'name'=>'NetPlant hosting panel',
    
	// preloading 'log' component
	'preload'=>array('log', 'fileCache', 'cache', 'bootstrap', 'RSentryException'),

	'modules'=>array('User'),
	'language'=>'ru',
	'sourceLanguage'=>'en',
	

	// autoloading model and component classes
	'import'=>array(
		
        'application.extensions.*',
		'application.components.*',


		// App-needed extensions
		'application.extensions.yiiext.nested-set-behavior.NestedSetBehavior',
		'application.extensions.yiiext.taggable-behavior.*',
		'application.extensions.yiiext.set-return-url-filter.*',

	),


	// application components
	'components'=>array(
		// 'messages' => array(
		// 	'class'=>'CDbMessageSource',
		// 	'cacheID'=>'fileCache',
		// 	'cachingDuration' => 86400*7,
		// 	'sourceMessageTable' => 'I18NSourceMessage',
		// 	'translatedMessageTable' => 'I18NMessage',
		// ),
		'request'=>array(
            'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
            'csrfTokenName'=>'token',
            'class'=>'HttpRequest',
            'noCsrfValidationRoutes'=>array(
            	'^User/user/login$',
	        ),
        ),
		'errorHandler'=>array(
            'errorAction'=>'Site/Error',
        ),
        'themeManager' => array(
            'basePath' => './themes/',
        ),
        'cache' => array(
            'class' => 'CApcCache',
            'behaviors' => array(
            		'TaggableCacheBehavior' => array(
            				'class' => 'ext.TaggableCacheBehavior',
            			),
            	),
        ),
        'fileCache' => array(
        	'class' => 'CFileCache',
        	'behaviors' => array(
            		'TaggableCacheBehavior' => array(
            				'class' => 'ext.TaggableCacheBehavior',
            			),
            	),
        ),
		'user'=>array(
			'class'=>'CWebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            //'autoRenewCookie' => true,
            'loginUrl'=>array('/User/User/Login'),
		),
		'urlManager'=>array(
                'urlFormat'=>'path',
                'showScriptName'=>false,
                'appendParams'=>false,
                'urlSuffix'=>'.html',

                'rules'=>array(
 					'<module>/<controller>/<action>' => '<module>/<controller>/<action>',               	
        			'<controller>/<action>' => '<controller>/<action>',
        			
                ),
        ),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		),
		'session'=> array(
			'class' => 'CDbHttpSession',
			'sessionTableName' => 'Session',
			'connectionID' => 'db',
            'timeout' => 86400,
            'cookieMode' => 'only',

        ),

		'RSentryException'=> array(
    	    'dsn'=> 'http://d306c768d10d415d9d75a04c276792a0:102c48b3bc8046a681083d05b4231250@hydra.uptimehost.ru:9000/2',
            'class' => 'application.components.yii-sentry-log.RSentryComponent',
    	),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                'sentry'=>array(
                    'class'=>'application.components.yii-sentry-log.RSentryLog',
                    'dsn'=> 'http://d306c768d10d415d9d75a04c276792a0:102c48b3bc8046a681083d05b4231250@hydra.uptimehost.ru:9000/2',
                    'levels'=>'error, warning, info, debug',
                ),                

		        
				
			),
		),
		'clientScript'=>array(
			'class' => 'MinifyClientScript',
                'caching' => true,//!YII_DEBUG, //use the cached css file if available
                'compress' => false, //remove whitespace and linearize to 1 line
                'scriptMap'=>array(
 					'jquery.js'=>false,
 					'jquery.min.js'=>false,
 				),
         ),
		'bootstrap' => array(
			'class'=>'ext.bootstrap.components.Bootstrap',
			'coreCss' => false,
			'enableJS' => true,
			'plugins'=>array(

				),
			),
		'mailer' => array(
			'class' => 'ext.Mailer.Mailer',
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// Salt is used for salting passwords
		// Don't change it in production!
		'salt'=>'9asjo32npsdf0',

	),



));

$app = getenv('ENVIRONMENT')?getenv('ENVIRONMENT'):'app';

if (file_exists(dirname(__FILE__).'/../'.$app.'/app.main.php')) {
	$config = CMap::mergeArray(
		$config,
		require(dirname(__FILE__).'/../'.$app.'/app.main.php'));
}

return $config;