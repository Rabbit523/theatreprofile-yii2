<li>
	<div class="media">
	<a href="<?php echo $data->venue->createUrl(); ?>" class="pull-left">
	<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND profileID='.$data->venue->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
		}
		$title = $data->venue->venueName.'<br />'.$data->venue->address->city.', '.$data->venue->address->state.', '.$data->venue->address->country->countryName;
	?>
		<img class="media-object" src="<?php echo $image_url; ?>" width="75px" height="45px" alt="">
	</a>
	
	<div class="media-body">
		<div class="media-heading">
			<a href="<?php echo $data->venue->createUrl(); ?>" title="<?php echo $title;?>">
				<?php echo $title;?>
			</a>
		</div>
	</div>
</li>