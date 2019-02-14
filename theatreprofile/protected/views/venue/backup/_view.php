<?php
/* @var $this VenueController */
/* @var $data Venue */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('venueName')); ?>:</b>
	<?php echo CHtml::encode($data->venueName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('addressID')); ?>:</b>
	<?php echo CHtml::encode($data->addressID); ?>
	<br />


</div>