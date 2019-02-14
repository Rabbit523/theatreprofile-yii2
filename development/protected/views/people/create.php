<?php
/* @var $this IndividualManagementController */
/* @var $model Individual */

$this->breadcrumbs=array(
	'People'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Individual', 'url'=>array('index')),
	array('label'=>'Manage Individual', 'url'=>array('admin')),
);
?>

<h1>Create Individual</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>