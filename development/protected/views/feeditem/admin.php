<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */

$this->breadcrumbs=array(
	'Feeditems'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Feeditem', 'url'=>array('index')),
	array('label'=>'Create Feeditem', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#feeditem-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<style type="text/css">
.items {word-wrap:break-word;table-layout:fixed;}
</style>

<h1>Manage Feeditems</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
	$dataprovider = $model->search();
	$dataprovider->sort = array(
	  'defaultOrder'=>'id desc'
	);

	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'feeditem-grid',
	'dataProvider'=>$dataprovider,
	'filter'=>$model,
	'columns'=>array(
		'id',
		'feedID',
		'title',
		'descr',
		'href',
		'publishDate',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
