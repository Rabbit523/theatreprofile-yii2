<?php
/* @var $this ShowManagementController */
/* @var $data Show */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('showName')); ?>:</b>
	<?php echo CHtml::encode($data->showName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('categoryID')); ?>:</b>
	<?php echo CHtml::encode($data->categoryID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('showDesc')); ?>:</b>
	<?php echo CHtml::encode($data->showDesc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('showDate')); ?>:</b>
	<?php echo CHtml::encode($data->showDate); ?>
	<br />


</div>