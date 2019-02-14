<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */

$this->breadcrumbs=array(
	'Feeditems'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Feeditem', 'url'=>array('index')),
	array('label'=>'Manage Feeditem', 'url'=>array('admin')),
);
?>

<h1>Create Feeditem</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>