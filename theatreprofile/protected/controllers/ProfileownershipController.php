<?php

class ProfileownershipController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
			'rights',
		);
	}
	
	public function allowedActions()
	{
		return 'index';
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{	
		$criteria1 = new CDbCriteria();
		$criteria1->condition = 'userID=:userID';
		$criteria1->params = array(':userID'=>Yii::app()->user->id);
		$criteria1->order="showName";
		$criteria1->with=array('show');
		$dataProvider1 = new CActiveDataProvider('Showownership',array('criteria'=>$criteria1,'pagination'=>array('pageSize'=>20,)));		
		
		$criteria2 = new CDbCriteria();
		$criteria2->condition = 'userID=:userID';
		$criteria2->params = array(':userID'=>Yii::app()->user->id);
		$criteria2->with=array('production.show');
		$criteria2->order="showName";
		$dataProvider2 = new CActiveDataProvider('Productionownership',array('criteria'=>$criteria2,'pagination'=>array('pageSize'=>20,)));
		
		$criteria3 = new CDbCriteria();
		$criteria3->condition = 'userID=:userID';
		$criteria3->params = array(':userID'=>Yii::app()->user->id);
		$criteria3->order="firstName";
		$criteria3->with=array('individual');
		$dataProvider3 = new CActiveDataProvider('Individualownership',array('criteria'=>$criteria3,'pagination'=>array('pageSize'=>20,)));
		
		$criteria4 = new CDbCriteria();
		$criteria4->condition = 'userID=:userID';
		$criteria4->params = array(':userID'=>Yii::app()->user->id);
		$criteria4->order="venueName";
		$criteria4->with=array('venue');
		$dataProvider4 = new CActiveDataProvider('Venueownership',array('criteria'=>$criteria4,'pagination'=>array('pageSize'=>20,)));
		
		$criteria5 = new CDbCriteria();
		$criteria5->condition = 'userID=:userID';
		$criteria5->params = array(':userID'=>Yii::app()->user->id);
		$criteria5->order="companyName";
		$criteria5->with=array('company');
		$dataProvider5 = new CActiveDataProvider('Companyownership',array('criteria'=>$criteria5,'pagination'=>array('pageSize'=>20,)));
			
		$this->render('index',array('dataProvider1'=>$dataProvider1,'dataProvider2'=>$dataProvider2,'dataProvider3'=>$dataProvider3,'dataProvider4'=>$dataProvider4,'dataProvider5'=>$dataProvider5,));
	}
}