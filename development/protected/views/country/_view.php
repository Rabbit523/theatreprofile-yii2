<?php
/* @var $this CountryController */
/* @var $data Country */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('countryName')); ?>:</b>
	<?php echo CHtml::encode($data->countryName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('countryCode')); ?>:</b>
	<?php echo CHtml::encode($data->countryCode); ?>
	<br />


</div>