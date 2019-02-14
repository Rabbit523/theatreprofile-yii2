<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */

$this->breadcrumbs=array(
	'Feeditems'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Feeditem', 'url'=>array('index')),
	array('label'=>'Create Feeditem', 'url'=>array('create')),
	array('label'=>'Update Feeditem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Feeditem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Feeditem', 'url'=>array('admin')),
);
?>

<h1>View Feeditem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'feedID',
		'title',
		'descr',
		'href',
		'publishDate',
	),
)); ?>
