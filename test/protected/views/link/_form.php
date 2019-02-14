<?php
/* @var $this LinkController */
/* @var $model Link */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'link-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'profileID'); ?>
		<?php echo $form->textField($model,'profileID',array('class'=>'span3')); ?>
		<?php echo $form->error($model,'profileID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'profileType'); ?>
		<?php echo $form->dropDownList($model,'profileType',array('2'=>'Production','5'=>'ProductionVenue'),array('class'=>'span3')); ?>
		<?php echo $form->error($model,'profileType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'href'); ?>
		<?php echo $form->textField($model,'href',array('class'=>'span3','maxlength'=>500),array('class'=>'span3')); ?>
		<?php echo $form->error($model,'href'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'linkType'); ?>
		<?php echo $form->dropDownList($model,'linkType',array('1'=>'Ticketing','2'=>'Merchandising','3'=>'Other'),array('class'=>'span3')); ?>
		<?php echo $form->error($model,'linkType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('class'=>'span3','maxlength'=>100)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->