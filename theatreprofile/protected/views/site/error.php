<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
 If you think you have received this message in error please let us know at <a href='mailto:info@theatreprofile.com'>info@theatreprofile.com.</a>
</div>