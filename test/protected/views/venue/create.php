<?php
/* @var $this VenueController */
/* @var $model Venue */

$this->breadcrumbs=array(
	'Venues'=>array('index'),
	'Create',
);
?>

<h1>Create Venue</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>