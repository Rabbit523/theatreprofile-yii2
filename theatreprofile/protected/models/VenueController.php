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
		);
	}
	
	public function allowedActions()
	{
		return 'index,view';
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','ProductionLists'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','Removeproduction','Removelink'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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
		$model=new Venue;
		$model->address = new Address;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Venue'])&&isset($_POST['Address']))
		{
			$model->attributes=$_POST['Venue'];
			$model->address->attributes=$_POST['Address'];
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
						$rnd = rand(0,9999);  // generate random number between 0-9999
						$new_file= rand(0,9999).'_'.$file->getName();
						$file->saveAs(Yii::app()->basePath.'/../images/uploads/'.$new_file);
						$image = new Image;
						$image->imageURL=$new_file;
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
			}
			Yii::app()->user->setFlash('success', "Venue successfully created.");
			$this->redirect(array('update','id'=>$model->id));
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
							$Link->label='Buy Tickets';
							$Link->save();
						}
					}
					if(isset($ticketingLinks['existing']))
					{
						foreach($ticketingLinks['existing'] as $existingTicketingLink)
						{
							$Link = Link::model()->findByPk($existingTicketingLink['id']);
							$Link->href=$existingTicketingLink['href'];
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
				find("profileID = :profileID and profileType = 4",array(':profileID'=>$id) );
				if (!$exist) {				
					$file=CUploadedFile::getInstanceByName('image');
					if(!$file){
						//echo 'no file selected';
					}
					else
					{
						$rnd = rand(0,9999);  // generate random number between 0-9999
						$new_file= rand(0,9999).'_'.$file->getName();
						$file->saveAs(Yii::app()->basePath.'/../images/uploads/'.$new_file);
						$image = new Image;
						$image->imageURL=$new_file;
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
						$rnd = rand(0,9999);  // generate random number between 0-9999
						$new_file= rand(0,9999).'_'.$file->getName();
						$file->saveAs(Yii::app()->basePath.'/../images/uploads/'.$new_file);
						
						$imageModel->imageURL=$new_file;
						$imageModel->save();
					}
				}
			}
			Yii::app()->user->setFlash('success', "Venue successfully updated.");			
			$this->redirect(array('update','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('Venue');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Venue('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Venue']))
			$model->attributes=$_GET['Venue'];

		$this->render('admin',array(
			'model'=>$model,
		));
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
}
