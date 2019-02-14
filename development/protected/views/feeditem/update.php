<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */

$this->breadcrumbs=array(
	'Feeditems'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Feeditem', 'url'=>array('index')),
	array('label'=>'Create Feeditem', 'url'=>array('create')),
	array('label'=>'View Feeditem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Feeditem', 'url'=>array('admin')),
);
?>

<h1>Update Feeditem <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>