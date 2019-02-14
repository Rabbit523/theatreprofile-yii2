<?php
/* @var $this IndividualManagementController */
/* @var $model Individual */

$this->breadcrumbs=array(
	'Individuals'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Individual', 'url'=>array('index')),
	array('label'=>'Create Individual', 'url'=>array('create')),
	array('label'=>'Update Individual', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Individual', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Individual', 'url'=>array('admin')),
);
?>

<h1>View Individual #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'firstName',
		'middleName',
		'lastName',
		'suffix',
		'gender',
		'individualType',
		'countryID',
		'zip',
		'dateOfBirth',
	),
)); ?>
