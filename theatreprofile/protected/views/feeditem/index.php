<?php
/* @var $this FeeditemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Feeditems',
);

$this->menu=array(
	array('label'=>'Create Feeditem', 'url'=>array('create')),
	array('label'=>'Manage Feeditem', 'url'=>array('admin')),
);
?>

<h1>Feeditems</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
