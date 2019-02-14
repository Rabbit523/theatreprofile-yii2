<?php

class ApiController extends Controller
{
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights',
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function allowedActions()
	{
		return 'list,view,authorize,generatekey';
	}
 
    // Actions
    public function actionList()
    {
		if(!isset($_GET['page'])){
			$page = 1;
		} else {
			$page = $_GET['page'];
		}
		$perPage = 10;
		$offset = ($page > 1) ? ($page * $perPage) - $perPage : 0;
		$criteria = new CDbCriteria;
		//$criteria->order = 'show.id ASC';
		$criteria->limit=$perPage;
		$criteria->offset=$offset;
		switch($_GET['model'])
		{
			case 'show':				
				$models = Show::model()->findAll($criteria);
				break;
			case 'production':
				$models = Production::model()->findAll($criteria);
				break;
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
					'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
				Yii::app()->end();
		}
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, 
					sprintf('No items found for model <b>%s</b>', $_GET['model']) );
		} else {
			$this->_sendResponse(200, jsonizenc($models));
		}
    }
	
    public function actionView()
    {
		 // Check if id was submitted via GET
		if(!isset($_GET['id']))
			$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
	 
		switch($_GET['model'])
		{
			// Find respective model    
			case 'show':
				$model = Show::model()->findByPk($_GET['id']);
				break;
			case 'production':
				$model = Production::model()->findByPk($_GET['id']);
				break;
			default:
				$this->_sendResponse(501, sprintf(
					'Mode <b>view</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
				Yii::app()->end();
		}
		// Did we find the requested model? If not, raise an error
		if(is_null($model))
			$this->_sendResponse(404, 'No item found with id '.$_GET['id']);
		else
		{
			switch($_GET['model'])
			{
				case 'show':
					$this->_sendResponse(200,jsonizenc($model, array('category','showcreators'=>array('individual','role'=>array('department')),'productions','profileimage'=>array('image'),'galleryimages'=>array('image'))));
					break;
				case 'production':
					$this->_sendResponse(200,jsonizenc($model, array('show'=>array('showcreators'=>array('individual','role'=>array('department'))),'category','productioncasts'=>array('individual'),'productioncrews'=>array('individual'),'productioncompanycrews'=>array('individual'),'productionvenues'=>array('venue'=>array('address')),'avgrating','ratingcount','profileimage'=>array('image'),'galleryimages'=>array('image'))));
					break;
				default:
					$this->_sendResponse(501, sprintf(
						'Mode <b>view1</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
		}	
    }
    
	public function actionCreate()
    {
		switch($_GET['model'])
		{
			// Get an instance of the respective model
			default:
				$this->_sendResponse(501, 
					sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
					Yii::app()->end();
		}
    }
    
	public function actionUpdate()
    {
		switch($_GET['model'])
		{
			// Get an instance of the respective model
			default:
				$this->_sendResponse(501, 
					sprintf('Mode <b>update</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
					Yii::app()->end();
		}
    }
    
	public function actionDelete()
    {
		switch($_GET['model'])
		{
			// Get an instance of the respective model
			default:
				$this->_sendResponse(501, 
					sprintf('Mode <b>delete</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
					Yii::app()->end();
		}
    }
	
	public function actionAuthorize()
    {
		if(!isset($_GET['key']))
			$this->_sendResponse(500, 'Error: Parameter <b>key</b> is missing' );
		switch($_GET['model'])
		{
			// Get an instance of the respective model
			case 'user':
				$model=UserKeys::validateKey($_GET['key']);
				if(empty($model)) {
					$this->_sendResponse(200, 
							sprintf('Invalid key. Key provided was <b>%s</b>.', $_GET['key']) );
				} else {
					$this->_sendResponse(200, jsonizenc($model, array('id','userID', 'activeKey','ipAddress', 'expirationDate', 'status', 'user' => array('id','username','email')),true,true));
				}
				break;
			default:
				$this->_sendResponse(501,
					sprintf('Mode <b>delete</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
					Yii::app()->end();
		}
    }
	
	public function actionGenerateKey()
    {
		switch($_GET['model'])
		{
			// Get an instance of the respective model
			case 'user':
				$reportAccess=false;
				$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
				foreach($roles as $role) if($role->name == 'ReportUser') $reportAccess=true;
				if(!$reportAccess)
				{
					$this->_sendResponse(401, 
							sprintf('Invalid request.'));
				}
				else
				{
					$activeKey=json_encode(array('activeKey' =>UserKeys::generateKey(Yii::app()->user->id)));
					if(empty($activeKey)) {
						$this->_sendResponse(401, 
								sprintf('Invalid request.') );
					} else {
						$this->_sendResponse(200, json_encode($activeKey));
					}
				}
				break;
			default:
				$this->_sendResponse(401,
					sprintf('Mode <b>delete</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
					Yii::app()->end();
		}
    }
	
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		// set the status
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		// and the content type
		header('Content-type: ' . $content_type);
	 
		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';
	 
			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}
	 
			// servers don't always have a signature turned on 
			// (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
	 
			// this should be templated in a real-world solution
			$body = '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
	</head>
	<body>
		<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
		<p>' . $message . '</p>
		<hr />
		<address>' . $signature . '</address>
	</body>
	</html>';
	 
			echo $body;
		}
		Yii::app()->end();
	}


	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}