<li >
	<div class="media">
		<a href="<?php echo $data->production->createUrl(); ?>" class="pull-left">
			<?php
			$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$data->production->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w28h44.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			}else
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$data->production->showID);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=yii::app()->request->baseUrl.'/images/uploads/'.$profile_image->image->imageURL;
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
					if(count($data->production->productionvenues)==1)
					{
						$venues=array_values($data->production->productionvenues)[0];
						$venueInfo = $venues->venue->venueName.', '.$venues->venue->address->city.', '.$venues->venue->address->state.', '.$venues->venue->address->country->countryName;
					}
					else if(count($data->production->productionvenues)>1)
					{
						$venueInfo = 'Multiple venues';
					}
					else
					{
						$venueInfo = 'Venue not available';
					}
				?>
			
				<a href="<?php echo $data->production->createUrl(); ?>">
					<?php
						echo $data->production->show->showName.' - '.(!empty($data->production->productionName)?$data->production->productionName:$venueInfo);
					?>
				</a>
				<?php
					echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/production/analytics',array('id'=>$data->production->id))."'><i class='icon-info-sign icon-red'></i></a>";
					echo '<br />('.$data->production->startDate.' - '.$data->production->endDate.')';
				?>
			</div>
		</div>
	</div>
</li>