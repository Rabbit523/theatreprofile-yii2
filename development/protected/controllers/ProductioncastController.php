<?php

class ProductioncastController extends Controller
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
		$Productioncastrating = Productioncastrating::model()->find("productionCastID=:productionCastID and userID=:userID",array(':productionCastID'=>Yii::app()->request->getPost('productionCastID'),':userID'=>Yii::app()->user->id));
		if(!$Productioncastrating)
		{
			$Productioncastrating = new Productioncastrating;
			$Productioncastrating->productionCastID=Yii::app()->request->getPost('productionCastID');
			$Productioncastrating->userID=Yii::app()->user->id;
			$Productioncastrating->rating=Yii::app()->request->getPost('value');
		}
		else
		{
			$Productioncastrating->rating=Yii::app()->request->getPost('value');
		}
		if(!empty($Productioncastrating->userID)&&!empty($Productioncastrating->productionCastID)&&!empty($Productioncastrating->rating))
		{
			if($Productioncastrating->validate())
			{
				echo $Productioncastrating->rating;
				$Productioncastrating->save();
			}
			//echo "Saved PID:".Yii::app()->request->getPost('productionID')." UID:".Yii::app()->user->id." Rating: ".Yii::app()->request->getPost('value');
		}
		else
		{
			throw new CHttpException(500);
		}
	}
}