<?php
/* @var $this LinkController */
/* @var $data Link */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profileID')); ?>:</b>
	<?php echo CHtml::encode($data->profileID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profileType')); ?>:</b>
	<?php echo CHtml::encode($data->profileType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('href')); ?>:</b>
	<?php echo CHtml::encode($data->href); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linkType')); ?>:</b>
	<?php echo CHtml::encode($data->linkType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
	<?php echo CHtml::encode($data->label); ?>
	<br />


</div>