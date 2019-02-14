<?php
/* @var $this ShowManagementController */
/* @var $model Show */

$this->breadcrumbs=array(
	'Shows'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Show', 'url'=>array('index')),
	array('label'=>'Create Show', 'url'=>array('create')),
	array('label'=>'Update Show', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Show', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Show', 'url'=>array('admin')),
);
?>

<h1>View Show #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'showName',
		'categoryID',
		'showDesc',
		'showDate',
	),
)); ?>
