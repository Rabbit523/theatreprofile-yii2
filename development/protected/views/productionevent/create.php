<?php
/* @var $this ProductioneventController */
/* @var $model Productionevent */

$pageTitle='Events - '.$venue->venueName." - Venue - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($venue->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('Venues'=>array('/venue'),$venue->venueName=>$venue->createUrl(),'Schedule'=>array('/venue/schedule'.'/'.$venue->id),'Events');
?>

<h1>Create Event</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'venue'=>$venue,'data'=>$data,'events'=>$events)); ?>