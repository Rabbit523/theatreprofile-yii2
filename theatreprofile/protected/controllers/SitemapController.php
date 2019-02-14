<?php

class SitemapController extends Controller
{
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
		return 'index';
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		header('Content-Type: application/xml;charset=utf-8');
		//$showModels = Show::model()->findAll(array('order' => 'id',));
		//$productionModels = Production::model()->findAll(array('order' => 'id','limit'=>3000,'offset'=>6000));
		//$peopleModels = Individual::model()->findAll(array('order' => 'id','limit'=>5000,'offset'=>0));
		//$venueModels = Venue::model()->findAll(array('order' => 'id'));
		//$companyModels = Company::model()->findAll(array('order' => 'id'));
		$feeditemModels = Feeditem::model()->findAll(array('order' => 'id'));
		$list = array();
		/*
		$list = array(
                    array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/'),
                        'frequency'=>'weekly',
                        'priority'=>'1',
                        ),
                    array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/site/contact'),
                        'frequency'=>'yearly',
                        'priority'=>'0.8',
                        ),
                    array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/site/page', array('view'=>'about')),
                        'frequency'=>'yearly',
                        'priority'=>'0.8',
                        ),
                    array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/site/page', array('view'=>'privacy')),
                        'frequency'=>'yearly',
                        'priority'=>'0.3',
                        ),
					array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/site/page', array('view'=>'terms')),
                        'frequency'=>'yearly',
                        'priority'=>'0.3',
                        ),
					array(
                        'loc'=>Yii::app()->createAbsoluteUrl('/site/page', array('view'=>'grow')),
                        'frequency'=>'yearly',
                        'priority'=>'0.3',
                        ),
					
                );
		*/
		$i=count($list);
		/*
		foreach($showModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
		foreach($productionModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
		foreach($peopleModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
		foreach($venueModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
		foreach($companyModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
		*/
		foreach($feeditemModels as $model)
		{
			$list[$i]['loc']=$model->createAbsoluteUrl();
			$list[$i]['frequency']='weekly';
			$list[$i]['priority']='0.5';
			$i++;
		}
        $this->renderPartial('index',array('list'=>$list));
	}
}