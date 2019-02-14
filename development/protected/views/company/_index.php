<li class="span3">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=5 AND imageType=1 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);	
	}else
	{
		$image_url=yii::app()->request->baseUrl.'/images/default/default_250x150.gif';
	}
	$title = $data->companyName.'</br>';
	?>
	<a class="thumbnail" href="<?php echo $data->createUrl(); ?>" title="<?php echo CHtml::encode($title);?>">
		<img data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="250px" height="150px" alt="" />
		<div class="caption">
			<?php echo $title; ?>
		</div>
	</a>
</li>