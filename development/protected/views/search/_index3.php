<li class="span2">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=3 AND imageType=1 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
	}else
	{
		$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
	}
	$title = $data->firstName.' '.$data->middleName.' '.$data->lastName.' '.$data->suffix;
	?>
	<a class="thumbnail" href="<?php echo $data->createUrl(); ?>" title="<?php echo $title;?>">
		<img src="<?php echo $image_url; ?>" width="140px" height="220px" alt="">
		<div class="caption">
			<?php echo $title; ?>
		</div>
	</a>
</li>