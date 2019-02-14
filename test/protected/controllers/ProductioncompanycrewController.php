<?php

class ProductioncompanycrewController extends Controller
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
		$Productioncompanycrewrating = Productioncompanycrewrating::model()->find("productionCompanyCrewID=:productionCompanyCrewID and userID=:userID",array(':productionCompanyCrewID'=>Yii::app()->request->getPost('productionCompanyCrewID'),':userID'=>Yii::app()->user->id));
		if(!$Productioncompanycrewrating)
		{
			$Productioncompanycrewrating = new Productioncompanycrewrating;
			$Productioncompanycrewrating->productionCompanyCrewID=Yii::app()->request->getPost('productionCompanyCrewID');
			$Productioncompanycrewrating->userID=Yii::app()->user->id;
			$Productioncompanycrewrating->rating=Yii::app()->request->getPost('value');
		}
		else
		{
			$Productioncompanycrewrating->rating=Yii::app()->request->getPost('value');
		}
		if(!empty($Productioncompanycrewrating->userID)&&!empty($Productioncompanycrewrating->productionCompanyCrewID)&&!empty($Productioncompanycrewrating->rating))
		{
			if($Productioncompanycrewrating->validate())
			{
				echo $Productioncompanycrewrating->rating;
				$Productioncompanycrewrating->save();
			}
			//echo "Saved PID:".Yii::app()->request->getPost('productionID')." UID:".Yii::app()->user->id." Rating: ".Yii::app()->request->getPost('value');
		}
		else
		{
			throw new CHttpException(500);
		}
	}
}