<li class="span3">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=5 AND imageType=1 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
	}else
	{
		$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
	}
	$Companyaddress = array_values($data->companyaddresses)[0];
	$Address=$Companyaddress->address;
	$title = $data->companyName.'<br />'.$Address->city.', '.$Address->state.', '.$Address->country->countryName;
	?>
	<a class="thumbnail" href="<?php echo $data->createUrl(); ?>" title="<?php echo $title;?>">
		<img src="<?php echo $image_url; ?>" width="75px" height="45px" alt="">
		<div class="caption">
			<?php echo $title; ?>
		</div>
	</a>
</li>