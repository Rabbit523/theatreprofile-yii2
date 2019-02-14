<li class="span2">
	<?php
	$updateAccess=Yii::app()->user->checkAccess('Production.UpdateAccess',array('ownerships'=>$data->production->productionownerships)); //include superadmin access
	$profile_image=Profileimage::model()->with('image')->find('profileType=3 AND imageType=1 AND profileID='.$data->individualID);
	if(isset($profile_image->image->imageURL))
	{
		$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w105h165.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
	}
	else
	{
		$image_url=yii::app()->request->baseUrl.'/images/default/default_105x165.gif';
	}
	?>
	<div class="thumbnail">
		<a href="<?php echo $data->individual->createUrl() ?>">
			<img data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="105px" height="165px" alt="">
		</a>
		<div class="caption">
			
			<?php
				$startDate=null;
				$endDate=null;
				if(!empty($data->startDate))
				{
					$startDate = DateTime::createFromFormat('m-d-Y', $data->startDate);
					$startDate = $startDate->format('Y');
				}
				if(!empty($data->endDate))
				{
					$endDate = DateTime::createFromFormat('m-d-Y', $data->endDate);
					$endDate = $endDate->format('Y');
				}
				/*
				if(empty($startDate)&&empty($endDate))
				{
					$startDate = Yii::app()->dateFormatter->format('yyyy',$data->production->startDate);
					$endDate = Yii::app()->dateFormatter->format('yyyy',$data->production->endDate);
				}
				*/
				$linkText='';
				$linkText=$data->individual->firstName.' '.$data->individual->middleName.' '.$data->individual->lastName.'<br/>'.$data->roleName;
				echo "<a href='".$data->individual->createUrl()."' title='".preg_replace('#<br\s*/?>#i', "\n", $linkText)."'>".$linkText;
				echo "</a>";
				if(!empty($startDate))
				{
					if($startDate==$endDate)
					{
						echo '<br />'.$startDate;
					}
					else
					{
						echo '<br />'.$startDate.' - '.$endDate;
					}
				}
				else
				{
						echo '<br />&nbsp;';
				}
				echo '<br />';
			?>
			<?php
				if(!Yii::app()->user->isGuest)
				{
					$Productioncastrating = Productioncastrating::model()->find("productionCastID=:productionCastID and userID=:userID",array(':productionCastID'=>$data->id,':userID'=>Yii::app()->user->id));
					$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'castRating'.$data->id,'name'=>'castRating'.$data->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>empty($Productioncastrating)?'0':(float)$Productioncastrating->rating,'htmlOptions'=>array('class'=>'hide castRating'),
					'callback'=>'
						function(){
						$.ajax({
							type: "POST",
							url: "'.Yii::app()->createUrl('productioncast/rating').'",
							data: "YII_CSRF_TOKEN='.Yii::app()->request->csrfToken.'&productionCastID='.$data->id.'&value=" + $(this).val(),
							success: function(msg){
								//alert("Thank you for rating this production a" + msg);
							}
						})}'
					));
				}
				else
				{
					$Productioncastrating = Productioncastrating::model()->find("productionCastID=:productionCastID and userID=:userID",array(':productionCastID'=>$data->id,':userID'=>Yii::app()->user->id));
					$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'castRating'.$data->id,'readOnly'=>true,'name'=>'castRating'.$data->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>'0',
					'htmlOptions'=>array('class'=>'castRating',"rel"=>"tooltip","data-placement"=>"top","title"=>"Log in to rate performances"),
					));
				}
				if($updateAccess||$data->production->privateRatings==0)
				{
					if($data->ratingcount!=0)
					{
						echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="right" itemprop="average" class="badge badge-important avgRating"><strong>'.$data->avgrating.'</strong></span>';
					}
					else
						echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
				}
				else
					echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
			?>
		</div>
	</div>
</li>
