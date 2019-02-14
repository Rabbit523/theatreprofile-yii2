<?php
/* @var $this IndividualManagementController */
/* @var $model Individual */

$this->breadcrumbs=array(
	'Individuals'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Individual', 'url'=>array('index')),
	array('label'=>'Create Individual', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#individual-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Individuals</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'individual-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'firstName',
		'middleName',
		'lastName',
		'suffix',
		'gender',
		/*
		'individualType',
		'countryID',
		'zip',
		'dateOfBirth',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
