<li>
	<div class="media">
	<a href="<?php echo $data->company->createUrl(); ?>" class="pull-left">
	<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=5 AND profileID='.$data->company->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
		}
		$ComapanyAddress = array_values($data->company->companyaddresses)[0];
		$title = $data->company->companyName.'<br />'.$ComapanyAddress->address->city.', '.$ComapanyAddress->address->state.', '.$ComapanyAddress->address->country->countryName;
	?>
		<img class="media-object" src="<?php echo $image_url; ?>" width="75px" height="45px" alt="">
	</a>
	
	<div class="media-body">
		<div class="media-heading">
			<a href="<?php echo $data->company->createUrl(); ?>" title="<?php echo $title;?>">
				<?php echo $title;?>
			</a>
			<?php
				echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/company/analytics',array('id'=>$data->company->id))."'><i class='icon-info-sign icon-red'></i></a>";	
			?>
		</div>
	</div>
</li>