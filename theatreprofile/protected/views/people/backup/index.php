<?php
/* @var $this IndividualManagementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Individuals',
);

$this->menu=array(
	array('label'=>'Create Individual', 'url'=>array('create')),
	array('label'=>'Manage Individual', 'url'=>array('admin')),
);
?>

<h1>Individuals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
