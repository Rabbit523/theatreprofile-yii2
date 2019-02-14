<li >
	<div class="media">
		<a href="<?php echo $data->createUrl(); ?>" class="pull-left">
			<?php
			$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$data->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w28h44.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			}else
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$data->showID);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w28h44.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
				}
				else
				{					
					$image_url=yii::app()->request->baseUrl.'/images/default/default_28x44.gif';
				}
			}
			?>

			<img class="media-object" src="<?php echo $image_url; ?>" width="28px" height="44px" alt="">
		</a>
		
		<div class="media-body">
			<div class="media-heading">
				<?php
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
			
				<a href="<?php echo $data->createUrl(); ?>">
					<?php echo $data->show->showName.' - '.(!empty($data->productionName)?$data->productionName:$venueInfo).'<br />('.$data->startDate.' - '.$data->endDate.')'; ?>
				</a>
			</div>
		</div>
	</div>
</li>