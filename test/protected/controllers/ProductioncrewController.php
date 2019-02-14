<?php

class ProductioncrewController extends Controller
{
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
		return '';
	}
	
	
	public function actionRating()
	{
		$Productioncrewrating = Productioncrewrating::model()->find("productionCrewID=:productionCrewID and userID=:userID",array(':productionCrewID'=>Yii::app()->request->getPost('productionCrewID'),':userID'=>Yii::app()->user->id));
		if(!$Productioncrewrating)
		{
			$Productioncrewrating = new Productioncrewrating;
			$Productioncrewrating->productionCrewID=Yii::app()->request->getPost('productionCrewID');
			$Productioncrewrating->userID=Yii::app()->user->id;
			$Productioncrewrating->rating=Yii::app()->request->getPost('value');
		}
		else
		{
			$Productioncrewrating->rating=Yii::app()->request->getPost('value');
		}
		if(!empty($Productioncrewrating->userID)&&!empty($Productioncrewrating->productionCrewID)&&!empty($Productioncrewrating->rating))
		{
			if($Productioncrewrating->validate())
			{
				echo $Productioncrewrating->rating;
				$Productioncrewrating->save();
			}
			//echo "Saved PID:".Yii::app()->request->getPost('productionID')." UID:".Yii::app()->user->id." Rating: ".Yii::app()->request->getPost('value');
		}
		else
		{
			throw new CHttpException(500);
		}
	}
}