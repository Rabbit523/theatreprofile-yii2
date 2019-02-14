<?php
/* @var $this ProductionManagementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Productions',
);

$this->menu=array(
	array('label'=>'Create Production', 'url'=>array('create')),
	array('label'=>'Manage Production', 'url'=>array('admin')),
);
?>

<h1>Productions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
