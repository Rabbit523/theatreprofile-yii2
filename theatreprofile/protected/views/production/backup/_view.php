<?php
/* @var $this ProductionManagementController */
/* @var $data Production */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('showID')); ?>:</b>
	<?php echo CHtml::encode($data->showID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firstPreviewDate')); ?>:</b>
	<?php echo CHtml::encode($data->firstPreviewDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startDate')); ?>:</b>
	<?php echo CHtml::encode($data->startDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endDate')); ?>:</b>
	<?php echo CHtml::encode($data->endDate); ?>
	<br />


</div>