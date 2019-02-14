<?php

class ProductionController extends Controller
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
		$model=$this->loadModel($id);
		$events=array();
		if(count($model->productionvenues)==1){
			$Productionvenue=$model->productionvenues[0];
			$Productionevents = $Productionvenue->productionevents;
			$date=date('m-d-Y H:i');
			foreach($Productionevents as $Productionevent){
				if($Productionevent->type==0)
				{
					if($Productionevent->recurs!=0)
					{
						$recursEndDate = DateTime::createFromFormat('!m-d-Y', $Productionevent->recursEndDate);
						$recursEndDate->setTime(23, 59, 59);
					}
					$startDate=DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
					if(($Productionevent->recurs==0&&$startDate>$date)||($Productionevent->recurs!=0&&$recursEndDate>$date))
					{
						$events = array_merge_recursive($events,$this->processEvent($Productionevent,$date));
					}
				}
			}
			usort($events, array($this, "date_compare"));
		}
		
		$this->render('view',array(
			'model'=>$model,'events'=>$events
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($show=null)
	{
		
		$model = new Production;
		if(isset($show))
		{
			$model->show = Show::model()->findByPk($show);
			$model->showID = $model->show->id;
		}
		else
		{
			$model->show = new Show;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Production'])&&isset($_POST['Show']))
		{
			$model->attributes=$_POST['Production'];
			$model->show->attributes=$_POST['Show'];
			if($model->showID == 0)
			{
				$model->show->attributes=$_POST['Show'];			
				$model->show->save();
				$model->showID=$model->show->id;
			}
			
			if($model->save())
			{
				if(isset($_POST['venue']))
				{
					$venue=$_POST['venue'];
					if(isset($venue['new']))
					{
						foreach($venue['new'] as $newVenue)
						{
							$id=$newVenue['venueID'];
							if($id==0)
							{
								$address = new Address;
								$address->addr1 = 'Not Available';
								$address->countryID = 0;
								if($address->save())
								{
									$Venue = new Venue;
									$Venue->venueName=$newVenue['venueName'];
									$Venue->addressID=$address->id;
									$Venue->save();
									Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$Venue->venueName.' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/venue/update',array('id'=>$Venue->id))."'>Edit</a></strong></div>");
									$id=$Venue->id;
								}
							}
							
							$Productionvenue = new Productionvenue;
							$Productionvenue->productionID=$model->id;
							$Productionvenue->venueID=$id;
							$Productionvenue->startDate=!empty($newVenue['startDate'])?$newVenue['startDate']:NULL;
							$Productionvenue->endDate=!empty($newVenue['endDate'])?$newVenue['endDate']:NULL;
							$Productionvenue->save();
						}
					}
				}

				if(isset($_POST['cast']))
				{
					$cast=$_POST['cast'];
					if(isset($cast['new']))
					{
						foreach($cast['new'] as $newCast)
						{
							$id=$newCast['individualID'];
							if($newCast['individualID']==0)
							{
								$name_array=array();
								$name_array=explode(' ', $newCast['castName']);

								$persion = new Individual;
								if(count($name_array)>1)
								{
									if(count($name_array)==2)
									{
										$persion->firstName=$name_array[0];
										$persion->lastName=$name_array[1];
									}
									if(count($name_array)==3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
									}
									if(count($name_array)>3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
										$persion->suffix=$name_array[3];
									}
								}
								else
								{
									$persion->firstName=$name_array[0];
								}
								
								$persion->countryID=0;
								$persion->individualType=3;
								$persion->save();
								$id=$persion->id;
							}

							
							$Productioncast = new Productioncast;
							$Productioncast->individualID=$id;
							$Productioncast->productionID=$model->id;
							$Productioncast->roleName=$newCast['roleName'];
							$Productioncast->startDate=!empty($newCast['startDate'])?$newCast['startDate']:NULL;
							$Productioncast->endDate=!empty($newCast['endDate'])?$newCast['endDate']:NULL;
							$Productioncast->save();
						}
					}
				}
				
				if(isset($_POST['crew']))
				{
					$crew=$_POST['crew'];
					if(isset($crew['new']))
					{
						foreach($crew['new'] as $newCrew)
						{
							$id=$newCrew['profileID'];
							$crewType=$newCrew['crewType'];
							if($crewType==1)
							{
								if($id==0)
								{
									$name_array=explode(' ', $newCrew['crewName']);

									$persion = new Individual;
									if(count($name_array)>1)
									{
										if(count($name_array)==2)
										{
											$persion->firstName=$name_array[0];
											$persion->lastName=$name_array[1];
										}
										if(count($name_array)==3)
										{
											$persion->firstName=$name_array[0];
											$persion->middleName=$name_array[1];
											$persion->lastName=$name_array[2];
										}
										if(count($name_array)>3)
										{
											$persion->firstName=$name_array[0];
											$persion->middleName=$name_array[1];
											$persion->lastName=$name_array[2];
											$persion->suffix=$name_array[3];
										}
									}
									else
									{
										$persion->firstName=$name_array[0];
									}
									
									$persion->countryID=0;
									$persion->individualType=3;
									$persion->save();
									$id=$persion->id;
								}
							}
							else
							{
								if($id==0)
								{
									$companyName = $newCrew['crewName'];
									$company = new Company;
									$company->companyName = $companyName;
									$address=new Address;
									$address->addr1='Not Available';
									$address->countryID=0;
									$address->save();
									$company->save();
									$companyAddress = new Companyaddress;
									$companyAddress->companyID = $company->id;
									$companyAddress->addressID = $address->id;
									$companyAddress->save();
									$id=$company->id;
								}
							}
							
							$roleID=$newCrew['roleID'];
							if($roleID==0)
							{
								$criteria = new CDbCriteria();
								$criteria->addSearchCondition('rolename', $newCrew['roleName']);
								$role = Role::model()->find($criteria);
								if(isset($role))
								{
									$roleID = $role->id;
								}
								else
								{
									$role = new Role;
									$role->roleName =$newCrew['roleName'];
									$role->departmentID = 0;
									$role->save();
									$roleID = $role->id;
								}
							}
							
							if($crewType==1)
							{
								$Productioncrew = new Productioncrew;
								$Productioncrew->profileID=$id;
								$Productioncrew->profileType=1;
								$Productioncrew->productionID=$model->id;
								$Productioncrew->roleID=$roleID;
								$Productioncrew->startDate=!empty($newCrew['startDate'])?$newCrew['startDate']:NULL;
								$Productioncrew->endDate=!empty($newCrew['endDate'])?$newCrew['endDate']:NULL;
								$Productioncrew->save();
							}
							else
							{
								$Productioncompanycrew = new Productioncompanycrew;
								$Productioncompanycrew->companyID=$id;
								$Productioncompanycrew->productionID=$model->id;
								$Productioncompanycrew->roleID=$roleID;
								$Productioncompanycrew->startDate=!empty($newCrew['startDate'])?$newCrew['startDate']:NULL;
								$Productioncompanycrew->endDate=!empty($newCrew['endDate'])?$newCrew['endDate']:NULL;
								$Productioncompanycrew->save();
							}
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
					$imageOriginal = call_user_func('imagecreatefrom'.$types[$originalType],$imageOriginalPath);
					$imageResized = imagecreatetruecolor($resizedWidth, $resizedHeight);
					imagecopyresampled($imageResized, $imageOriginal, 0, 0, $cropX, $cropY, $resizedWidth, $resizedHeight, $resizedWidth, $resizedHeight);
					$fileName = $this->getUniqueFileName($file->getName(),'pr');
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
					$prodile_image->profileType=2; 
					$prodile_image->imageID=$image->id; 
					$prodile_image->imageType=1; 
					$prodile_image->save();
				}
				
				//Save contact information
				if($_POST['contactInfoID_facebook']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_facebook'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=1; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_facebook'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_googleplus']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_googleplus'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=2; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_twitter']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_twitter'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=3; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_twitter'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_instagram']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_instagram'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=4; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_instagram'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_website']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_website']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_website'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=5; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_website'];
				$Productioncontactinfo->save();
			
				Yii::app()->user->setFlash('success', "Production successfully created.");
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
		if(!Yii::app()->user->checkAccess('Production.UpdateAccess',array('ownerships'=>$model->productionownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($model->createUrl());
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Production'])&&isset($_POST['Show']))
		{
			$model->attributes=$_POST['Production'];
			if($model->showID == 0)
			{
				$show = new Show();
				$show->attributes=$_POST['Show'];			
				$show->save();
				$model->showID=$show->id;
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
			
				if(isset($_POST['venue']))
				{
					$venue=$_POST['venue'];
					if(isset($venue['new']))
					{
						foreach($venue['new'] as $newVenue)
						{
							$id=$newVenue['venueID'];
							if($id==0)
							{
								$address = new Address;
								$address->addr1 = 'Not Available';
								$address->countryID = 0;
								if($address->save())
								{
									$Venue = new Venue;
									$Venue->venueName=$newVenue['venueName'];
									$Venue->addressID=$address->id;
									$Venue->save();
									Yii::app()->user->setFlash('notify',Yii::app()->user->getFlash('notify', '')."<div class='well clearfix'><strong>".$Venue->venueName.' '."<a class='btn btn-primary btn-small pull-right' target='_blank' href='".yii::app()->createUrl('/venue/update',array('id'=>$Venue->id))."'>Edit</a></strong></div>");
									$id=$Venue->id;
								}
							}
							
							$Productionvenue = new Productionvenue;
							$Productionvenue->productionID=$model->id;
							$Productionvenue->venueID=$id;
							$Productionvenue->startDate=!empty($newVenue['startDate'])?$newVenue['startDate']:NULL;
							$Productionvenue->endDate=!empty($newVenue['endDate'])?$newVenue['endDate']:NULL;
							$Productionvenue->save();							
						}
					}
					if(isset($venue['existing']))
					{
						foreach($venue['existing'] as $existingVenue)
						{
							$id=$existingVenue['venueID'];
							if($id==0)
							{
								$address = new Address;
								$address->addr1 = 'Not Available';
								$address->countryID = 0;
								if($address->save())
								{
									$Venue = new Venue;
									$Venue->venueName=$existingVenue['venueName'];
									$Venue->addressID=$address->id;
									$Venue->save();
									$id=$Venue->id;
								}
							}
							
							$Productionvenue = Productionvenue::model()->findByPk($existingVenue['id']);
							$Productionvenue->venueID=$id;
							$Productionvenue->startDate=!empty($existingVenue['startDate'])?$existingVenue['startDate']:NULL;
							$Productionvenue->endDate=!empty($existingVenue['endDate'])?$existingVenue['endDate']:NULL;
							$Productionvenue->save();
						}
					}
				}

				if(isset($_POST['cast']))
				{
					$cast=$_POST['cast'];
					if(isset($cast['new']))
					{
						foreach($cast['new'] as $newCast)
						{
							$id=$newCast['individualID'];
							if($newCast['individualID']==0)
							{
								$name_array=array();
								$name_array=explode(' ', $newCast['castName']);

								$persion = new Individual;
								if(count($name_array)>1)
								{
									if(count($name_array)==2)
									{
										$persion->firstName=$name_array[0];
										$persion->lastName=$name_array[1];
									}
									if(count($name_array)==3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
									}
									if(count($name_array)>3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
										$persion->suffix=$name_array[3];
									}
								}
								else
								{
									$persion->firstName=$name_array[0];
								}
								
								$persion->countryID=0;
								$persion->individualType=3;
								$persion->validate();
								$persion->save();
								$id=$persion->id;
							}

							
							$Productioncast = new Productioncast;
							$Productioncast->individualID=$id;
							$Productioncast->productionID=$model->id;
							$Productioncast->roleName=$newCast['roleName'];
							$Productioncast->startDate=!empty($newCast['startDate'])?$newCast['startDate']:NULL;
							$Productioncast->endDate=!empty($newCast['endDate'])?$newCast['endDate']:NULL;
							$Productioncast->save();
						}
					}
					
					if(isset($cast['existing']))
					{
						foreach($cast['existing'] as $existingcast)
						{
							$id=$existingcast['individualID'];
							if($existingcast['individualID']==0)
							{
								$name_array=array();
								$name_array=explode(' ', $existingcast['castName']);

								$persion = new Individual;
								if(count($name_array)>1)
								{
									if(count($name_array)==2)
									{
										$persion->firstName=$name_array[0];
										$persion->lastName=$name_array[1];
									}
									if(count($name_array)==3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
									}
									if(count($name_array)>3)
									{
										$persion->firstName=$name_array[0];
										$persion->middleName=$name_array[1];
										$persion->lastName=$name_array[2];
										$persion->suffix=$name_array[3];
									}
								}
								else
								{
									$persion->firstName=$name_array[0];
								}
								
								$persion->countryID=0;
								$persion->individualType=2;
								$persion->save();
								$id=$persion->id;
							}
							$Productioncast = Productioncast::model()->findByPk($existingcast['id']);
							$Productioncast->individualID=$id;
							$Productioncast->roleName=$existingcast['roleName'];
							$Productioncast->startDate=!empty($existingcast['startDate'])?$existingcast['startDate']:NULL;
							$Productioncast->endDate=!empty($existingcast['endDate'])?$existingcast['endDate']:NULL;
							$Productioncast->save();
						}
					}
				}

				if(isset($_POST['crew']))
				{
					$crew=$_POST['crew'];
					if(isset($crew['new']))
					{
						foreach($crew['new'] as $newCrew)
						{
							$id=$newCrew['profileID'];
							$crewType=$newCrew['crewType'];
							if($crewType==1)
							{
								if($id==0)
								{
									$name_array=explode(' ', $newCrew['crewName']);

									$persion = new Individual;
									if(count($name_array)>1)
									{
										if(count($name_array)==2)
										{
											$persion->firstName=$name_array[0];
											$persion->lastName=$name_array[1];
										}
										if(count($name_array)==3)
										{
											$persion->firstName=$name_array[0];
											$persion->middleName=$name_array[1];
											$persion->lastName=$name_array[2];
										}
										if(count($name_array)>3)
										{
											$persion->firstName=$name_array[0];
											$persion->middleName=$name_array[1];
											$persion->lastName=$name_array[2];
											$persion->suffix=$name_array[3];
										}
									}
									else
									{
										$persion->firstName=$name_array[0];
									}
									
									$persion->countryID=0;
									$persion->individualType=3;
									$persion->save();
									$id=$persion->id;
								}
							}
							else
							{
								if($id==0)
								{
									$companyName = $newCrew['crewName'];
									$company = new Company;
									$company->companyName = $companyName;
									$address=new Address;
									$address->addr1='Not Available';
									$address->countryID=0;
									$address->save();
									$company->save();
									$companyAddress = new Companyaddress;
									$companyAddress->companyID = $company->id;
									$companyAddress->addressID = $address->id;
									$companyAddress->save();
									$id=$company->id;
								}
							}
							
							$roleID=$newCrew['roleID'];
							if($roleID==0)
							{
								$criteria = new CDbCriteria();
								$criteria->addSearchCondition('rolename', $newCrew['roleName']);
								$role = Role::model()->find($criteria);
								if(isset($role))
								{
									$roleID = $role->id;
								}
								else
								{
									$role = new Role;
									$role->roleName =$newCrew['roleName'];
									$role->departmentID = 0;
									$role->save();
									$roleID = $role->id;
								}
							}
							
							if($crewType==1)
							{
								$Productioncrew = new Productioncrew;
								$Productioncrew->profileID=$id;
								$Productioncrew->profileType=1;
								$Productioncrew->productionID=$model->id;
								$Productioncrew->roleID=$roleID;
								$Productioncrew->startDate=!empty($newCrew['startDate'])?$newCrew['startDate']:NULL;
								$Productioncrew->endDate=!empty($newCrew['endDate'])?$newCrew['endDate']:NULL;
								$Productioncrew->save();
							}
							else
							{
								$Productioncompanycrew = new Productioncompanycrew;
								$Productioncompanycrew->companyID=$id;
								$Productioncompanycrew->productionID=$model->id;
								$Productioncompanycrew->roleID=$roleID;
								$Productioncompanycrew->startDate=!empty($newCrew['startDate'])?$newCrew['startDate']:NULL;
								$Productioncompanycrew->endDate=!empty($newCrew['endDate'])?$newCrew['endDate']:NULL;
								$Productioncompanycrew->save();
							}
						}
					}
					
					if(isset($crew['existing']))
					{
						foreach($crew['existing'] as $existingCrew)
						{
							$id=$existingCrew['profileID'];
							$crewType=$existingCrew['crewType'];
							$crewTypePrev=$existingCrew['crewTypePrev'];
							if($crewType==$crewTypePrev)
							{
								if($crewType==1)
								{
									if($id==0)
									{
										$name_array=explode(' ', $existingCrew['crewName']);

										$persion = new Individual;
										if(count($name_array)>1)
										{
											if(count($name_array)==2)
											{
												$persion->firstName=$name_array[0];
												$persion->lastName=$name_array[1];
											}
											if(count($name_array)==3)
											{
												$persion->firstName=$name_array[0];
												$persion->middleName=$name_array[1];
												$persion->lastName=$name_array[2];
											}
											if(count($name_array)>3)
											{
												$persion->firstName=$name_array[0];
												$persion->middleName=$name_array[1];
												$persion->lastName=$name_array[2];
												$persion->suffix=$name_array[3];
											}
										}
										else
										{
											$persion->firstName=$name_array[0];
										}
										
										$persion->countryID=0;
										$persion->individualType=3;
										$persion->save();
										$id=$persion->id;
									}
								}
								else
								{
									if($id==0)
									{
										$companyName = $existingCrew['crewName'];
										$company = new Company;
										$company->companyName = $companyName;
										$address=new Address;
										$address->addr1='Not Available';
										$address->countryID=0;
										$address->save();
										$company->save();
										$companyAddress = new Companyaddress;
										$companyAddress->companyID = $company->id;
										$companyAddress->addressID = $address->id;
										$companyAddress->save();
										$id=$company->id;
									}
								}
								
								$roleID=$existingCrew['roleID'];
								if($roleID==0)
								{
									$criteria = new CDbCriteria();
									$criteria->addSearchCondition('rolename', $existingCrew['roleName']);
									$role = Role::model()->find($criteria);
									if(isset($role))
									{
										$roleID = $role->id;
									}
									else
									{
										$role = new Role;
										$role->roleName =$existingCrew['roleName'];
										$role->departmentID = 0;
										$role->save();
										$roleID = $role->id;
									}
								}
								
								if($crewType==1)
								{
									$Productioncrew = Productioncrew::model()->findByPk($existingCrew['id']);
									$Productioncrew->profileID=$id;
									$Productioncrew->profileType=1;
									$Productioncrew->roleID=$roleID;
									$Productioncrew->startDate=!empty($existingCrew['startDate'])?$existingCrew['startDate']:NULL;
									$Productioncrew->endDate=!empty($existingCrew['endDate'])?$existingCrew['endDate']:NULL;
									$Productioncrew->save();
								}
								else
								{
									$Productioncompanycrew = Productioncompanycrew::model()->findByPk($existingCrew['id']);
									$Productioncompanycrew->companyID=$id;
									$Productioncompanycrew->roleID=$roleID;
									$Productioncompanycrew->startDate=!empty($existingCrew['startDate'])?$existingCrew['startDate']:NULL;
									$Productioncompanycrew->endDate=!empty($existingCrew['endDate'])?$existingCrew['endDate']:NULL;
									$Productioncompanycrew->save();
								}
							}
							else
							{
								if($crewTypePrev==1)
								{
									$Productioncrew = Productioncrew::model()->findByPk($existingCrew['id']);
									$Productioncrew->delete();
								}
								else
								{
									$Productioncompanycrew = Productioncompanycrew::model()->findByPk($existingCrew['id']);
									$Productioncompanycrew->delete();
								}
								if($crewType==1)
								{
									if($id==0)
									{
										$name_array=explode(' ', $existingCrew['crewName']);
										$persion = new Individual;
										if(count($name_array)>1)
										{
											if(count($name_array)==2)
											{
												$persion->firstName=$name_array[0];
												$persion->lastName=$name_array[1];
											}
											if(count($name_array)==3)
											{
												$persion->firstName=$name_array[0];
												$persion->middleName=$name_array[1];
												$persion->lastName=$name_array[2];
											}
											if(count($name_array)>3)
											{
												$persion->firstName=$name_array[0];
												$persion->middleName=$name_array[1];
												$persion->lastName=$name_array[2];
												$persion->suffix=$name_array[3];
											}
										}
										else
										{
											$persion->firstName=$name_array[0];
										}
										
										$persion->countryID=0;
										$persion->individualType=3;
										$persion->save();
										$id=$persion->id;
									}
								}
								else
								{
									if($id==0)
									{
										$companyName = $existingCrew['crewName'];
										$company = new Company;
										$company->companyName = $companyName;
										$address=new Address;
										$address->addr1='Not Available';
										$address->countryID=0;
										$address->save();
										$company->save();
										$companyAddress = new Companyaddress;
										$companyAddress->companyID = $company->id;
										$companyAddress->addressID = $address->id;
										$companyAddress->save();
										$id=$company->id;
									}
								}
								
								$roleID=$existingCrew['roleID'];
								if($roleID==0)
								{
									$criteria = new CDbCriteria();
									$criteria->addSearchCondition('rolename', $existingCrew['roleName']);
									$role = Role::model()->find($criteria);
									if(isset($role))
									{
										$roleID = $role->id;
									}
									else
									{
										$role = new Role;
										$role->roleName =$existingCrew['roleName'];
										$role->departmentID = 0;
										$role->save();
										$roleID = $role->id;
									}
								}
								
								if($crewType==1)
								{
									$Productioncrew = new Productioncrew;
									$Productioncrew->profileID=$id;
									$Productioncrew->profileType=1;
									$Productioncrew->productionID=$model->id;
									$Productioncrew->roleID=$roleID;
									$Productioncrew->startDate=!empty($existingCrew['startDate'])?$existingCrew['startDate']:NULL;
									$Productioncrew->endDate=!empty($existingCrew['endDate'])?$existingCrew['endDate']:NULL;
									$Productioncrew->save();
								}
								else
								{
									$Productioncompanycrew = new Productioncompanycrew;
									$Productioncompanycrew->companyID=$id;
									$Productioncompanycrew->productionID=$model->id;
									$Productioncompanycrew->roleID=$roleID;
									$Productioncompanycrew->startDate=!empty($existingCrew['startDate'])?$existingCrew['startDate']:NULL;
									$Productioncompanycrew->endDate=!empty($existingCrew['endDate'])?$existingCrew['endDate']:NULL;
									$Productioncompanycrew->save();
								}
							}
						}
					}
				}

				$exist = Profileimage::model()->
				find("profileID = :profileID and profileType = 2 and imageType=1",array(':profileID'=>$model->id) );
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
						$fileName = $this->getUniqueFileName($file->getName(),'pr');
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
						else
						{
							Yii::app()->user->setFlash('error', "An unknown error occurred when processing the image. Please try uploading a different image.");
						}
						$image = new Image;
						$image->imageURL=$fileName;
						$image->save();

						$prodile_image = new Profileimage;
						$prodile_image->profileID = $model->id;
						$prodile_image->profileType=2; 
						$prodile_image->imageID=$image->id; 
						$prodile_image->imageType=1; 
						$prodile_image->save();
						//print_r($file);
					}
				}
				else
				{
					$imageModel=Image::model()->findByPk($exist->imageID);
					$file=CUploadedFile::getInstanceByName('image');
					if(!$file)
					{
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
						$fileName = $this->getUniqueFileName($file->getName(),'pe');
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
						else
						{
							Yii::app()->user->setFlash('error', "An unknown error occurred when processing the image. Please try uploading a different image.");
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
						$profile_image->profileType=2; 
						$profile_image->imageID=$image->id; 
						$profile_image->imageType=2; 
						$profile_image->save();
					}
				}
				
				//Save contact information
				if($_POST['contactInfoID_facebook']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_facebook']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_facebook'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=1; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_facebook'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_googleplus']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_googleplus']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_googleplus'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=2; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_googleplus'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_twitter']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_twitter']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_twitter'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=3; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_twitter'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_instagram']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_instagram']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_instagram'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=4; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_instagram'];
				$Productioncontactinfo->save();
				
				if($_POST['contactInfoID_website']!=0)
					$Productioncontactinfo = Productioncontactinfo::model()->findByPk($_POST['contactInfoID_website']);
				else
				{
					$Productioncontactinfo = new Productioncontactinfo;
					$Productioncontactinfo->id = $_POST['contactInfoID_website'];
				}
				$Productioncontactinfo->productionID=$model->id; 
				$Productioncontactinfo->contactTypeID=5; 
				$Productioncontactinfo->contactInfo=$_POST['contactInfo_website'];
				$Productioncontactinfo->save();
				
				Yii::app()->user->setFlash('success', "Production successfully updated.");
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	
	public function actionRating()
	{
		$Productionrating = Productionrating::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>Yii::app()->request->getPost('productionID'),':userID'=>Yii::app()->user->id));
		if(!$Productionrating)
		{
			$Productionrating = new Productionrating;
			$Productionrating->productionID=Yii::app()->request->getPost('productionID');
			$Productionrating->userID=Yii::app()->user->id;
			$Productionrating->rating=Yii::app()->request->getPost('value');
		}
		else
		{
			$Productionrating->rating=Yii::app()->request->getPost('value');
		}
		if(!empty($Productionrating->userID)&&!empty($Productionrating->productionID)&&!empty($Productionrating->rating))
		{
			if($Productionrating->validate())
			{
				echo $Productionrating->rating;
				$Productionrating->save();
			}
			//echo "Saved PID:".Yii::app()->request->getPost('productionID')." UID:".Yii::app()->user->id." Rating: ".Yii::app()->request->getPost('value');
		}
		else
		{
			throw new CHttpException(666);
		}
	}
		
	public function actionWatchlistAdd()
	{
		$Productionwatchlist = Productionwatchlist::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>Yii::app()->request->getPost('productionID'),':userID'=>Yii::app()->user->id));
		if(empty($Productionwatchlist))
		{
			$Productionwatchlist = new Productionwatchlist;
			$Productionwatchlist->productionID=Yii::app()->request->getPost('productionID');
			$Productionwatchlist->userID=Yii::app()->user->id;
			if($Productionwatchlist->validate())
			{
				$Productionwatchlist->save();
			}
		}
	}
	
	public function actionWatchlistRemove()
	{
		$Productionwatchlist = Productionwatchlist::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>Yii::app()->request->getPost('productionID'),':userID'=>Yii::app()->user->id));
		if(!empty($Productionwatchlist))
			$Productionwatchlist->delete();
	}
	
	public function actionOwnershipClaim()
	{
		$Productionownership = Productionownership::model()->find("productionID=:productionID",array(':productionID'=>Yii::app()->request->getPost('productionID')));
		if(empty($Productionownership))
		{
			$Productionownership = new Productionownership;
			$Productionownership->productionID=Yii::app()->request->getPost('productionID');
			$Productionownership->userID=Yii::app()->user->id;
			if($Productionownership->validate())
				$Productionownership->save();
			Yii::app()->user->setFlash('success', "Production successfully claimed.");
		}
	}
	
	public function actionOwnershipRelinquish()
	{
		$Productionownership = Productionownership::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>Yii::app()->request->getPost('productionID'),':userID'=>Yii::app()->user->id));
		if(!empty($Productionownership))
		{
			$Productionownership->delete();
			Yii::app()->user->setFlash('success', "Production ownership successfully relinquished.");
		}
		
		$Production = Production::model()->find("id=:productionID",array(':productionID'=>Yii::app()->request->getPost('productionID')));
		if(!count($Production->productionownerships))
		{
			$Production->privateRatings=0;
			$Production->save();
		}
	}
	
	public function actionRemovelink($id,$venueid)
	{
		$link=Link::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$venueid));
	}
	
	/**
	 * Deletes a profile image
	 */
	public function actionDeleteProfileImage()
	{
		$model=$this->loadModel(Yii::app()->request->getPost('productionID'));
		if(!Yii::app()->user->checkAccess('Production.UpdateAccess',array('ownerships'=>$model->productionownerships)))
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
		//$this->loadModel($id)->delete();

		$model=Production::model()->with('productionvenues')->findByPk($id);
		//$model->showcreators->showID=0;
		foreach($model->productionvenues as $vanues)
		{
			$vanues->delete();
		}

   		$model->delete();	

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Production',array(
		'pagination'=>array('pageSize'=>20),
		'criteria'=>array('order'=>'a.showName',
		'join' => 'INNER JOIN tbl_show a ON a.id=t.showID'),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

		/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Production the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Production::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Production $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='production-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionVenueLists()
    {
        $term = Yii::app()->request->getQuery('term');
        $venues = Venue::model()->findAll('venueName like "%'.$term.'%"');
        $item=array();
        foreach($venues as $venue)
        {
        	$item[]=array(
        		'id'=>$venue->id,
        		'value'=>$venue->venueName,
				'desc'=>$venue->address->city.', '.$venue->address->state.', '.$venue->address->country->countryCode,
        		);
        }
        echo json_encode($item);
    }
	
	public function actionCrewRoleList()
    {
        $term = Yii::app()->request->getQuery('term');
        $roles = Role::model()->with(array(
			'department'=>array(
				'select'=>false,
				'joinType'=>'INNER JOIN',
				'condition'=>'department.departmentName !="Authors"'
			)
			))->findAll('roleName like "%'.$term.'%"');
        $item=array();
        foreach($roles as $role)
        {
        	$item[]=array(
        		'id'=>$role->id,
        		'value'=>$role->roleName
        		);
        }
        echo json_encode($item);
    } 

	public function  actionRemovevenue($id,$productionID)
	{
			$Productionvenue=Productionvenue::model()->findByPk($id)->delete();
			$this->redirect(array('update','id'=>$productionID));
	}

	public function  actionRemovecast($id,$productionID)
	{
			$Productioncast=Productioncast::model()->findByPk($id)->delete();
			$this->redirect(array('update','id'=>$productionID));
	}

	public function  actionRemovecrew($id,$type,$productionID)
	{
		if($type==1)
		{
			$Productioncrew=Productioncrew::model()->findByPk($id)->delete();
			$this->redirect(array('update','id'=>$productionID));
		}
		else
		{
			$Productioncompanycrew=Productioncompanycrew::model()->findByPk($id)->delete();
			$this->redirect(array('update','id'=>$productionID));
		}
	}
	
	
	private function date_compare($a, $b)
	{
		$t1 = strtotime($a['start']);
		$t2 = strtotime($b['start']);
		return $t1 - $t2;
	}
	
	
	private function processEvent($Productionevent,$viewStart=null)
	{
		$Venue=$Productionevent->productionvenue->venue;
		$venueName=$Venue->venueName;
		$image_url='';
		$id=0;
		$item = array();
		$id=$Productionevent->id;
		$eventUrl = $Venue->createUrl();
		$eventEditUrl = yii::app()->createUrl('productionevent/update').'/'.$Productionevent->id;
		$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND imageType=1 AND profileID='.$Venue->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}
		else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
		}			
		if($Productionevent->recurs==0)
		{
			$item[] = $this->generate_event($id,$Productionevent,$venueName,$eventUrl,$eventEditUrl,$image_url);
			return $item;
		}
		else
		{
			return $this->generate_repeating_event($id,$Productionevent,$venueName,$eventUrl,$eventEditUrl,$image_url,$viewStart);
		}
	}
	
	private function generate_event($id,$Productionevent,$venueName,$eventUrl,$eventEditUrl,$imageUrl)
	{
		$start = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		//$end = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->endDate);
		$start = $start->format('l, F d, Y g:i A');
		//$end = $end->format('Y-m-d\TH:i:00.000\Z');
		$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionevent->productionVenueID.' and linkType=1');	
		$linkItems=array();
		foreach($links as $link)
		{
			$linkItems[]=array('label'=> $link->label,'href'=>$link->href);
		}
		$item=array(
			'id'=>$id,
			'start'=>$start,
			//'end'=>$end,
			'venueName'=>$venueName,
			'eventUrl'=>$eventUrl,
			'eventEditUrl'=>$eventEditUrl,
			'imageUrl'=>$imageUrl,
			'ticketLinks'=>$linkItems
		);
		return $item;
	}
	
	
	private function generate_repeating_event($id,$Productionevent,$venueName,$eventUrl,$eventEditUrl,$imageUrl,$viewStart) {
		$viewStart = DateTime::createFromFormat('m-d-Y H:i', $viewStart);
		$startDate = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		//$endDate = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->endDate);
		$recursEndDate = DateTime::createFromFormat('!m-d-Y', $Productionevent->recursEndDate);
		$recursEndDate->setTime(23, 59, 59);
		while ($startDate <$viewStart)
			$startDate = $this->get_next_date($startDate, $Productionevent->recurs);
		$item =array();
		$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionevent->productionVenueID.' and linkType=1');	
		$linkItems=array();
		foreach($links as $link)
		{
			$linkItems[]=array('label'=> $link->label,'href'=>$link->href);
		}
		while ($startDate >=$viewStart && $startDate <= $recursEndDate)
		{
			$item[]=array(
				'id'=>$id,
				'start'=>$startDate->format('l, F d, Y g:i A'),
				'venueName'=>$venueName,
				'eventUrl'=>$eventUrl,
				'eventEditUrl'=>$eventEditUrl,
				'imageUrl'=>$imageUrl,
				'ticketLinks'=>$linkItems
			);
			$startDate = $this->get_next_date($startDate, $Productionevent->recurs);
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
	
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAnalytics($id)
	{
		$model=$this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Production.UpdateAccess',array('ownerships'=>$model->productionownerships)))
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
