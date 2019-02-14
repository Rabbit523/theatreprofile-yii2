<?php
function minify_output($buffer)
{
	//return Minify_HTML::minify($buffer);
	return $buffer;
}
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once(dirname(__FILE__).'/protected/vendors/Google/src/Google/autoload.php');
require_once($yii);
//require_once(dirname(__FILE__).'/protected/extensions/minScript/vendors/minify/min/lib/Minify/HTML.php');
$app = Yii::createWebApplication($config);

Yii::app()->onBeginRequest = function($event)
{
	return ob_start("minify_output");
};

Yii::app()->onEndRequest = function($event)
{
	if(ob_get_level()>0)
		return ob_end_flush();
};
$app->run();