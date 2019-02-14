<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';
	
	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		UserKeys::invalidateKey(Yii::app()->user->id);
		Yii::app()->user->logout();
		//$this->redirect(Yii::app()->controller->module->returnLogoutUrl);
		$this->redirect(Yii::app()->user->returnUrl);
	}

}