<li class="span2">
	<?php
	$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$data->id);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
	}
	else
	{
		$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
		//$image_url='';
	}
	?>
	
	<a class="thumbnail" href="<?php echo $data->createUrl(); ?>" title="<?php echo CHtml::encode($data->showName); ?>">
		<?php
		if($image_url!='')
		{
		?>
		<img data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="" />
		<?php
		}
		else { ?>
			<span class="centered-child"><?php echo $data->showName; ?></span>
		</div>
		<?php } ?>
		<div class="caption">
			<?php echo $data->showName; ?>
		</div>
	</a>
</li>