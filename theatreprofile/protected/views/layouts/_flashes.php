<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
	echo '<div class="alert alert-' .$key. '"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$message.'</div>';
}