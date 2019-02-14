<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('theatreprofile',dirname(__FILE__).'/../../../theatreprofile/protected');
//Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'EBQ Entertainment',
	'timeZone' => 'UTC',
	'aliases' => array(),

	// preloading 'log' component,
	'preload' => array(
        'log',
        'errorHandler', // handle fatal errors
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.*',
		'application.vendors.*',
		'application.helpers.*',
		'theatreprofile.models.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		//'gii'=>array(
		//	'class'=>'system.gii.GiiModule',
		//	'password'=>'!password1',
		// If removed, Gii defaults to localhost only. Edit carefully to taste.
		//	'ipFilters'=>array('127.0.0.1','::1'),
		//),
	),

	// application components
	'components'=>array(
		'request'=>array(
			'enableCookieValidation'=>true,
			'enableCsrfValidation'=>true,
		),
		'clientScript'=>array(
            'coreScriptPosition'=>CClientScript::POS_HEAD,
			'defaultScriptFilePosition'=>CClientScript::POS_END,
            'defaultScriptPosition'=>CClientScript::POS_END,
			'class'=>'ext.minScript.components.ExtMinScript',
			'minScriptDisableMin' => array('/[-\.]min\.(?:js)$/i'),
        ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			//'caseSensitive'=>false, 
			'rules'=>array(
				'<view:(about|terms|grow|privacy|social|pilot|Actor-TheatreProfessionals-Form|Consumer-Pilot-Program-Form|Education-Application-Form|Venue-Pilot-Program-Form|Theatre-Form)>'=>'site/page',
                '<action:contact>'=>'site/<action>',
				'<controller:\w+>/<id:\d+>/<title>'=>'<controller>/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=theatrep_main',
			'emulatePrepare' => true,
			'username' => 'theatrep_admin',
			'password' => '!password1',
			'initSQLs'=>array("set time_zone='+00:00';"), 
			'charset' => 'utf8',
			
            // set to true to enable database query logging
            // don't forget to put `profile` in the log route `levels` below
            'enableProfiling' => false,
 
            // set to true to replace the params with the literal values
            'enableParamLogging' => false,
		),
		'image'=>array(
          'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'trace, warning, error, info, profile, audit',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	
	//Controller map
	'controllerMap'=>array(
		'min'=>array(
			'class'=>'ext.minScript.controllers.ExtMinScriptController',
		),
    ),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'info@theatreprofile.com',
		'mediaServeUrl'=>'http://media.theatreprofile.com',
		'theatreprofileBaseUrl'=>'http://www.theatreprofile.com',
	),
);