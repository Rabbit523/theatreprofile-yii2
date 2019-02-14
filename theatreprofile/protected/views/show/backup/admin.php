<?php
/* @var $this ShowManagementController */
/* @var $model Show */

$this->breadcrumbs=array(
	'Shows'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Show', 'url'=>array('index')),
	array('label'=>'Create Show', 'url'=>array('create')),
);


?>

<h1>Manage Shows</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'show-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'showName',
		'categoryID',
		'showDesc',
		'showDate',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
