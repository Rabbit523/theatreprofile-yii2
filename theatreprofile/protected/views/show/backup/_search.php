<?php
/* @var $this ShowManagementController */
/* @var $model Show */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'showName'); ?>
		<?php echo $form->textField($model,'showName',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'categoryID'); ?>
		<?php echo $form->textField($model,'categoryID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'showDesc'); ?>
		<?php echo $form->textField($model,'showDesc',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'showDate'); ?>
		<?php echo $form->textField($model,'showDate'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->