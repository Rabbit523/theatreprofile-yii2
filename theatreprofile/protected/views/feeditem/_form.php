<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'feeditem-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'feedID'); ?>
		<?php echo $form->textField($model,'feedID',array('class'=>'span4')); ?>
		<?php echo $form->error($model,'feedID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textArea($model,'title',array('size'=>60,'maxlength'=>1000,'class'=>'span4','rows'=>4)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descr'); ?>
		<?php echo $form->textArea($model,'descr',array('size'=>60,'maxlength'=>5000,'class'=>'span4','rows'=>4)); ?>
		<?php echo $form->error($model,'descr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'href'); ?>
		<?php echo $form->textField($model,'href',array('size'=>60,'maxlength'=>1000,'class'=>'span4')); ?>
		<?php echo $form->error($model,'href'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'publishDate'); ?>
		<?php echo $form->textField($model,'publishDate',array('class'=>'span4')); ?>
		<?php echo $form->error($model,'publishDate'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->