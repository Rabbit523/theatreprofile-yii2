<?php
$this->breadcrumbs=array(
	'Venues'=>array('index'),
	$model->venueName=>$model->createUrl(),
	'Update',
);
?>

<h1>Update Venue <?php echo $model->venueName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>