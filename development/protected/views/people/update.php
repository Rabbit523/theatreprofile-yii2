<?php
$this->breadcrumbs=array(
	'People'=>array('index'),
	$model->firstName.' '.$model->lastName=>$model->createUrl(),
	'Update',
);
?>

<h1>Update <?php echo $model->firstName.' '.$model->lastName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>