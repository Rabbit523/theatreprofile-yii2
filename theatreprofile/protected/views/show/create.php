<?php
/* @var $this ShowManagementController */
/* @var $model Show */

$this->breadcrumbs=array(
	'Shows'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Show', 'url'=>array('index')),
	array('label'=>'Manage Show', 'url'=>array('admin')),
);
?>

<h1>Create Show</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>