<li>
	<div class="media">
	<a href="<?php echo $data->individual->createUrl(); ?>" class="pull-left">
	<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=3 AND profileID='.$data->individual->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w28h44.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_28x44.gif';
		}
		$title = $data->individual->firstName.' '.$data->individual->middleName.' '.$data->individual->lastName.' '.$data->individual->suffix;
	?>
		<img class="media-object" src="<?php echo $image_url; ?>" width="28px" height="44px" alt="">
	</a>
	
	<div class="media-body">
		<div class="media-heading">
			<a href="<?php echo $data->individual->createUrl(); ?>" title="<?php echo $title;?>">
				<?php echo $title;?>
			</a>
		</div>
	</div>
</li>