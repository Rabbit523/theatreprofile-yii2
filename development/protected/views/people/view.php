<?php
$pageTitle = $model->firstName.' '.($model->middleName!=''?$model->middleName.' ':'').($model->lastName!=''?$model->lastName.' ':'').$model->suffix." - People - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('People'=>array('index'),$model->firstName.' '.$model->lastName);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".recordList{overflow: hidden;position: relative;}
.btnToggleCollapse {position:absolute;bottom:3px;right:3px;}
#btnWatchlistRemove, #btnWatchlistAdd, #btnOwnershipClaim, #btnOwnershipClaimed, #btnOwnershipRelinquish {margin-left:5px;}
.pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:140px;height:220px;}
#links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
#links img {max-height:60px;margin:0 2px;}");
$updateAccess=Yii::app()->user->checkAccess('People.UpdateAccess',array('ownerships'=>$model->individualownerships));
?>
<div class="row">
	<div class="span10">
		<div class="individualInfo" itemscope itemtype="https://data-vocabulary.org/Person">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=3 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
				}else
				{
					$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
				}
				?>
				<div class="pull-left text-center">
					<div class="pnl-profile-pic">
						<span itemprop="photo">
						<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="">
						</span>
					</div>
				</div>
			
				<div class="media-body">
					<div class="media-heading"><h1 class="inline">
						<span itemprop="name">
						<?php echo $model->firstName.' '.$model->middleName.' '.$model->lastName.' '.$model->suffix; ?>
						</span>
					</h1>
					<?php
						if($updateAccess)
						{
							echo "<small><a href='".yii::app()->createUrl('/people/update',array('id'=>$model->id))."'>Edit Profile</a></small>";
							$Individualownership = Individualownership::model()->find("individualID=:individualID and userID=:userID",array(':individualID'=>$model->id,':userID'=>Yii::app()->user->id));
						}
						if(!Yii::app()->user->isGuest)
						{
							$Individualwatchlist = Individualwatchlist::model()->find("individualID=:individualID and userID=:userID",array(':individualID'=>$model->id,':userID'=>Yii::app()->user->id));
							if(!empty($Individualwatchlist))
								echo " <a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>";							
							else
								echo " <a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>";
							if(!empty($model->individualownerships))
							{
								if(!empty($Individualownership))
									echo " <a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users. Click to relinquish ownership.'><i class='icon-ok-circle icon-red'></i></a>";
								else
									echo " <a id='btnOwnershipClaimed' class='btn disabled' rel='tooltip' data-placement='right' title='This profile is claimed by another user. If you think the person who has claimed this profile is not the right person to edit the information please let us know.'><i class='icon-ban-circle icon-red'></i></a>";
							}
							else
								echo " <a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership of this profile.'><i class='icon-tag icon-red'></i></a>";
							if($updateAccess)
								echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/people/analytics',array('id'=>$model->id))."'><i class='icon-info-sign icon-red'></i></a>";
						}
						else
						{
							echo " <a class='btn' rel='tooltip' data-placement='right' title='You need to be logged in to add this theatre personality to your watchlist. Membership is free and you will get many other benefits.'><i class='icon-eye-open icon-red'></i></a>";
						}
					?>
					</div>
					<?php
						$Individualcontactinfo=Individualcontactinfo::model()->findAll("contactInfo>'' AND individualID=".$model->id);
						if(count($Individualcontactinfo)>0)
						{
							echo '<div class="social">';
							foreach($Individualcontactinfo as $x)
							{
								echo '<a target="_blank" href="'.$x->contactInfo.'">';
								switch($x->contactTypeID)
								{
									case 1: 	echo '<i class="fa fa-custom fa-facebook-official"></i>';
												break;
									case 2: 	echo '<i class="fa fa-custom fa-google-plus"></i></i>';
												break;
									case 3: 	echo '<i class="fa fa-custom fa-twitter-square"></i>';
												break;
									case 4: 	echo '<i class="fa fa-custom fa-instagram"></i>';
												break;
									case 5: 	echo '<i class="fa fa-custom fa-globe"></i>';
												break;
									default: 	break;
								}
								echo '</a>';
							}
							echo '</div>';
						}
					?>
					<ul class="unstyled">
						<?php
							if($model->gender==1)
								echo '<li><span>Male</span></li>';
							else if($model->gender==2)
								echo '<li><span>Female</span></li>';
							//echo '<li><span>'.$model->country->countryName.'</span></li>';
							echo '<li><span>'.$model->descr.'</span></li>';
						?>
					</ul>
				</div>
			</div>
		</div>
		
		<?php
			$profile_images=Profileimage::model()->findAll('profileType=3 AND imageType=2 AND profileID='.$model->id);
			if(count($profile_images))
			{
				echo '<div class="row-fluid"><div id="links" class="well">';
				foreach ($profile_images as $profile_image)
				{
					if(isset($profile_image->image->imageURL))
					{
						//$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						$image_url=Yii::app()->params["mediaServeUrl"].'/images/uploads/'.$profile_image->image->imageURL;
						echo '<a href="'.$image_url.'" data-gallery>';
						echo '<img src="'.$image_url.'" alt="" title="Title not available.">';
						echo '</a>';
					}
				}
				
				echo '</div></div><div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"><!-- The container for the modal slides --><div class="slides"></div><h3 class="title"></h3><a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a><ol class="indicator"></ol></div>';
			}
		?>

		<div class="clear"></div>
		<div class="mini-layout recordList customCollapse">
			<h4>Actor</h4>
			<hr class="red_line"/>
				<?php
				if(count($model->productioncasts)==0)
				{
					echo '<div class="media">No records found</div>';
				}
				else
				{
					foreach($model->productioncasts as $productioncast){
				?>
					<div class="media">
						<a href="<?php echo $productioncast->production->createUrl() ?>" class="pull-left">
							<?php
							$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$productioncast->productionID);
							if(isset($profile_image->image->imageURL))
							{
								$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
							}
							else
							{
								$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$productioncast->production->showID);
								if(isset($profile_image->image->imageURL))
								{
									$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
								}
								else
								{					
									$image_url=yii::app()->request->baseUrl.'/images/default/default_42x66.gif';
								}
							}
							?>
							<img class="media-object" data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="42px" height="66px" alt="" />
						</a>
						
						<div class="media-body">
							<div class="media-heading">
								<?php
									$productionInfo='';
									if(count($productioncast->production->productionvenues)==1)
									{
										$venues=array_values($productioncast->production->productionvenues)[0];
										$productionInfo= '<a href="'.$productioncast->production->createUrl().'">'.$productioncast->production->show->showName.(!empty($productioncast->production->productionName)?' - '.$productioncast->production->productionName:' - '.$venues->venue->venueName).'</a>';
									}
									else if(count($productioncast->production->productionvenues)>1)
									{
										$productionInfo= '<a href="'.$productioncast->production->createUrl().'">'.$productioncast->production->show->showName.(!empty($productioncast->production->productionName)?' - '.$productioncast->production->productionName:' - Multiple venues').'</a>';
									}
									else
									{
										$productionInfo= '<a href="'.$productioncast->production->createUrl().'">'.$productioncast->production->show->showName.(!empty($productioncast->production->productionName)?' - '.$productioncast->production->productionName:' - Venue not available').'</a>';
									}
									
									$startDate = null;
									$endDate = null;
									if(!empty($productioncast->startDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $productioncast->startDate);
										$startDate = $newDate->format('M d, Y');
									}
									if(!empty($productioncast->endDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $productioncast->endDate);
										$endDate = $newDate->format('M d, Y');
									}
									
									echo $productionInfo.'<br />'.$productioncast->roleName.' ('.$startDate.' - '.$endDate.')';
									echo '<br />';
									foreach($productioncast->production->productionvenues as $productionvenue)
									{
										$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');	
										foreach($links as $link)
										{
											echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini btn-danger">'.$link->label.'</a>';
										}
									}
								?>
							</div>
						</div>
					</div>
					<?php 
						}
					}
					?>
		</div>
		 
		 
		<div class="mini-layout recordList customCollapse">
			<h4>Creative, Crew and Staff</h4>
			<hr class="red_line"/>
			<?php
			//$productioncrews = Productioncrew::model()->with('role')->findAll('profileID='.$model->id);
			$productioncrews = $model->productioncrews;
			if(count($productioncrews)==0)
			{
				echo '<div class="media">No Records found</div>';
			}
			else
			{
				foreach($productioncrews as $productioncrew){
			?>
				<div class="media">
					<a href="<?php echo $productioncrew->production->createUrl() ?>" class="pull-left">
						<?php
						$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$productioncrew->productionID);
						if(isset($profile_image->image->imageURL))
						{
							$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						}
						else
						{
							$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$productioncrew->production->showID);
							if(isset($profile_image->image->imageURL))
							{
								$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
							}
							else
							{					
								$image_url=yii::app()->request->baseUrl.'/images/default/default_42x66.gif';
							}
						}
						?>
						<img class="media-object" data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="42px" height="66px" alt="" />
					</a>
					
					<div class="media-body">
						<div class="media-heading">
							<?php 
								$productionInfo='';
								if(count($productioncrew->production->productionvenues)==1)
								{
									$venues=array_values($productioncrew->production->productionvenues)[0];
									$productionInfo= '<a href="'.$productioncrew->production->createUrl().'">'.$productioncrew->production->show->showName.(!empty($productioncrew->production->productionName)?' - '.$productioncrew->production->productionName:' - '.$venues->venue->venueName).'</a>';
								}
								else if(count($productioncrew->production->productionvenues)>1)
								{
									$productionInfo= '<a href="'.$productioncrew->production->createUrl().'">'.$productioncrew->production->show->showName.(!empty($productioncrew->production->productionName)?' - '.$productioncrew->production->productionName:' - Multiple venues').'</a>';
								}
								else
								{
									$productionInfo= '<a href="'.$productioncrew->production->createUrl().'">'.$productioncrew->production->show->showName.(!empty($productioncrew->production->productionName)?' - '.$productioncrew->production->productionName:' - Venue not available').'</a>';
								}
								
								$startDate = null;
								$endDate = null;
								if(!empty($productioncrew->startDate))
								{
									$newDate = DateTime::createFromFormat('m-d-Y', $productioncrew->startDate);
									$startDate = $newDate->format('M d, Y');
								}
								if(!empty($productioncrew->endDate))
								{
									$newDate = DateTime::createFromFormat('m-d-Y', $productioncrew->endDate);
									$endDate = $newDate->format('M d, Y');
								}
								
								echo $productionInfo.'<br />'.$productioncrew->role->roleName.' ('.$startDate.' - '.$endDate.')';
							?>
						</div>
					</div>
				</div>
			<?php
				}
			}
			?>
		</div>
		 
		<div class="mini-layout recordList customCollapse">
			<h4>Author</h4>
			<hr class="red_line"/>
			<?php
			$showCreators = Showcreator::model()->with('role')->with('show')->findAll('individualID='.$model->id);
			if(count($showCreators)==0)
			{
				echo '<div class="media">No Records found</div>';
			}
			else
			{
				foreach($showCreators as $showCreator)
				{
			?>
				<div class="media">
					<a href="<?php echo $showCreator->show->createUrl() ?>" class="pull-left">
						<?php
						$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$showCreator->showID);
						if(isset($profile_image->image->imageURL))
						{
							$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						}
						else
						{
							$image_url=yii::app()->request->baseUrl.'/images/default/default_42x66.gif';
						}
						?>

						<img class="media-object" data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="42px" height="66px" alt="">
					</a>
					
					<div class="media-body">
						<div class="media-heading">
							<a href="<?php echo $showCreator->show->createUrl() ?>">
								<?php echo $showCreator->show->showName.'<br />'.$showCreator->role->roleName; ?>
							</a>
						</div>
					</div>
				</div>
			<?php
				}
			}
			?>
		</div>
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-5' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-5'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(".individualInfo").on("click","#btnWatchlistAdd",function(){
	$.ajax({
		type: "POST",
		url: cfg.peopleBaseUrl+'/watchlistAdd',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&individualID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistAdd").replaceWith("<a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>")
		}
	});
});
$(".individualInfo").on("click","#btnWatchlistRemove",function(){
	$.ajax({
		type: "POST",
		url: cfg.peopleBaseUrl+'/watchlistRemove',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&individualID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistRemove").replaceWith("<a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>")
		}
	});
});
$(".individualInfo").on("click","#btnOwnershipClaim",function(){
	bootbox.confirm("Are you sure you wish to claim this profile? By claiming this profile you acknowledge you are the owner or representative of the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.peopleBaseUrl+'/ownershipClaim',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&individualID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});
$(".individualInfo").on("click","#btnOwnershipRelinquish",function(){
	bootbox.confirm("Are you sure you want to release this profile? By clicking “OK” anyone registered with Theatre Profile will be able to edit the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.peopleBaseUrl+'/ownershipRelinquish',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&individualID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});	
});
JS
, CClientScript::POS_READY);
?>