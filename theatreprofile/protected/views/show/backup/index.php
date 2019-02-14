<?php
/* @var $this ShowManagementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Shows',
);

$this->menu=array(
	array('label'=>'Create Show', 'url'=>array('create')),
	array('label'=>'Manage Show', 'url'=>array('admin')),
);
?>

<h1>Shows</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
