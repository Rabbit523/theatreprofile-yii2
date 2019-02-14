<?php
$this->pageTitle=Yii::app()->name. " - Production - ".$model->show->showName;

$venue_count = count($model->productionvenues);
if($venue_count==1)
{
	$venue = array_values($model->productionvenues)[0];
	$this->breadcrumbs=array(
	'Shows'=>array('/show'),
	(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),
	!empty($model->productionName)?$model->productionName:$model->show->showName.' at '.$venue->venue->venueName=>$model->createUrl(),
	'Update',
	);
}
else if($venue_count>1)
{
	$this->breadcrumbs=array(
	'Shows'=>array('shows'),
	(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),
	!empty($model->productionName)?$model->productionName:$model->show->showName.' - Multiple venues'=>$model->createUrl(),
	'Update',
	);
}
else
{
	$this->breadcrumbs=array(
	'Shows'=>array('shows'),
	(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),
	!empty($model->productionName)?$model->productionName:$model->show->showName.' - Venue not available'=>$model->createUrl(),
	'Update',
	);
}
?>

<h1>Update Production <?php echo $model->show->showName; ?><small><?php echo ' '.$model->productionName; ?> </small></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>