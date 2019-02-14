<?php
Yii::import('zii.widgets.CPortlet');
class LoginWidget extends CPortlet
{
	public function init()
	{
		$this->title=Yii::t('user', 'Login to get started');
		parent::init();
	}
	protected function renderContent()
	{
		$this->render('loginWidget', array('model' => new UserLogin()));
	}
}
?>