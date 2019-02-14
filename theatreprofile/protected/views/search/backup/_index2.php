<li class="span2">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=yii::app()->request->baseUrl.'/images/uploads/'.$profile_image->image->imageURL;
	}else
	{
		$image_url='http://placehold.it/140x220&text=Profile%20Picture';
	}
	$venueInfo='';
	if(count($data->productionvenues)==1)
	{
		$venues=array_values($data->productionvenues)[0];
		$venueInfo = $venues->venue->venueName.', '.$venues->venue->address->city.', '.$venues->venue->address->state.', '.$venues->venue->address->country->countryName;
	}
	else if(count($data->productionvenues)>1)
	{
		$venueInfo = 'Multiple venues';
	}
	else
	{
		$venueInfo = 'Venue not available';
	}
	?>
	
	<a class="thumbnail" href="<?php echo yii::app()->createUrl('/production/view',array('id'=>$data->id)) ?>">
		<img src="<?php echo $image_url; ?>" style="height:220px;width:140px" alt="">
		<div class="caption">
			<?php echo $data->show->showName.'<br />'.$venueInfo.' ('.Yii::app()->dateFormatter->formatDateTime($data->startDate, 'medium', null).' - '.Yii::app()->dateFormatter->formatDateTime($data->endDate, 'medium', null).')'; ?>
		</div>
	</a>
</li>