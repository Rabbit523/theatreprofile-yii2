<?php

class CompanyController extends Controller
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
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model=new Company;
		if(isset($_POST['Company'])&&isset($_POST['Address']))
		{
			$model->attributes=$_POST['Company'];
			$Companyaddress=new Companyaddress;
			$Companyaddress->address=new Address;
			$Companyaddress->address->attributes=$_POST['Address'];
			if($Companyaddress->address->save())
			{
				$Companyaddress->addressID=$Companyaddress->address->id;
				if($model->save())
				{	
					if(isset($_POST['crew']))
					{
						$crew=$_POST['crew'];
						if(isset($crew['new']))
						{
							$crews=$crew['new'];
							foreach($crews as $crew)
							{
								$Productioncompanycrew = new Productioncompanycrew;
								$Productioncompanycrew->productionID=$crew['productionID'];
								if($Productioncompanycrew->productionID==0)
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
									$Productioncompanycrew->productionID=$Production->id;
								}
								
								$Productioncompanycrew->roleID=$crew['roleID'];
								if($Productioncompanycrew->roleID==0)
								{
									$role = Role::model()->find("roleName='".$crew['roleName']."'");
									if(isset($role))
									{
										$Productioncompanycrew->roleID = $role->id;
									}
									else
									{
										$role = new Role;
										$role->roleName =$crew['roleName'];
										$role->departmentID = 0;
										$role->save();
										$Productioncompanycrew->roleID = $role->id;
									}
								}
								$Productioncompanycrew->companyID=$model->id;
								$Productioncompanycrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
								$Productioncompanycrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
								$Productioncompanycrew->save();
							}
						}
					}
				
					$Companyaddress->companyID=$model->id;
					$Companyaddress->save();
					
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
						$fileName = $this->getUniqueFileName($file->getName(),'c');
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
						$prodile_image->profileType=5; 
						$prodile_image->imageID=$image->id; 
						$prodile_image->imageType=1; 
						$prodile_image->save();
					}
					
					//Save contact information
					if($_POST['contactInfoID_facebook']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_facebook'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=1; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_facebook'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_googleplus']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_googleplus'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=2; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_twitter']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_twitter'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=3; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_twitter'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_instagram']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_instagram'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=4; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_instagram'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_website']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_website']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_website'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=5; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_website'];
					$Companycontactinfo->save();
					
					Yii::app()->user->setFlash('success', "Company successfully created.");
					$this->redirect(array('update','id'=>$model->id));
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
		if(!Yii::app()->user->checkAccess('Company.UpdateAccess',array('ownerships'=>$model->companyownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($model->createUrl());
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Company'])&&isset($_POST['Address']))
		{
			$model->attributes=$_POST['Company'];
			$Companyaddress = array_values($model->companyaddresses)[0];
			$Companyaddress->address = Address::model()->findByPk($Companyaddress->addressID);
			$Companyaddress->address->attributes=$_POST['Address'];
			
			if($Companyaddress->address->save())
			{
				if($model->save())
				{
					if(isset($_POST['crew']))
					{
						$crew=$_POST['crew'];
						if(isset($crew['new']))
						{
							$crews=$crew['new'];
							foreach($crews as $crew)
							{
								$Productioncompanycrew = new Productioncompanycrew;
								$Productioncompanycrew->productionID=$crew['productionID'];
								if($Productioncompanycrew->productionID==0)
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
									$Productioncompanycrew->productionID=$Production->id;
								}
								
								$Productioncompanycrew->roleID=$crew['roleID'];
								if($Productioncompanycrew->roleID==0)
								{
									$role = Role::model()->find("roleName='".$crew['roleName']."'");
									if(isset($role))
									{
										$Productioncompanycrew->roleID = $role->id;
									}
									else
									{
										$role = new Role;
										$role->roleName =$crew['roleName'];
										$role->departmentID = 0;
										$role->save();
										$Productioncompanycrew->roleID = $role->id;
									}
								}
								$Productioncompanycrew->companyID=$model->id;
								$Productioncompanycrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
								$Productioncompanycrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
								$Productioncompanycrew->save();
							}
						}
						if(isset($crew['existing']))
						{
							$crews=$crew['existing'];
							foreach($crews as $crew)
							{
								$Productioncompanycrew = Productioncompanycrew::model()->findByPk($crew['id']);
								$Productioncompanycrew->productionID=$crew['productionID'];
								if($Productioncompanycrew->productionID==0)
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
									$Productioncompanycrew->productionID=$Production->id;
								}
								$Productioncompanycrew->roleID=$crew['roleID'];
								if($Productioncompanycrew->roleID==0)
								{
									$role = Role::model()->find("roleName='".$crew['roleName']."'");
									if(isset($role))
									{
										$Productioncompanycrew->roleID = $role->id;
									}
									else
									{
										$role = new Role;
										$role->roleName =$crew['roleName'];
										$role->departmentID = 0;
										$role->save();
										$Productioncompanycrew->roleID = $role->id;
									}
								}
								$Productioncompanycrew->companyID=$model->id;
								$Productioncompanycrew->startDate=!empty($crew['startDate'])?$crew['startDate']:NULL;
								$Productioncompanycrew->endDate=!empty($crew['endDate'])?$crew['endDate']:NULL;
								$Productioncompanycrew->save();
							}
						}
					}
					$exist = Profileimage::model()->
					find("profileID = :profileID and profileType = 5 and imageType=1",array(':profileID'=>$id) );
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
							$fileName = $this->getUniqueFileName($file->getName(),'c');
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
							$prodile_image->profileType=5; 
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
							$fileName = $this->getUniqueFileName($file->getName(),'c');
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
							$profile_image->profileType=5; 
							$profile_image->imageID=$image->id; 
							$profile_image->imageType=2; 
							$profile_image->save();
						}
					}
					
					//Save contact information
					if($_POST['contactInfoID_facebook']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_facebook'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=1; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_facebook'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_googleplus']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_googleplus'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=2; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_twitter']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_twitter'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=3; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_twitter'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_instagram']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_instagram'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=4; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_instagram'];
					$Companycontactinfo->save();
					
					if($_POST['contactInfoID_website']!=0)
						$Companycontactinfo = Companycontactinfo::model()->findByPk($_POST['contactInfoID_website']);
					else
					{
						$Companycontactinfo = new Companycontactinfo;
						$Companycontactinfo->id = $_POST['contactInfoID_website'];
					}
					$Companycontactinfo->companyID=$model->id; 
					$Companycontactinfo->contactTypeID=5; 
					$Companycontactinfo->contactInfo=$_POST['contactInfo_website'];
					$Companycontactinfo->save();
					
					Yii::app()->user->setFlash('success', "Company successfully updated.");
					$this->redirect(array('update','id'=>$model->id));
				}
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Company');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$this->render('index');
	}
	
	public function actionProgram()
	{
		//$this->redirect(yii::app()->baseUrl.'/documents/FWFringe-Program-20160310.pdf');
		header('Location: success.html');
		Yii::app()->getRequest()->sendFile('FWFringe-Program-20160310.pdf', file_get_contents(Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.'/documents/FWFringe-Program-20160310.pdf'));
	}
	
	public function actionWatchlistAdd()
	{
		$Companywatchlist = Companywatchlist::model()->find("companyID=:companyID and userID=:userID",array(':companyID'=>Yii::app()->request->getPost('companyID'),':userID'=>Yii::app()->user->id));
		if(empty($Companywatchlist))
		{
			$Companywatchlist = new Companywatchlist;
			$Companywatchlist->companyID=Yii::app()->request->getPost('companyID');
			$Companywatchlist->userID=Yii::app()->user->id;
			if($Companywatchlist->validate())
				$Companywatchlist->save();
		}
	}
	
	public function actionWatchlistRemove()
	{
		$Companywatchlist = Companywatchlist::model()->find("companyID=:companyID and userID=:userID",array(':companyID'=>Yii::app()->request->getPost('companyID'),':userID'=>Yii::app()->user->id));
		if(!empty($Companywatchlist))
			$Companywatchlist->delete();
	}
	
	public function actionOwnershipClaim()
	{
		$Companyownership = Companyownership::model()->find("companyID=:companyID",array(':companyID'=>Yii::app()->request->getPost('companyID')));
		if(empty($Companyownership))
		{
			$Companyownership = new Companyownership;
			$Companyownership->companyID=Yii::app()->request->getPost('companyID');
			$Companyownership->userID=Yii::app()->user->id;
			if($Companyownership->validate())
				$Companyownership->save();
		}
	}
	
	public function actionOwnershipRelinquish()
	{
		$Companyownership = Companyownership::model()->find("companyID=:companyID and userID=:userID",array(':companyID'=>Yii::app()->request->getPost('companyID'),':userID'=>Yii::app()->user->id));
		if(!empty($Companyownership))
			$Companyownership->delete();
	}
	
	/**
	 * Deletes a profile image
	 */
	public function actionDeleteProfileImage()
	{
		$model=$this->loadModel(Yii::app()->request->getPost('companyID'));
		if(!Yii::app()->user->checkAccess('Company.UpdateAccess',array('ownerships'=>$model->companyownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
		}
		
		$Profileimage = Profileimage::model()->findByPk(Yii::app()->request->getPost('id'))->delete();
		Yii::app()->user->setFlash('success', "Profile successfully updated.");
		//echo $this->renderPartial('//layouts/_flashes');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Company the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Company::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Company $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='company-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionCompanyLists()
    {
		$term = Yii::app()->request->getQuery('term');
		$optimizedterm=str_replace(' ', '%', $term);
        $companies = Company::model()->findAll('companyName like "%'.$optimizedterm.'%"');
        $item=array();
        foreach($companies as $company)
        {
			$ComapanyAddress = array_values($company->companyaddresses)[0];
        	$item[]=array(
        		'id'=>$company->id,
        		'value'=>$company->companyName,
				'desc'=>(!empty($ComapanyAddress->address->city)?$ComapanyAddress->address->city.', ':'').$ComapanyAddress->address->country->countryCode
        	);
        }
        echo json_encode($item);
    }
	
	
	public function  actionRemovecrew($id,$companyID)
	{
		$Productioncompanycrew=Productioncompanycrew::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$companyID));
	}
	
	
	/**
	 * Displays analytics for a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAnalytics($id)
	{
		$model=$this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Company.UpdateAccess',array('ownerships'=>$model->companyownerships)))
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
