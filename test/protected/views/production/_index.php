<li class="span2">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
	}
	else
	{
		$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$data->show->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}
		else
		{					
			$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
		}
	}
	$venue_count = count($data->productionvenues);
	if($venue_count==1)
	{
		$productionvenue = array_values($data->productionvenues)[0];
		$title = $data->show->showName.'<br />'.(!empty($data->productionName)?$data->productionName.'<br /><br />':$productionvenue->venue->venueName.'<br />'.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->country->countryCode);
	}
	else if($venue_count>1)
	{
		$title = $data->show->showName.'<br />'.(!empty($data->productionName)?$data->productionName.'<br /><br />':'Multiple Venues'.'<br /><br />');
	}
	else
	{
		$title = $data->show->showName.'<br />'.(!empty($data->productionName)?$data->productionName.'<br /><br />':'Venue not available'.'<br /><br />');
	}
	?>
	<a class="thumbnail" href="<?php echo $data->createUrl(); ?>" title="<?php echo CHtml::encode($title);?>">
		<img data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="140px" height="220px">
		<div class="caption">
			<?php echo $title; ?>
		</div>
	</a>
</li>