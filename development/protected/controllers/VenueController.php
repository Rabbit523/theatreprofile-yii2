<?php

class VenueController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
			'rights',
			'postOnly + delete', // we only allow deletion via POST request
			array('ext.seo.filters.SeoFilter + view'), // apply the filter to the view-action
		);
	}
	
	public function allowedActions()
	{
		return 'index,view,schedule,getevents,uploadticketsaleinfo';
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionSchedule($id)
	{
		$this->render('schedule',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAnalytics($id)
	{
		$model=$this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$model->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to view this information.");
			$this->redirect($model->createUrl());
		}
	
		require_once(Yii::getPathOfAlias('Google').'/src/Google/Client.php');
		$client_id = '86692592400-0le4c08r7f7qktv5pd2v2j49phag032n.apps.googleusercontent.com'; //Client ID
		//$client_id = '40272878428-3d8dvdg9ff20neoldo261romjsv8norh.apps.googleusercontent.com'; //Client ID
		$service_account_name = '86692592400-0le4c08r7f7qktv5pd2v2j49phag032n@developer.gserviceaccount.com'; //Email Address
		//$service_account_name = '40272878428-3d8dvdg9ff20neoldo261romjsv8norh@developer.gserviceaccount.com'; //Email Address
		$key_file_location = Yii::getPathOfAlias('Google').'/Theatre Profile-2978d2c716a5.p12';
		//$key_file_location = Yii::getPathOfAlias('Google').'\Test-85e9b929a612.p12';
		$client = new Google_Client();
		$client->setApplicationName('Theatre Profile Analytics');
		//Get token from database
		$Systemparameter = Systemparameter::model()->find('parameterName=:parameterName',array(':parameterName'=>'GoogleAPIServiceToken'));
		if (count($Systemparameter))
		{
			$client->setAccessToken($Systemparameter->parameterValue);
		}
		$key = file_get_contents($key_file_location);
		$cred = new Google_Auth_AssertionCredentials($service_account_name,array('https://www.googleapis.com/auth/analytics.readonly'),$key);
		$client->setAssertionCredentials($cred);
		if ($client->getAuth()->isAccessTokenExpired()) {
			$client->getAuth()->refreshTokenWithAssertion($cred);
			//Save token to database
			$Systemparameter = Systemparameter::model()->find('parameterName=:parameterName',array(':parameterName'=>'GoogleAPIServiceToken'));
			if (count($Systemparameter))
			{
				$Systemparameter->parameterValue=$client->getAccessToken();
				$Systemparameter->save();
			}
			else
			{
				$Systemparameter = new Systemparameter;
				$Systemparameter->parameterName='GoogleAPIServiceToken';
				$Systemparameter->parameterValue=$client->getAccessToken();
				$Systemparameter->save();
			}
		}

		$analytics = new Google_Service_Analytics($client);
		$this->render('analytics',array(
			'model'=>$this->loadModel($id),
			'profileId'=>7961023,
			'analytics'=>$analytics,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Venue;
		$model->address = new Address;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Venue'])&&isset($_POST['Address']))
		{
			$model->attributes=$_POST['Venue'];
			$model->address->attributes=$_POST['Address'];
			if($model->validate())
			{
				if($model->address->save())
				{
					$model->addressID = $model->address->id;
					if($model->save())
					{
						if(isset($_POST['production']['new']))
						{
							$newProductions=$_POST['production']['new'];
							foreach($newProductions as $newProduction)
							{	
								
								
								$Productionvenue = new Productionvenue;
								$Productionvenue->productionID=$newProduction['productionID'];
								if($Productionvenue->productionID==0)
								{
									$Production = new Production;
									$Production->showID = $newProduction['showID'];
									if($Production->showID==0)
									{
										$Show = new Show;
										$Show->showName = $newProduction['showName'];
										$Show->categoryID = 0;
										$Show->save();
										$Production->showID=$Show->id;
									}
									$Production->startDate=!empty($newProduction['startDate'])?$newProduction['startDate']:NULL;
									$Production->endDate=!empty($newProduction['endDate'])?$newProduction['endDate']:NULL;
									$Production->save();
									Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$Production->show->showName.' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
									$Productionvenue->productionID=$Production->id;
								}
								$Productionvenue->venueID=$model->id;
								$Productionvenue->startDate=!empty($newProduction['startDate'])?$newProduction['startDate']:NULL;
								$Productionvenue->endDate=!empty($newProduction['endDate'])?$newProduction['endDate']:NULL;
								$Productionvenue->save();
							}
						}					
						
						//save profile image
						$file=CUploadedFile::getInstanceByName('image');
						if(!$file){
							//echo 'no file selected';
						}
						else
						{
							$cropX = (int)$_POST['crop_x'];
							$cropY = (int)$_POST['crop_y'];
							$resizedWidth = (int)$_POST['width'];
							$resizedHeight = (int)$_POST['height'];
							$imageOriginalPath = $file->getTempName();
							list($originalWidth, $originalHeight, $originalType) = getimagesize($imageOriginalPath);
							$types = array(1 => 'gif', 'jpeg', 'png');
							$imageOriginal = call_user_func('imagecreatefrom' . $types[$originalType],$imageOriginalPath);
							$imageResized = imagecreatetruecolor($resizedWidth, $resizedHeight);
							imagecopyresampled($imageResized, $imageOriginal, 0, 0, $cropX, $cropY, $resizedWidth, $resizedHeight, $resizedWidth, $resizedHeight);
							$fileName = $this->getUniqueFileName($file->getName(),'v');
							$path_parts = pathinfo($fileName);
							if (strtolower($path_parts['extension']) == 'jpg') {
								imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
							} elseif (strtolower($path_parts['extension']) == 'jpeg') {
								imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
							} elseif (strtolower($path_parts['extension']) == 'gif') {
								imagegif($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
							} elseif (strtolower($path_parts['extension']) == 'png') {
								imagepng($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
							}
							$image = new Image;
							$image->imageURL=$fileName;
							$image->save();

							$prodile_image = new Profileimage;
							$prodile_image->profileID = $model->id;
							$prodile_image->profileType=4; 
							$prodile_image->imageID=$image->id; 
							$prodile_image->imageType=1; 
							$prodile_image->save();
						}
						
						//Save contact information
						if($_POST['contactInfoID_facebook']!=0)
							$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
						else
						{
							$Venuecontactinfo = new Venuecontactinfo;
							$Venuecontactinfo->id = $_POST['contactInfoID_facebook'];
						}
						$Venuecontactinfo->venueID=$model->id; 
						$Venuecontactinfo->contactTypeID=1; 
						$Venuecontactinfo->contactInfo=$_POST['contactInfo_facebook'];
						$Venuecontactinfo->save();
						
						if($_POST['contactInfoID_googleplus']!=0)
							$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
						else
						{
							$Venuecontactinfo = new Venuecontactinfo;
							$Venuecontactinfo->id = $_POST['contactInfoID_googleplus'];
						}
						$Venuecontactinfo->venueID=$model->id; 
						$Venuecontactinfo->contactTypeID=2; 
						$Venuecontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
						$Venuecontactinfo->save();
						
						if($_POST['contactInfoID_twitter']!=0)
							$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
						else
						{
							$Venuecontactinfo = new Venuecontactinfo;
							$Venuecontactinfo->id = $_POST['contactInfoID_twitter'];
						}
						$Venuecontactinfo->venueID=$model->id; 
						$Venuecontactinfo->contactTypeID=3; 
						$Venuecontactinfo->contactInfo=$_POST['contactInfo_twitter'];
						$Venuecontactinfo->save();
						
						if($_POST['contactInfoID_instagram']!=0)
							$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
						else
						{
							$Venuecontactinfo = new Venuecontactinfo;
							$Venuecontactinfo->id = $_POST['contactInfoID_instagram'];
						}
						$Venuecontactinfo->venueID=$model->id; 
						$Venuecontactinfo->contactTypeID=4; 
						$Venuecontactinfo->contactInfo=$_POST['contactInfo_instagram'];
						$Venuecontactinfo->save();
						
						if($_POST['contactInfoID_website']!=0)
							$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_website']);
						else
						{
							$Venuecontactinfo = new Venuecontactinfo;
							$Venuecontactinfo->id = $_POST['contactInfoID_website'];
						}
						$Venuecontactinfo->venueID=$model->id; 
						$Venuecontactinfo->contactTypeID=5; 
						$Venuecontactinfo->contactInfo=$_POST['contactInfo_website'];
						$Venuecontactinfo->save();
						
						Yii::app()->user->setFlash('success', "Venue successfully created.");
						$this->redirect(array('update','id'=>$model->id));
					}
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$model->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($model->createUrl());
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);		

		if(isset($_POST['Venue'])&&isset($_POST['Address']))
		{
			$model->venueName=$_POST['Venue']['venueName'];
			$model->descr=$_POST['Venue']['descr'];
			
			if($model->addressID==0)
			{
				$address = new Address;
				$address->addr1 = $_POST['Address']['addr1'];
				$address->city = $_POST['Address']['city'];
				$address->state = $_POST['Address']['state'];
				$address->zip = $_POST['Address']['zip'];
				$address->countryID = $_POST['Address']['countryID'];
				$address->save();
				$model->addressID = $address->id;
			}
			else
			{
				$address = Address::model()->findByPk($model->addressID);
				$address->addr1 = $_POST['Address']['addr1'];
				$address->city = $_POST['Address']['city'];
				$address->state = $_POST['Address']['state'];
				$address->zip = $_POST['Address']['zip'];
				$address->countryID = $_POST['Address']['countryID'];
				$address->save();
			}
			if($model->save())
			{
			
				if(isset($_POST['links']))
				{
					$ticketingLinks=$_POST['links'];
					if(isset($ticketingLinks['new']))
					{
						foreach($ticketingLinks['new'] as $newTicketingLink)
						{
							$Link = new Link;
							$Link->profileType=5;
							$Link->profileID=$newTicketingLink['productionVenueID'];
							$Link->href=$newTicketingLink['href'];
							$Link->linkType=1;
							$Link->label=$newTicketingLink['label'];
							$Link->save();
						}
					}
					if(isset($ticketingLinks['existing']))
					{
						foreach($ticketingLinks['existing'] as $existingTicketingLink)
						{
							$Link = Link::model()->findByPk($existingTicketingLink['id']);
							$Link->href=$existingTicketingLink['href'];
							$Link->label=$existingTicketingLink['label'];
							$Link->save();
						}
					}
				}
			
				if(isset($_POST['production']))
				{
					if(isset($_POST['production']['new']))
					{
						$newProductions=$_POST['production']['new'];
						foreach($newProductions as $newProduction)
						{
							$Productionvenue = new Productionvenue;
							$Productionvenue->productionID=$newProduction['productionID'];
							if($Productionvenue->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $newProduction['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $newProduction['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=!empty($newProduction['startDate'])?$newProduction['startDate']:NULL;
								$Production->endDate=!empty($newProduction['endDate'])?$newProduction['endDate']:NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$Production->show->showName.' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productionvenue->productionID=$Production->id;
							}
							$Productionvenue->venueID=$model->id;
							$Productionvenue->startDate=!empty($newProduction['startDate'])?$newProduction['startDate']:NULL;
							$Productionvenue->endDate=!empty($newProduction['endDate'])?$newProduction['endDate']:NULL;
							$Productionvenue->save();
						}
					}
					
					if(isset($_POST['production']['existing']))
					{
						$existingProductions=$_POST['production']['existing'];
						foreach($existingProductions as $existingProduction)
						{
							$Productionvenue = Productionvenue::model()->findByPk($existingProduction['id']);
							$Productionvenue->productionID=$existingProduction['productionID'];
							if($Productionvenue->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $existingProduction['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $existingProduction['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=!empty($existingProduction['startDate'])?$existingProduction['startDate']:NULL;
								$Production->endDate=!empty($existingProduction['endDate'])?$existingProduction['endDate']:NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$Production->show->showName.' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productionvenue->productionID=$Production->id;
							}
							$Productionvenue->venueID=$model->id;
							$Productionvenue->startDate=!empty($existingProduction['startDate'])?$existingProduction['startDate']:NULL;
							$Productionvenue->endDate=!empty($existingProduction['endDate'])?$existingProduction['endDate']:NULL;
							$Productionvenue->save();
						}
					}
					
				}
				
				$exist = Profileimage::model()->
				find("profileID = :profileID and profileType = 4 and imageType=1",array(':profileID'=>$id) );
				if (!$exist) {				
					$file=CUploadedFile::getInstanceByName('image');
					if(!$file){
						//echo 'no file selected';
					}
					else
					{
						$cropX = (int)$_POST['crop_x'];
						$cropY = (int)$_POST['crop_y'];
						$resizedWidth = (int)$_POST['width'];
						$resizedHeight = (int)$_POST['height'];
						$imageOriginalPath = $file->getTempName();
						list($originalWidth, $originalHeight, $originalType) = getimagesize($imageOriginalPath);
						$types = array(1 => 'gif', 'jpeg', 'png');
						$imageOriginal = call_user_func('imagecreatefrom' . $types[$originalType],$imageOriginalPath);
						$imageResized = imagecreatetruecolor($resizedWidth, $resizedHeight);
						imagecopyresampled($imageResized, $imageOriginal, 0, 0, $cropX, $cropY, $resizedWidth, $resizedHeight, $resizedWidth, $resizedHeight);
						$fileName = $this->getUniqueFileName($file->getName(),'v');
						$path_parts = pathinfo($fileName);
						if (strtolower($path_parts['extension']) == 'jpg') {
							imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} elseif (strtolower($path_parts['extension']) == 'jpeg') {
							imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} elseif (strtolower($path_parts['extension']) == 'gif') {
							imagegif($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} elseif (strtolower($path_parts['extension']) == 'png') {
							imagepng($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						}
						$image = new Image;
						$image->imageURL=$fileName;
						$image->save();

						$prodile_image = new Profileimage;
						$prodile_image->profileID = $model->id;
						$prodile_image->profileType=4; 
						$prodile_image->imageID=$image->id; 
						$prodile_image->imageType=1; 
						$prodile_image->save();
						//print_r($file);
					}
				}
				else{
					$imageModel=Image::model()->findByPk($exist->imageID);
					$file=CUploadedFile::getInstanceByName('image');
					if(!$file){
						//echo 'no file selected';
					}
					else
					{
						$cropX = (int)$_POST['crop_x'];
						$cropY = (int)$_POST['crop_y'];
						$resizedWidth = (int)$_POST['width'];
						$resizedHeight = (int)$_POST['height'];
						$imageOriginalPath = $file->getTempName();
						list($originalWidth, $originalHeight, $originalType) = getimagesize($imageOriginalPath);
						$types = array(1 => 'gif', 'jpeg', 'png');
						$imageOriginal = call_user_func('imagecreatefrom' . $types[$originalType],$imageOriginalPath);
						$imageResized = imagecreatetruecolor($resizedWidth, $resizedHeight);
						imagecopyresampled($imageResized, $imageOriginal, 0, 0, $cropX, $cropY, $resizedWidth, $resizedHeight, $resizedWidth, $resizedHeight);
						$fileName = $this->getUniqueFileName($file->getName(),'v');
						$path_parts = pathinfo($fileName);
						if (strtolower($path_parts['extension']) == 'jpg') {
							imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} 
						elseif (strtolower($path_parts['extension']) == 'jpeg') {
							imagejpeg($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} 
						elseif (strtolower($path_parts['extension']) == 'gif') {
							imagegif($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} elseif (strtolower($path_parts['extension']) == 'png') {
							imagepng($imageResized, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						}
						$imageModel->imageURL=$fileName;
						$imageModel->save();
					}
				}
				
				$files=$this->fixFilesArray($_FILES['images']);
				foreach($files as $file)
				{
					if(!empty($file['tmp_name']))
					{
						$imageOriginalPath = $file['tmp_name'];
						list($originalWidth, $originalHeight, $originalType) = getimagesize($imageOriginalPath);
						$types = array(1 => 'gif', 'jpeg', 'png');
						$imageOriginal = call_user_func('imagecreatefrom' . $types[$originalType],$imageOriginalPath);
						$fileName = $this->getUniqueFileName($file['name'],'c');
						$path_parts = pathinfo($fileName);
						if (strtolower($path_parts['extension']) == 'jpg') {
							imagejpeg($imageOriginal, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} 
						elseif (strtolower($path_parts['extension']) == 'jpeg') {
							imagejpeg($imageOriginal, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} 
						elseif (strtolower($path_parts['extension']) == 'gif') {
							imagegif($imageOriginal, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						} elseif (strtolower($path_parts['extension']) == 'png') {
							imagepng($imageOriginal, Yii::app()->basePath.'/../images/uploads/'.$fileName);
						}
						
						$image = new Image;
						$image->imageURL=$fileName;
						$image->save();
						
						$profile_image = new Profileimage;
						$profile_image->profileID = $model->id;
						$profile_image->profileType=4; 
						$profile_image->imageID=$image->id; 
						$profile_image->imageType=2; 
						$profile_image->save();
					}
				}
				
				//Save contact information
				if($_POST['contactInfoID_facebook']!=0)
					$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
				else
				{
					$Venuecontactinfo = new Venuecontactinfo;
					$Venuecontactinfo->id = $_POST['contactInfoID_facebook'];
				}
				$Venuecontactinfo->venueID=$model->id; 
				$Venuecontactinfo->contactTypeID=1; 
				$Venuecontactinfo->contactInfo=$_POST['contactInfo_facebook'];
				$Venuecontactinfo->save();
				
				if($_POST['contactInfoID_googleplus']!=0)
					$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
				else
				{
					$Venuecontactinfo = new Venuecontactinfo;
					$Venuecontactinfo->id = $_POST['contactInfoID_googleplus'];
				}
				$Venuecontactinfo->venueID=$model->id; 
				$Venuecontactinfo->contactTypeID=2; 
				$Venuecontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
				$Venuecontactinfo->save();
				
				if($_POST['contactInfoID_twitter']!=0)
					$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
				else
				{
					$Venuecontactinfo = new Venuecontactinfo;
					$Venuecontactinfo->id = $_POST['contactInfoID_twitter'];
				}
				$Venuecontactinfo->venueID=$model->id; 
				$Venuecontactinfo->contactTypeID=3; 
				$Venuecontactinfo->contactInfo=$_POST['contactInfo_twitter'];
				$Venuecontactinfo->save();
				
				if($_POST['contactInfoID_instagram']!=0)
					$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
				else
				{
					$Venuecontactinfo = new Venuecontactinfo;
					$Venuecontactinfo->id = $_POST['contactInfoID_instagram'];
				}
				$Venuecontactinfo->venueID=$model->id; 
				$Venuecontactinfo->contactTypeID=4; 
				$Venuecontactinfo->contactInfo=$_POST['contactInfo_instagram'];
				$Venuecontactinfo->save();
				
				if($_POST['contactInfoID_website']!=0)
					$Venuecontactinfo = Venuecontactinfo::model()->findByPk($_POST['contactInfoID_website']);
				else
				{
					$Venuecontactinfo = new Venuecontactinfo;
					$Venuecontactinfo->id = $_POST['contactInfoID_website'];
				}
				$Venuecontactinfo->venueID=$model->id; 
				$Venuecontactinfo->contactTypeID=5; 
				$Venuecontactinfo->contactInfo=$_POST['contactInfo_website'];
				$Venuecontactinfo->save();
				
				Yii::app()->user->setFlash('success', "Venue successfully updated.");			
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionProductionLists($showID)
    {
        $productions = Production::model()->with(
			array('show'=>array('condition'=>'showID='.$showID),
		))->findAll();
        $item=array();
        foreach($productions as $production)
        {
			if(count($production->productionvenues)>1)
			{
				$item[]=array(
					'id'=>$production->id,
					'value'=>$production->show->showName.(!empty($production->productionName)?' - '.$production->productionName:' - Multiple venues')
				);
			}
			else if(count($production->productionvenues)==1)
			{
				$productionvenue = array_values($production->productionvenues)[0];
				$item[]=array(
					'id'=>$production->id,
					'value'=>$production->show->showName.' at '.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->country->countryCode
				);
			}
			else
			{
				$item[]=array(
					'id'=>$production->id,
					'value'=>$production->show->showName.' - '.'Venue not available'
				);
			}
        }
        echo json_encode($item);
    }
	
	
	public function  actionRemoveproduction($id,$venueid)
	{
		$productionvenue=Productionvenue::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$venueid));
	}
	
	public function actionRemovelink($id,$venueid)
	{
		$link=Link::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$venueid));
	}
	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Venue');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$this->render('index');
	}	
	
	public function actionGetEvents($id)
	{
		$start = Yii::app()->request->getQuery('start');
		$end = Yii::app()->request->getQuery('end');
		if(empty($start)||empty($end))
			 throw new CHttpException(400,'Invalid request.');
		$Productionvenues = Productionvenue::model()->with(array('productionevents'=>array('joinType'=>'INNER JOIN', 'condition'=>"(productionevents.startDate>='".$start."' and productionevents.startdate<'".$end."') or (productionevents.recurs!=0 and ((productionevents.recursStartDate<='".$start."' and productionevents.recursEndDate>'".$start."') or (productionevents.recursStartdate>'".$start."' and productionevents.recursStartDate<'".$end."')))")))->findAll('venueID='.$id);
		//print_r($Productionvenues);die();
        $item=array();
        foreach($Productionvenues as $Productionvenue)
        {
			$Productionevents = $Productionvenue->productionevents;
			$name='';
			foreach($Productionevents as $Productionevent)
			{
				$item = array_merge_recursive($item,$this->processEvent($Productionevent,$start,$end));
			}
        }   
        echo json_encode($item);
	}
	
	
	private function processEvent($Productionevent,$viewStart,$viewEnd)
	{
		$Venue=$Productionevent->productionvenue->venue;
		$image_url='';
		$show=$Productionevent->productionvenue->production->show;
		$production=$Productionevent->productionvenue->production;
		$id=0;
		if($Productionevent->type==0)
		{
			$id=$Productionevent->id;
			$title = $show->showName;
			$description = ($production->descr==''?$show->showDesc:$production->descr);
			$eventUrl = $production->createUrl();
			$eventDuration = isset($production->duration)?$production->duration:'NA';
			$eventIntermissions = isset($production->intermissions)?$production->intermissions:'NA';
			$eventEditUrl = yii::app()->createUrl('productionevent/update').'/'.$Productionevent->id;
			$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$production->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			}
			else
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$show->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
				}
				else
				{					
					$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
				}
			}			
		}
		else
		{
			if(!empty($Venue->venueownerships))
			{
				$Venueownership = Venueownership::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>$Venue->id,':userID'=>Yii::app()->user->id));
				if(empty($Venueownership))
				{
					$id=0;
					$title = 'Private event';
					$description = 'Information for this event is not available.';
					$eventUrl = '';
					$eventEditUrl = '';
					$eventDuration = '';
					$eventIntermissions = '';
					$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
				}
				else
				{
					$id=$Productionevent->id;
					$title = $show->showName;
					$description = ($production->descr==''?$show->showDesc:$production->descr).'This is a private event.';
					$eventUrl = $production->createUrl();
					$eventEditUrl = yii::app()->createUrl('productionevent/update').'/'.$Productionevent->id;
					$eventDuration = isset($production->duration)?$production->duration:'NA';
					$eventIntermissions = isset($production->intermissions)?$production->intermissions:'NA';
					$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$production->id);
					if(isset($profile_image->image->imageURL))
					{
						$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					}
					else
					{
						$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$show->id);
						if(isset($profile_image->image->imageURL))
						{
							$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						}
						else
						{					
							$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
						}
					}
				}
			}
			else
			{
				$id=$Productionevent->id;
				$title = $show->showName;
				$description = ($production->descr==''?$show->showDesc:$production->descr);
				$eventUrl = $production->createUrl();
				$eventEditUrl = yii::app()->createUrl('productionevent/update').'/'.$Productionevent->id;
				$eventDuration = isset($production->duration)?$production->duration:'NA';
				$eventIntermissions = isset($production->intermissions)?$production->intermissions:'NA';
				$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$production->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
				}
				else
				{
					$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$show->id);
					if(isset($profile_image->image->imageURL))
					{
						$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					}
					else
					{					
						$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
					}
				}
			}	
		}
		$item = array();
		if($Productionevent->recurs==0)
		{
			$item[] = $this->generate_event($id,$title,$description,$Productionevent,$eventUrl,$eventEditUrl,$image_url,$eventDuration,$eventIntermissions);
			return $item;
		}
		else
		{
			return $this->generate_repeating_event($id,$title,$description,$Productionevent,$eventUrl,$eventEditUrl,$image_url,$eventDuration,$eventIntermissions,$viewStart,$viewEnd);
		}
	}
	
	private function generate_event($id,$title,$description,$Productionevent,$eventUrl,$eventEditUrl,$imageUrl,$eventDuration,$eventIntermissions)
	{
		$start = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		//$end = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->endDate);
		$start = $start->format('Y-m-d\TH:i:00.000\Z');
		//$end = $end->format('Y-m-d\TH:i:00.000\Z');
		$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionevent->productionVenueID.' and linkType=1');	
		$linkItems=array();
		foreach($links as $link)
		{
			$linkItems[]=array('label'=> $link->label,'href'=>$link->href);
		}
		$item=array(
			'id'=>$id,
			'title'=>$title,
			'description'=>$description,
			'start'=>$start,
			//'end'=>$end,
			'eventUrl'=>$eventUrl,
			'eventEditUrl'=>$eventEditUrl,
			'eventDuration'=>$eventDuration,
			'eventIntermissions'=>$eventIntermissions,
			'imageUrl'=>$imageUrl,
			'ticketLinks'=>$linkItems
		);
		return $item;
	}
	
	
	private function generate_repeating_event($id,$title,$description,$Productionevent,$eventUrl,$eventEditUrl,$imageUrl,$eventDuration,$eventIntermissions,$viewStart,$viewEnd) {
		$startDate = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		//$endDate = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->endDate);
		$recursEndDate = DateTime::createFromFormat('!m-d-Y', $Productionevent->recursEndDate);
		$recursEndDate->setTime(23, 59, 59);
		$viewEnd = DateTime::createFromFormat('!Y-m-d', $viewEnd);
		$item =array();
		$item[]=$this->generate_event($id,$title,$description,$Productionevent,$eventUrl,$eventEditUrl,$imageUrl,$eventDuration,$eventIntermissions);
		$startDate = $this->get_next_date($startDate, $Productionevent->recurs);
		//$endDate = $this->get_next_date($endDate, $Productionevent->recurs);
		$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionevent->productionVenueID.' and linkType=1');	
		$linkItems=array();
		foreach($links as $link)
		{
			$linkItems[]=array('label'=> $link->label,'href'=>$link->href);
		}
		while ($startDate <= $recursEndDate&&$startDate < $viewEnd)
		{
			//$startDate = $this->get_next_date($startDate, $Productionevent->recurs);
			//$endDate = $this->get_next_date($endDate, $Productionevent->recurs);
			$item[]=array(
				'id'=>$id,
				'title'=>$title,
				'description'=>$description,
				'start'=>$startDate->format('Y-m-d\TH:i:00.000\Z'),
				//'end'=>$endDate->format('Y-m-d\TH:i:00.000\Z'),
				'eventUrl'=>$eventUrl,
				'eventDuration'=>$eventDuration,
				'eventIntermissions'=>$eventIntermissions,
				'eventEditUrl'=>$eventEditUrl,
				'imageUrl'=>$imageUrl,
				'ticketLinks'=>$linkItems
			);
			$startDate = $this->get_next_date($startDate, $Productionevent->recurs);
			//$endDate = $this->get_next_date($endDate, $Productionevent->recurs);
		}
		return $item;
	 }

	private function get_next_date($date, $int) {
		if ($int == 1)
			$date->add(new DateInterval('P1D'));
		if ($int == 2)
			$date->add(new DateInterval('P1W'));
		if ($int == 3)
			$date->add(new DateInterval('P1M'));
		return $date;
	}

	private function get_next_month($date, $n = 1) {
		$newDate = strtotime("+{$n} months", $date);
		// adjustment for events that repeat on the 29th, 30th and 31st of a month
		if (date('j', $date) !== (date('j', $newDate))) {
			$newDate = date($date,strtotime("+" .$n. " months"));
		}
		return $newDate;
	}

	private function get_next_year($date, $n = 1) {
		$newDate = strtotime("+{$n} years", $date);
		// adjustment for events that repeat on february 29th
		if (date('j', $date) !== (date('j', $newDate))) {
			$newDate = date($date,strtotime("+" . $n + 3 . " years"));
		}
		return $newDate;
	}
	
	
	
	public function actionWatchlistAdd()
	{
		$Venuewatchlist = Venuewatchlist::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>Yii::app()->request->getPost('venueID'),':userID'=>Yii::app()->user->id));
		if(empty($Venuewatchlist))
		{
			$Venuewatchlist = new Venuewatchlist;
			$Venuewatchlist->venueID=Yii::app()->request->getPost('venueID');
			$Venuewatchlist->userID=Yii::app()->user->id;
			if($Venuewatchlist->validate())
				$Venuewatchlist->save();
		}
	}
	
	public function actionWatchlistRemove()
	{
		$Venuewatchlist = Venuewatchlist::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>Yii::app()->request->getPost('venueID'),':userID'=>Yii::app()->user->id));
		if(!empty($Venuewatchlist))
			$Venuewatchlist->delete();
	}
	
	public function actionOwnershipClaim()
	{
		$Venueownership = Venueownership::model()->find("venueID=:venueID",array(':venueID'=>Yii::app()->request->getPost('venueID')));
		if(empty($Venueownership))
		{
			$Venueownership = new Venueownership;
			$Venueownership->venueID=Yii::app()->request->getPost('venueID');
			$Venueownership->userID=Yii::app()->user->id;
			if($Venueownership->validate())
				$Venueownership->save();
		}
	}
	
	public function actionOwnershipRelinquish()
	{
		$Venueownership = Venueownership::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>Yii::app()->request->getPost('venueID'),':userID'=>Yii::app()->user->id));
		if(!empty($Venueownership))
			$Venueownership->delete();
	}
	
	/**
	 * Deletes a profile image
	 */
	public function actionDeleteProfileImage()
	{
		$model=$this->loadModel(Yii::app()->request->getPost('venueID'));
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$model->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
		}
		
		$Profileimage = Profileimage::model()->findByPk(Yii::app()->request->getPost('id'))->delete();
		Yii::app()->user->setFlash('success', "Profile successfully updated.");
		//echo $this->renderPartial('//layouts/_flashes');
	}
	
	/**
	 * Upload ticket sale info if you are owner of venue and have access to reports
	 */
	public function actionUploadTicketSaleInfo($id)
	{
		$model=$this->loadModel($id);
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
		foreach($roles as $role) if($role->name == 'ReportUser') $reportAccess=true;
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$model->venueownerships))||!$reportAccess)
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to perform this operation.");
			$this->redirect($model->createUrl());
		}
		else
		{
			$source=2; /* Replace with source enumerator in the future */
			//Set up tttcVenue record
			
			$TTTCVenue = TTTCVenue::model()->find("tpVenueID=:id and source=:source",array(':id'=>$id,':source'=>$source));
			if(empty($TTTCVenue))
			{
				//Get max id for current source
				$TTTCVenue = new TTTCVenue;
				$criteria=new CDbCriteria;
				$criteria->select='MAX(id) AS id';
				$criteria->addColumnCondition(array('source' => 2));
				$row = $TTTCVenue->model()->find($criteria);
				$TTTCVenue=new TTTCVenue;
				if($row['id']+1<100001)
					$TTTCVenue->id=100001;
				else
					$TTTCVenue->id=$row['id']+1;
				$TTTCVenue->tpVenueID=$id;
				$TTTCVenue->venueName=$model->venueName;
				$TTTCVenue->addressID=$model->addressID;
				$TTTCVenue->source=$source;
				$TTTCVenue->save();
			}
			
			
			//Validate & process worksheet
			include 'PHPOffice/PHPExcel.php';
			$file=CUploadedFile::getInstanceByName('worksheet');
			$inputFileName = $file->getTempName();
			try {
				/** Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			} catch(PHPExcel_Reader_Exception $e) {
				Yii::app()->user->setFlash('error', "Error uploading file.");
			}
			$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
			$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			for($i=2;$i<=$highestRow;$i++)
			{
				$cellValue1 = $sheetData[$i]["A"]; // SaleID
				$cellValue2 = $sheetData[$i]["B"]; // ContactID
				$cellValue3 = $sheetData[$i]["C"]; // First Name
				$cellValue4 = $sheetData[$i]["D"]; // Last Name
				$cellValue5 = $sheetData[$i]["E"]; // Street Address
				$cellValue6 = $sheetData[$i]["F"]; // City
				$cellValue7 = $sheetData[$i]["G"]; // State
				$cellValue8 = $sheetData[$i]["H"]; // Postal Code
				$cellValue9 = $sheetData[$i]["I"]; // Event Title
				$cellValue10 = $sheetData[$i]["J"]; // Event Time
				$cellValue11 = $sheetData[$i]["K"]; // Order Origin
				$cellValue12 = $sheetData[$i]["L"]; // Type
				$cellValue13 = $sheetData[$i]["M"]; // Effective Item Price
				$cellValue14 = $sheetData[$i]["N"]; // Unit Discount Amount
				$cellValue15 = $sheetData[$i]["O"]; // Seat Assignment
				if($cellValue1==null) break;
				
				//Insert record into import table
				//$ImportTTTCTicketSale = ImportTTTCTicketSale::model()->find("salesID=:cellValue1 and source=:source",array(':cellValue1'=>$cellValue1,':source'=>$source));
				
				//Get max salesID for current source
				$ImportTTTCTicketSale = new ImportTTTCTicketSale;
				$criteria=new CDbCriteria;
				$criteria->select='MAX(salesID) AS salesID';
				$criteria->addColumnCondition(array('source' => 2));
				$row = $ImportTTTCTicketSale->model()->find($criteria);
				
				//Save row to import table	
				$ImportTTTCTicketSale=new ImportTTTCTicketSale;
				$ImportTTTCTicketSale->salesID=$row['salesID']+1;
				$ImportTTTCTicketSale->venueID=$TTTCVenue->id;
				$ImportTTTCTicketSale->purchaseDate=null;
				$ImportTTTCTicketSale->boxOffice=0;
				if($cellValue13==0)
					$ImportTTTCTicketSale->boxOfficeComp=1;
				else
					$ImportTTTCTicketSale->boxOfficeComp=0;
				$ImportTTTCTicketSale->refundInventory=0;
				$ImportTTTCTicketSale->refundSale=0;
				$ImportTTTCTicketSale->refundDate=null;
				$ImportTTTCTicketSale->refundReason=null;
				$ImportTTTCTicketSale->title=$cellValue9;
				$ImportTTTCTicketSale->eventID=null;
				$ImportTTTCTicketSale->eventDate=date("Y-m-d G:i:s", strtotime($cellValue10));;
				$ImportTTTCTicketSale->section=null;
				$ImportTTTCTicketSale->ticket=$cellValue12;
				$ImportTTTCTicketSale->qty=1;
				$ImportTTTCTicketSale->seat=$cellValue15;
				$ImportTTTCTicketSale->first=$cellValue3;
				$ImportTTTCTicketSale->last=$cellValue4;
				$ImportTTTCTicketSale->name=$cellValue3." ".$cellValue4;;
				$ImportTTTCTicketSale->billingName=$cellValue3." ".$cellValue4;
				$ImportTTTCTicketSale->billingAddress1=$cellValue5;
				$ImportTTTCTicketSale->billingAddress2=null;
				$ImportTTTCTicketSale->billingCity=$cellValue6;
				$ImportTTTCTicketSale->billingState=$cellValue7;
				$ImportTTTCTicketSale->billingZip=$cellValue8;
				$ImportTTTCTicketSale->email=null;
				$ImportTTTCTicketSale->phone=null;
				$ImportTTTCTicketSale->transactionID=null;
				$ImportTTTCTicketSale->invoiceID=$cellValue1;
				$ImportTTTCTicketSale->subtotal=null;
				$ImportTTTCTicketSale->fees=null;
				$ImportTTTCTicketSale->netTotal=$cellValue13;
				$ImportTTTCTicketSale->BOOrderDiscount=$cellValue14;
				$ImportTTTCTicketSale->organizationID=null;
				$ImportTTTCTicketSale->organization=null;
				$ImportTTTCTicketSale->source=$source;
				$ImportTTTCTicketSale->userID=Yii::app()->user->id;
				$ImportTTTCTicketSale->status=0;
				//else
				//{
				//	$ImportTTTCTicketSale->qty=$ImportTTTCTicketSale->qty+1;
				//	$ImportTTTCTicketSale->seat=(strlen($ImportTTTCTicketSale->seat.",".$cellValue15)<=255?$ImportTTTCTicketSale->seat.",".$cellValue15:substr($ImportTTTCTicketSale->seat,0,252)."...");
				//	$ImportTTTCTicketSale->BOOrderDiscount=$ImportTTTCTicketSale->BOOrderDiscount+$cellValue14;
				//	$ImportTTTCTicketSale->netTotal=$ImportTTTCTicketSale->netTotal+$cellValue13;
				//}
				$ImportTTTCTicketSale->save();
			}
			//Return success message
			Yii::app()->user->setFlash('success', "Ticket sale information successfully uploaded.");
			$this->render('view',array(
				'model'=>$model,
			));
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Venue the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Venue::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Venue $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='venue-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Generate a unique file name for uploaded files
	 */
	 
	protected function getUniqueFileName($existingFileName,$prefix)
	{
		$ext = strtolower(pathinfo($existingFileName, PATHINFO_EXTENSION));
		$prefix=($prefix=='')?'img':$prefix;
		while (true) {
			$uniqueFileName = uniqid($prefix, false).'.'.$ext;
			if (!file_exists(Yii::app()->basePath.'/../images/uploads/'.$uniqueFileName)) break;
		}
		return $uniqueFileName;
	}
	
	/**
	 * Supporting function for multi-file upload
	 */
	
	protected function fixFilesArray(&$file_post) {
		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);

		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}
		return $file_ary;
	}
}
