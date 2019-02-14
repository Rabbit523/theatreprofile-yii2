<div class="form">
<?php echo CHtml::beginForm(array('/user/login')); 
$link = '//'.Yii::app()->controller->uniqueid .'/'.Yii::app()->controller->action->id;
echo CHtml::hiddenField('quicklogin', $link);
?>

<?php echo CHtml::errorSummary($model); ?>

<div class="row">
	<?php echo CHtml::activeTextField($model,'username', array('class' => "input-block-level","placeholder"=>'Username or Email')) ?>
</div>

<div class="row" style="padding-top:12px;">
	<?php echo CHtml::activePasswordField($model,'password', array('class' => "input-block-level","placeholder"=>'Password')); ?>
</div>

<div class="row rememberMe">
	<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
	<?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
</div>

<div class="row">
	<?php echo CHtml::submitButton('Login',array('class' => "span3")); ?>
	<?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
</div>
        
<?php echo CHtml::endForm(); ?>
</div>