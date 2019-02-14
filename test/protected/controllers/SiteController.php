<?php

class SiteController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights',
		);
	}
	
	public function allowedActions()
	{
		return 'actions,index,error,facebook_feed,contact,page,captcha';
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$models = Carousel::model()->findAll(array(
			'condition' => 'status = :stat',
			'params' => array(':stat' => '1'),
		));
		$items=array();
		$i=0;
		foreach($models as $model)
		{
			$items[$i]['image']=yii::app()->request->baseUrl.'/images/'.$model->imageURL;
			$items[$i]['href']=$model->href;
			$items[$i]['label']=$model->label;
			$items[$i]['caption']=$model->caption;
			$i++;
		}
	
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		$this->layout='home';
		$this->render('index',array('items'=>$items));
	}
	
	
	/**
	 * This is the default 'admin' action
	 */
	public function actionAdministrator()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('administrator');
	}
	
	
	public function actionFacebook_feed()
	{
		/*************************************/
		/* Introduce your credentials */
		/*
		1 - If you are not registered as a developer in Facebook, you will have to register in https://developers.facebook.com/, go to Apps -> Register as a Developer
		2 - Once you are registered go to https://developers.facebook.com/ Apps -> Create a new App and fill the form
		3 - If you created the App succesfully, you will see the new App ID and Secret keys in the dashboard
		*/

		$app_id 	= '1444685225816091';
		$app_secret = '9d6baffd7727827bfc5538c60b5e4b9b';

		/***************************************/

		error_reporting(0);


		if(empty($_GET['page_id'])) 
			die('The FB Page ID is required');
			
		$screen_name_data = $_GET['page_id'];
		$count = $_GET['count'];

		if($count == "" || $count <= 0) 
			$count = 20;

		require_once( 'Facebook/facebook.php' );

		$facebook = new FacebookGraphV2(array(
		  'appId'  => $app_id,
		  'secret' => $app_secret,
		));

		$fields = array('status_type', 'picture', 'object_id', 'source', 'link', 'message', 'description', 'id', 'from', 'created_time', 'type');

		$response = $facebook->api('/'.$screen_name_data.'/posts', 'get', array('limit' => $count, 'fields' => $fields));
		$graph_arr = $response['data'];
		if(empty($response['data'])) {
			$response = $facebook->api('/'.$screen_name_data.'/feed', 'get', array('limit' => $count, 'fields' => $fields));
			$graph_arr = $response['data'];
		}
		/*
		echo '<pre>';
		print_r($graph_arr);
		echo '</pre>';
		*/
		$count = 0;
		$json_decoded = array();


		if(is_array($graph_arr)) {
			$json_decoded['responseData']['feed']['link'] = "https://facebook.com/".$screen_name_data;
			$json_decoded['responseData']['feed']['entries'] = array();	/*echo '<pre>';
			print_r($graph_arr);
			echo '</pre>';
			die();*/
			foreach($graph_arr as $data)
			{
				$picture = $data['picture'];
				
				if(!isset($data['object_id'])) {
					$pic_id = explode("_", $picture);	
					$data['object_id'] = $pic_id[1];
				}
				
				if($data['type'] == "link" && strpos($picture, 'hprofile') !== false) {
					$picture = "";
				} else {

					if(strpos($picture, 'safe_image.php') === false && is_numeric($data['object_id'])) {
						$picture = 'https://graph.facebook.com/'.$data['object_id'].'/picture?type=normal';
					}
					
				}

				if($data['message'] == '') {
					$data['message'] = $data['description'];	
				}
				
				if($data['source'] != '' && strpos($data['link'], 'facebook.com') !== false) {
					$data['message'] .= '<video width="480" height="320" controls="controls" poster="'.str_replace(array('https://', 'http://'), 'nolinkVideo', $picture).'">
					<source src="'.str_replace(array('https://', 'http://'), 'nolinkVideo', $data['source']).'" type="video/mp4">
					</video>';	
					$picture = "";
				}
				if(substr($data['link'], 0, 8) == '/events/') {
					$data['link'] = "https://facebook.com".$data['link'];
					
					if(strpos($picture, 'safe_image.php') === false && is_numeric($data['object_id'])) {
						$picture = 'https://graph.facebook.com/'.$data['object_id'].'/picture?type=large';
					}
				}
				
				if($data['story'] != '') {
					$data['link'] = '';
					$data['message'] = $data['message'] == '' ? $data['story'] : $data['message'];
					$picture = $data['picture'];
				}
				
				if(($data['message'] == '' && $picture == '') || (is_numeric($_GET['count']) && $count >= $_GET['count'])) {
					continue;
				}
				/*$picture = str_replace(array("s130x130/", "p130x130/", "p118x90/"), '', $data['picture']);
				$picture = str_replace('/v/t1.0-0/', '/t1.0-0/', $picture);
				$picture = str_replace('/v/t1.0-9/', '/t1.0-9/', $picture);
				$picture = str_replace('/v/l/t1.0-0/', '/t1.0-0/', $picture);
				$picture = str_replace('/v/l/t1.0-9/', '/t1.0-9/', $picture);
				$picture = str_replace('/192x/', '/736x/', $picture);*/
				
				$json_decoded['responseData']['feed']['entries'][$count]['link'] = ($data['link'] != "" ? $data['link'] : "https://facebook.com/".$data['id']);
				$json_decoded['responseData']['feed']['entries'][$count]['contentSnippet'] = nl2br($data['message']);
				$json_decoded['responseData']['feed']['entries'][$count]['content'] = nl2br($data['message']);
				$json_decoded['responseData']['feed']['entries'][$count]['title'] = nl2br($data['message']);
				$json_decoded['responseData']['feed']['entries'][$count]['thumbnail'] = $picture;
				$json_decoded['responseData']['feed']['entries'][$count]['author'] = $data['from']['name'];
				@$json_decoded['responseData']['feed']['entries'][$count]['publishedDate'] = date("D, d M Y H:i:s O", strtotime($data['created_time']));
				
				$count++;
			}
		}

		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json_decoded);	
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
			{
				if($error['code']==403)
				{
					$error['message'] = "You don’t have access to this section of the site.";
				}
				$this->render('error', $error);
			}
		}
	}

	
	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will send 
				you a response as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
	
	/**
	 * Launch report dashboard
	 */
	public function actionLaunchReportingDashboard()
	{
		if(!Yii::app()->user->checkAccess('Site.LaunchReportingDashboard'))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to view this page.");
		}
		else
		{
			Yii::app()->user->setFlash('info', "The reporting dashboard will now be launched in a new window...");
			//php script to launch new window and post new active key for user
			Yii::app()->user->setState('activeKey', UserKeys::generateKey(Yii::app()->user->id));
			Yii::app()->user->setState('launchReports', "true");
		}
		$this->redirect(Yii::app()->request->urlReferrer);
	}
}
