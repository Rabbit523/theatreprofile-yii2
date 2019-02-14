<?php

class ShowController extends Controller
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

	public function actionCreatorLists()
    {
    	
		$term = Yii::app()->request->getQuery('term');
		$optimizedterm=str_replace(' ', '%', $term);
        $creators = Individual::model()->with('country')->findAll('concat(firstName,middleName,lastName,suffix) like "%'.$optimizedterm.'%"');
        $item=array();
        foreach($creators as $creator)
        {
        	$item[]=array(
        		'id'=>$creator->id,
        		'value'=>$creator->firstName.' '.$creator->middleName.' '.$creator->lastName.' '.$creator->suffix,
				'desc'=>$creator->country->countryCode,
        	);
        }
        
        echo json_encode($item);
    }
	
	 public function actionShowLists()
    {
    	
        $term = Yii::app()->request->getQuery('term');
       //echo $term ;
        $shows = Show::model()->findAll('showName like "%'.$term.'%"');
        //print_r($creators);
       // echo json_encode($creators);
        $item=array();
        foreach($shows as $show)
        {
			$showcreators = Showcreator::model()->with('individual')->findAll('showID='.$show->id);
			$name='';
			foreach($showcreators as $creator)
			{
				$name = $name.$creator->individual->firstName.' '.$creator->individual->lastName.', ';
			}
			$name = rtrim($name,', ');
			$desc = empty($name)?'Creators not available':$name;

        	$item[]=array(
        		'id'=>$show->id,
        		'value'=>$show->showName,
				'desc'=>$desc,
        	);
        }   
        echo json_encode($item);
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
		$model=new Show;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Show']))
		{
			$model->attributes=$_POST['Show'];
			if($model->save())
			{
				
				if(isset($_POST['new_creator'])){
					foreach($_POST['new_creator'] as $creator)
					{
						if($creator['individualID']==0)
						{
							$name_array=explode(' ', $creator['name']);
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
							$newshowcreator = new Showcreator;
							$newshowcreator->showID=$model->id;
							$newshowcreator->individualID=$persion->id;
							$newshowcreator->roleID=$creator['role'];
							$newshowcreator->save();
						}
						else
						{
							$newshowcreator = new Showcreator;
							$newshowcreator->showID=$model->id;
							$newshowcreator->individualID=$creator['individualID'];
							$newshowcreator->roleID=$creator['role'];
							$newshowcreator->save();
						}
					}
				}

				
				$file=CUploadedFile::getInstanceByName('image');
				if(!$file){
					echo 'No file selected.';
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
					$fileName = $this->getUniqueFileName($file->getName(),'s');
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
					$prodile_image->profileType=1; 
					$prodile_image->imageID=$image->id; 
					$prodile_image->imageType=1; 
					$prodile_image->save();
				}
				Yii::app()->user->setFlash('success', "Show successfully created.");
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
		if(!Yii::app()->user->checkAccess('Show.UpdateAccess',array('ownerships'=>$model->showownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($model->createUrl());
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Show']))
		{
			$model->attributes=$_POST['Show'];
			if($model->save())
			{
				if(isset($_POST['new_creator']))
				{
					foreach($_POST['new_creator'] as $creator)
					{
						if($creator['individualID']==0)
						{
							$name_array=explode(' ', $creator['name']);
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
							$newshowcreator = new Showcreator;
							$newshowcreator->showID=$model->id;
							$newshowcreator->individualID=$persion->id;
							$newshowcreator->roleID=$creator['role'];
							$newshowcreator->save();
						}
						else
						{
							$newshowcreator = new Showcreator;
							$newshowcreator->showID=$model->id;
							$newshowcreator->individualID=$creator['individualID'];
							$newshowcreator->roleID=$creator['role'];
							$newshowcreator->save();
						}	
					}
				}


				$exist = Profileimage::model()->
				find("profileID = :profileID and profileType = 1 and imageType=1",array(':profileID'=>$id) );
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
						$fileName = $this->getUniqueFileName($file->getName(),'s');
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
						$prodile_image->profileType=1; 
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
						$fileName = $this->getUniqueFileName($file->getName(),'s');
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
						$profile_image->profileType=1; 
						$profile_image->imageID=$image->id; 
						$profile_image->imageType=2; 
						$profile_image->save();
					}
				}
				Yii::app()->user->setFlash('success', "Show successfully updated.");
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
		$model=Show::model()->with('showcreators')->findByPk($id);
		//$model->showcreators->showID=0;
		foreach($model->showcreators as $creators)
		{
			$creators->delete();
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
		$dataProvider=new CActiveDataProvider('Show');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Show the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Show::model()->with('category')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function  actionRemovecreator($id,$showid)
	{
		$creator=Showcreator::model()->findByPk($id)->delete();
		$this->redirect(array('update','id'=>$showid));
	}
	
	
	public function actionWatchlistAdd()
	{
		$Showwatchlist = Showwatchlist::model()->find("showID=:showID and userID=:userID",array(':showID'=>Yii::app()->request->getPost('showID'),':userID'=>Yii::app()->user->id));
		if(empty($Showwatchlist))
		{
			$Showwatchlist = new Showwatchlist;
			$Showwatchlist->showID=Yii::app()->request->getPost('showID');
			$Showwatchlist->userID=Yii::app()->user->id;
			if($Showwatchlist->validate())
				$Showwatchlist->save();
		}
	}
	
	public function actionWatchlistRemove()
	{
		$Showwatchlist = Showwatchlist::model()->find("showID=:showID and userID=:userID",array(':showID'=>Yii::app()->request->getPost('showID'),':userID'=>Yii::app()->user->id));
		if(!empty($Showwatchlist))
			$Showwatchlist->delete();
	}
	
	public function actionOwnershipClaim()
	{
		$Showownership = Showownership::model()->find("showID=:showID",array(':showID'=>Yii::app()->request->getPost('showID')));
		if(empty($Showownership))
		{
			$Showownership = new Showownership;
			$Showownership->showID=Yii::app()->request->getPost('showID');
			$Showownership->userID=Yii::app()->user->id;
			if($Showownership->validate())
				$Showownership->save();
		}
	}
	
	public function actionOwnershipRelinquish()
	{
		$Showownership = Showownership::model()->find("showID=:showID and userID=:userID",array(':showID'=>Yii::app()->request->getPost('showID'),':userID'=>Yii::app()->user->id));
		if(!empty($Showownership))
			$Showownership->delete();
	}
	
	/**
	 * Deletes a profile image
	 */
	public function actionDeleteProfileImage()
	{
		$model=$this->loadModel(Yii::app()->request->getPost('showID'));
		if(!Yii::app()->user->checkAccess('Show.UpdateAccess',array('ownerships'=>$model->showownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
		}
		
		$Profileimage = Profileimage::model()->findByPk(Yii::app()->request->getPost('id'))->delete();
		Yii::app()->user->setFlash('success', "Profile successfully updated.");
		//echo $this->renderPartial('//layouts/_flashes');
	}

	/**
	 * Performs the AJAX validation.
	 * @param Show $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='show-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAnalytics($id)
	{
		$model=$this->loadModel($id);		
		if(!Yii::app()->user->checkAccess('Show.UpdateAccess',array('ownerships'=>$model->showownerships)))
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
