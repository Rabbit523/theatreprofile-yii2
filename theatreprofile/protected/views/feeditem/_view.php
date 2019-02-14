<?php
/* @var $this FeeditemController */
/* @var $data Feeditem */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('feedID')); ?>:</b>
	<?php echo CHtml::encode($data->feedID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descr')); ?>:</b>
	<?php echo CHtml::encode($data->descr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('href')); ?>:</b>
	<?php echo CHtml::encode($data->href); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publishDate')); ?>:</b>
	<?php echo CHtml::encode($data->publishDate); ?>
	<br />


</div>