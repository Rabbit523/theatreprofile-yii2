<?php
$this->breadcrumbs=array(
	'Shows'=>array('index'),
	(is_numeric($model->showName)?' '.$model->showName:$model->showName)=>$model->createUrl(),
	'Update',
);

?>

<h1><?php echo $model->showName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>