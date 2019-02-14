<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	$model->companyName=>$model->createUrl(),
	'Update',
);
?>

<h1>Update Company <?php echo $model->companyName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>