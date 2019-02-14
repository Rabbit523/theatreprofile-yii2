<?php
$this->pageTitle="Administrator - ".Yii::app()->name;
?>
<h3>Administrator</h3>
<ul>
	<li><a href="<?php echo yii::app()->createUrl('audit'); ?>">Audit</a></li>
	<li><a href="<?php echo yii::app()->createUrl('rights'); ?>">Rights</a></li>
	<li><a href="<?php echo yii::app()->createUrl('user'); ?>">Users</a></li>	
	<li><a href="<?php echo yii::app()->createUrl('feeditem'); ?>">News Items</a></li>
	<li><a href="<?php echo yii::app()->createUrl('link'); ?>">Links</a></li>
	<li><a href="<?php echo yii::app()->createUrl('country'); ?>">Countries</a></li>
</ul>