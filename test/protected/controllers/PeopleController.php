<?php

class PeopleController extends Controller
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
		return 'index,view';
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Individual;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Individual']))
		{
			$model->attributes=$_POST['Individual'];
			if($model->save())
			{
				if(isset($_POST['cast']))
				{
					$cast=$_POST['cast'];
					if(isset($cast['new']))
					{
						$casts=$cast['new'];
						foreach($casts as $cast)
						{	
							$Productioncast = new Productioncast;
							$Productioncast->productionID=$cast['productionID'];
							if($Productioncast->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $cast['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $cast['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$cast['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncast->productionID=$Production->id;
							}
							$Productioncast->individualID=$model->id;
							$Productioncast->roleName=$cast['roleName'];
							$Productioncast->startDate=!empty($cast['startDate'])?$cast['startDate']:NULL;
							$Productioncast->endDate=!empty($cast['endDate'])?$cast['endDate']:NULL;
							$Productioncast->save();
						}
					}
				}

				if(isset($_POST['crew']))
				{
					$crew=$_POST['crew'];
					if(isset($crew['new']))
					{
						$crews=$crew['new'];
						foreach($crews as $crew)
						{
							$Productioncrew = new Productioncrew;
							$Productioncrew->productionID=$crew['productionID'];
							if($Productioncrew->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $crew['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $crew['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$crew['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncrew->productionID=$Production->id;
							}
							
							$Productioncrew->roleID=$crew['roleID'];
							if($Productioncrew->roleID==0)
							{
								$role = Role::model()->find("roleName='".$crew['roleName']."'");
								if(isset($role))
								{
									$Productioncrew->roleID = $role->id;
								}
								else
								{
									$role = new Role;
									$role->roleName =$crew['roleName'];
									$role->departmentID = 0;
									$role->save();
									$Productioncrew->roleID = $role->id;
								}
							}
							$Productioncrew->profileID=$model->id;
							$Productioncrew->profileType=1;						
							$Productioncrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
							$Productioncrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
							$Productioncrew->save();
						}
					}
				}
				
				
				if(isset($_POST['creator']))
				{
					$creators=$_POST['creator'];
					if(isset($creators['new']))
					{
						$newCreators=$creators['new'];
						foreach($newCreators as $newCreator)
						{
							$Showcreator = new Showcreator;
							$Showcreator->showID=$newCreator['showID'];
							if($Showcreator->showID==0)
							{
								$Show = new Show;
								$Show->showName = $newCreator['showName'];
								$Show->categoryID = 0;
								$Show->save();
								$Showcreator->showID=$Show->id;
							}
							$Showcreator->roleID=$newCreator['roleID'];
							$Showcreator->individualID=$model->id;
							$Showcreator->save();
						}
					}
				}

					
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
					$fileName = $this->getUniqueFileName($file->getName(),'p');
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
					$prodile_image->profileType=3; 
					$prodile_image->imageID=$image->id; 
					$prodile_image->imageType=1; 
					$prodile_image->save();
				}
				
				//Save contact information
				if($_POST['contactInfoID_facebook']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_facebook'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=1; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_facebook'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_googleplus']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_googleplus'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=2; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_twitter']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_twitter'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=3; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_twitter'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_instagram']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_instagram'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=4; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_instagram'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_website']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_website']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_website'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=5; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_website'];
				$Individualcontactinfo->save();
				
				Yii::app()->user->setFlash('success', "Profile successfully created.");
				$this->redirect(array('update','id'=>$model->id));
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
		if(!Yii::app()->user->checkAccess('People.UpdateAccess',array('ownerships'=>$model->individualownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($model->createUrl());
		}

		if(isset($_POST['Individual']))
		{
			$model->attributes=$_POST['Individual'];
			if($model->save())
			{
				if(isset($_POST['cast']))
				{
					$cast=$_POST['cast'];
					if(isset($cast['new']))
					{
						$casts=$cast['new'];
						foreach($casts as $cast)
						{	
							$Productioncast = new Productioncast;
							$Productioncast->productionID=$cast['productionID'];
							if($Productioncast->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $cast['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $cast['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$cast['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncast->productionID=$Production->id;
							}
							$Productioncast->individualID=$model->id;
							$Productioncast->roleName=$cast['roleName'];
							$Productioncast->startDate=!empty($cast['startDate'])?$cast['startDate']:NULL;
							$Productioncast->endDate=!empty($cast['endDate'])?$cast['endDate']:NULL;
							$Productioncast->save();
						}
					}
					
					if(isset($cast['existing']))
					{
						$casts=$cast['existing'];
						foreach($casts as $cast)
						{	
							$Productioncast = Productioncast::model()->findByPk($cast['id']);
							$Productioncast->productionID=$cast['productionID'];
							if($Productioncast->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $cast['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $cast['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$cast['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncast->productionID=$Production->id;
							}
							$Productioncast->individualID=$model->id;
							$Productioncast->roleName=$cast['roleName'];
							$Productioncast->startDate=!empty($cast['startDate'])?$cast['startDate']:NULL;
							$Productioncast->endDate=!empty($cast['endDate'])?$cast['endDate']:NULL;
							$Productioncast->save();
						}
					}
				}

				if(isset($_POST['crew']))
				{
					$crew=$_POST['crew'];
					if(isset($crew['new']))
					{
						$crews=$crew['new'];
						foreach($crews as $crew)
						{
							$Productioncrew = new Productioncrew;
							$Productioncrew->productionID=$crew['productionID'];
							if($Productioncrew->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $crew['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $crew['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$crew['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncrew->productionID=$Production->id;
							}
							
							$Productioncrew->roleID=$crew['roleID'];
							if($Productioncrew->roleID==0)
							{
								$role = Role::model()->find("roleName='".$crew['roleName']."'");
								if(isset($role))
								{
									$Productioncrew->roleID = $role->id;
								}
								else
								{
									$role = new Role;
									$role->roleName =$crew['roleName'];
									$role->departmentID = 0;
									$role->save();
									$Productioncrew->roleID = $role->id;
								}
							}
							$Productioncrew->profileID=$model->id;
							$Productioncrew->profileType=1;						
							$Productioncrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
							$Productioncrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
							$Productioncrew->save();
						}
					}
					if(isset($crew['existing']))
					{
						$crews=$crew['existing'];
						foreach($crews as $crew)
						{
							$Productioncrew = Productioncrew::model()->findByPk($crew['id']);
							$Productioncrew->productionID=$crew['productionID'];
							if($Productioncrew->productionID==0)
							{
								$Production = new Production;
								$Production->showID = $crew['showID'];
								if($Production->showID==0)
								{
									$Show = new Show;
									$Show->showName = $crew['showName'];
									$Show->categoryID = 0;
									$Show->save();
									$Production->showID=$Show->id;
								}
								$Production->startDate=NULL;
								$Production->endDate=NULL;
								$Production->save();
								Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$crew['showName'].' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/production/update',array('id'=>$Production->id))."'>Edit</a></strong></div>");
								$Productioncrew->productionID=$Production->id;
							}
							$Productioncrew->roleID=$crew['roleID'];
							if($Productioncrew->roleID==0)
							{
								$role = Role::model()->find("roleName='".$crew['roleName']."'");
								if(isset($role))
								{
									$Productioncrew->roleID = $role->id;
								}
								else
								{
									$role = new Role;
									$role->roleName =$crew['roleName'];
									$role->departmentID = 0;
									$role->save();
									$Productioncrew->roleID = $role->id;
								}
							}
							$Productioncrew->profileID=$model->id;
							$Productioncrew->profileType=1;						
							$Productioncrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
							$Productioncrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
							$Productioncrew->save();
						}
					}
					
					
				}
				
				
				if(isset($_POST['creator']))
				{
					$creators=$_POST['creator'];
					if(isset($creators['new']))
					{
						$newCreators=$creators['new'];
						foreach($newCreators as $newCreator)
						{
							$Showcreator = new Showcreator;
							$Showcreator->showID=$newCreator['showID'];
							if($Showcreator->showID==0)
							{
								$Show = new Show;
								$Show->showName = $newCreator['showName'];
								$Show->categoryID = 0;
								$Show->save();
								$Showcreator->showID=$Show->id;
							}
							$Showcreator->roleID=$newCreator['roleID'];
							$Showcreator->individualID=$model->id;
							$Showcreator->save();
						}
					}
					if(isset($creators['existing']))
					{
						$existingCreators=$_POST['creator']['existing'];
						foreach($existingCreators as $existingCreator)
						{
							$Showcreator = Showcreator::model()->findByPk($existingCreator['id']);
							$Showcreator->showID=$existingCreator['showID'];
							if($Showcreator->showID==0)
							{
								$Show = new Show;
								$Show->showName = $existingCreator['showName'];
								$Show->categoryID = 0;
								$Show->save();
								$Showcreator->showID=$Show->id;
							}
							$Showcreator->roleID=$existingCreator['roleID'];
							$Showcreator->individualID=$model->id;
							$Showcreator->save();
						}
					}
				}


				$exist = Profileimage::model()->
				find("profileID = :profileID and profileType = 3 and imageType=1",array(':profileID'=>$model->id) );
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
						$fileName = $this->getUniqueFileName($file->getName(),'p');
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
						$prodile_image->profileType=3; 
						$prodile_image->imageID=$image->id; 
						$prodile_image->imageType=1; 
						$prodile_image->save();						
					}
				}
				else
				{
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
						$fileName = $this->getUniqueFileName($file->getName(),'p');
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
						$profile_image->profileType=3; 
						$profile_image->imageID=$image->id; 
						$profile_image->imageType=2; 
						$profile_image->save();
					}
				}
				
				//Save contact information
				if($_POST['contactInfoID_facebook']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_facebook'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=1; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_facebook'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_googleplus']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_googleplus'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=2; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_twitter']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_twitter'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=3; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_twitter'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_instagram']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_instagram'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=4; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_instagram'];
				$Individualcontactinfo->save();
				
				if($_POST['contactInfoID_website']!=0)
					$Individualcontactinfo = Individualcontactinfo::model()->findByPk($_POST['contactInfoID_website']);
				else
				{
					$Individualcontactinfo = new Individualcontactinfo;
					$Individualcontactinfo->id = $_POST['contactInfoID_website'];
				}
				$Individualcontactinfo->individualID=$model->id; 
				$Individualcontactinfo->contactTypeID=5; 
				$Individualcontactinfo->contactInfo=$_POST['contactInfo_website'];
				$Individualcontactinfo->save();
				
				Yii::app()->user->setFlash('success', "Profile successfully updated.");
				$this->redirect(array('update','id'=>$model->id));	
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionWatchlistAdd()
	{
		$Individualwatchlist = Individualwatchlist::model()->find("individualID=:individualID and userID=:userID",array(':individualID'=>Yii::app()->request->getPost('individualID'),':userID'=>Yii::app()->user->id));
		if(empty($Individualwatchlist))
		{
			$Individualwatchlist = new Individualwatchlist;
			$Individualwatchlist->individualID=Yii::app()->request->getPost('individualID');
			$Individualwatchlist->userID=Yii::app()->user->id;
			if($Individualwatchlist->validate())			
				$Individualwatchlist->save();
		}
	}
	
	public function actionWatchlistRemove()
	{
		$Individualwatchlist = Individualwatchlist::model()->find("individualID=:individualID and userID=:userID",array(':individualID'=>Yii::app()->request->getPost('individualID'),':userID'=>Yii::app()->user->id));
		if(!empty($Individualwatchlist))
			$Individualwatchlist->delete();
	}
	
	public function actionOwnershipClaim()
	{
		$Individualownership = Individualownership::model()->find("individualID=:individualID",array(':individualID'=>Yii::app()->request->getPost('individualID')));
		if(empty($Individualownership))
		{
			$Individualownership = new Individualownership;
			$Individualownership->individualID=Yii::app()->request->getPost('individualID');
			$Individualownership->userID=Yii::app()->user->id;
			if($Individualownership->validate())
				$Individualownership->save();
		}
	}
	
	public function actionOwnershipRelinquish()
	{
		$Individualownership = Individualownership::model()->find("individualID=:individualID and userID=:userID",array(':individualID'=>Yii::app()->request->getPost('individualID'),':userID'=>Yii::app()->user->id));
		if(!empty($Individualownership))
			$Individualownership->delete();
	}
	
	/**
	 * Deletes a profile image
	 */
	public function actionDeleteProfileImage()
	{
		$model=$this->loadModel(Yii::app()->request->getPost('individualID'));
		if(!Yii::app()->user->checkAccess('People.UpdateAccess',array('ownerships'=>$model->individualownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
		}
		
		$Profileimage = Profileimage::model()->findByPk(Yii::app()->request->getPost('id'))->delete();
		Yii::app()->user->setFlash('success', "Profile successfully updated.");
		//echo $this->renderPartial('//layouts/_flashes');
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Individual');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Individual the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Individual::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Individual $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='individual-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionCountryLists()
    {
    	
        $term = Yii::app()->request->getQuery('term');
       //echo $term ;
        $Countrys = Country::model()->findAll('countryName like "%'.$term.'%"');
        //print_r($creators);
       // echo json_encode($creators);
        $item=array();
        foreach($Countrys as $Country)
        {
        	$item[]=array(
        		'id'=>$Country->id,
        		'value'=>$Country->countryName
        		);
        }
        
        echo json_encode($item);
    }


    public function actionProductionLists()
    {
        $term = Yii::app()->request->getQuery('term');
        $productions = Production::model()->with(
			array('show'=>array('condition'=>'showName like "%'.$term.'%"'),
		))->with('productionvenues.venue.address.country')->findAll();
        $item=array();
        foreach($productions as $production)
        {
			if(count($production->productionvenues)>1)
			{
				$item[]=array(
					'id'=>$production->id,
					'value'=>$production->show->showName.' - Multiple venues'
				);
			}
			else if(count($production->productionvenues)==1)
			{
				$productionvenue = array_values($production->productionvenues)[0];
				$item[]=array(
					'id'=>$production->id,
					'value'=>$production->show->showName.' - '.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->country->countryCode
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

    public function  actionRemovecreator($id,$individualID)
	{
		$Showcreator=Showcreator::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$individualID));

	}

	public function  actionRemovecast($id,$individualID)
	{
		$Productioncast=Productioncast::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$individualID));
	}

	public function  actionRemovecrew($id,$individualID)
	{
		$Productioncrew=Productioncrew::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$individualID));
	}
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAnalytics($id)
	{
		$model=$this->loadModel($id);
		if(!Yii::app()->user->checkAccess('People.UpdateAccess',array('ownerships'=>$model->individualownerships)))
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
