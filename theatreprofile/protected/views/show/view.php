<?php
$pageTitle=$model->showName. " - Show - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->showDesc, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('Shows'=>array('/show'),$model->showName);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', 'cfg.modelID='.(isset($model->id)?$model->id:0).';', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".recordList{overflow:hidden;position:relative;}
.btnToggleCollapse {position:absolute;bottom:3px;right:3px;}
#btnWatchlistRemove, #btnWatchlistAdd, #btnOwnershipClaim, #btnOwnershipClaimed, #btnOwnershipRelinquish {margin-left:5px;}
.pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:140px;height:220px;}
#links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
#links img {max-height:60px;margin:0 2px;}");
$updateAccess=Yii::app()->user->checkAccess('Show.UpdateAccess',array('ownerships'=>$model->showownerships)); //include superadmin access
?>
</style>
<div class="row">
	<div class="span10">
		<div class="showInfo">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
				}
				else
				{
					$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
				}
				?>
				<div class="pull-left text-center">
					<div class="pnl-profile-pic">
						<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px">
					</div>
				</div>
			
				<div class="media-body">
					<div class="media-heading"><h1 class="inline"><?php echo $model->showName;?></h1>
					<?php
						if($updateAccess)
						{
							echo "<small><a href='".yii::app()->createUrl('/show/update',array('id'=>$model->id))."'>Edit Profile</a></small>";
							$Showownership = Showownership::model()->find("showID=:showID and userID=:userID",array(':showID'=>$model->id,':userID'=>Yii::app()->user->id));
						}
						if(!Yii::app()->user->isGuest)
						{
							$Showwatchlist = Showwatchlist::model()->find("showID=:showID and userID=:userID",array(':showID'=>$model->id,':userID'=>Yii::app()->user->id));
							if(!empty($Showwatchlist))
							{
								echo " <a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' data-title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>";
							}
							else
							{
								echo " <a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>";
							}
							if(!empty($model->showownerships))
							{
								if(!empty($Showownership))
									echo " <a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users. Click to relinquish ownership.'><i class='icon-ok-circle icon-red'></i></a>";
								else
									echo " <a id='btnOwnershipClaimed' class='btn disabled' rel='tooltip' data-placement='right' title='This profile is claimed by another user. If you think the person who has claimed this profile is not the right person to edit the information please let us know.'><i class='icon-ban-circle icon-red'></i></a>";
							}
							else							
								echo " <a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership of this profile.'><i class='icon-tag icon-red'></i></a>";
							if($updateAccess)
								echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/show/analytics',array('id'=>$model->id))."'><i class='icon-info-sign icon-red'></i></a>";
						}
						else
						{
							echo " <a class='btn' rel='tooltip' data-placement='right' title='You need to be logged in to add this show to your watchlist. Membership is free and you will get many other benefits.'><i class='icon-eye-open icon-red'></i></a>";
						}
					?>
					</div>
					<div>
					<?php
						echo $model->category->categoryName;
						if(isset($model->showDate)) echo ', '.$model->showDate;
					?>
					</div>
					<ul class="unstyled">
						<?php
						$book ='';
						$music='';
						$lyrics='';
						$adaptation='';
						$translation='';
						$concept='';
						
						foreach($model->showcreators as $creator)
						{
							$name = $creator->individual->firstName.' '.$creator->individual->middleName.' '.$creator->individual->lastName.' '.$creator->individual->suffix;	
							switch($creator->role->roleName)
							{
								case "Script/Book":$book = $book.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';
								break;
								case "Lyrics":$lyrics = $lyrics.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Music":$music = $music.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Adaptation":$adaptation = $adaptation.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Translation":$translation = $translation.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Concept":$concept = $concept.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								
								default:break;
							}
						}
						if($book!='')echo '<li><strong>Script/Book: </strong>'.substr($book, 0, -2).'</li>';
						if($music!='')echo '<li><strong>Music: </strong>'.substr($music,0,-2).'</li>';
						if($lyrics!='')echo '<li><strong>Lyrics: </strong>'.substr($lyrics,0,-2).'</li>';
						if($adaptation!='')echo '<li><strong>Adaptation: </strong>'.substr($adaptation,0,-2).'</li>';
						if($translation!='')echo '<li><strong>Translation: </strong>'.substr($translation,0,-2).'</li>';
						if($concept!='')echo '<li><strong>Concept: </strong>'.substr($concept,0,-2).'</li>';
						?>
					</ul>
					
					<p class="clear">
						<?php
							echo $model->showDesc;
						?>
					</p>
				</div>
			</div>
			
			<?php
				$profile_images=Profileimage::model()->findAll('profileType=1 AND imageType=2 AND profileID='.$model->id);
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
				<h4 class="inline">
				Productions
				</h4>
				<?php
					echo '<small>'.CHtml::Link('Add Production',yii::app()->createUrl('/production/create/',array('show'=>$model->id)),array('style'=>'margin-left:5px;')).'</small>';
				?>
				<hr class="red_line"/>
				<?php
					if(count($model->productions)==0)
					{
				?>
					<div class="media">No productions found</div>
				<?php
					}
					else
					{
						foreach($model->productions as $production)
						{
				?>
							<div class="media">
								<a href="<?php echo $production->createUrl(); ?>" class="pull-left">
									<?php
									$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$production->id);
									if(isset($profile_image->image->imageURL))
									{
										$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
									}
									else
									{
										$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$model->id);
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
										<a href="<?php echo $production->createUrl(); ?>">
											<?php
											echo $model->showName.' - ';
											if(count($production->productionvenues)==1)
											{
												$venues=array_values($production->productionvenues)[0];
												echo !empty($production->productionName)?$production->productionName:$venues->venue->venueName.', '.$venues->venue->address->city.', '.$venues->venue->address->state.', '.$venues->venue->address->country->countryName;
											}
											else if(count($production->productionvenues)>1)
											{
												echo !empty($production->productionName)?$production->productionName:'Multiple venues';
											}
											else
											{
												echo !empty($production->productionName)?$production->productionName:'Venue not available';
											}
											if(!empty($production->startDate))
											{
												$newDate = DateTime::createFromFormat('m-d-Y', $production->startDate);
												$production->startDate = $newDate->format('M d, Y');
											}
											if(!empty($production->endDate))
											{
												$newDate = DateTime::createFromFormat('m-d-Y', $production->endDate);
												$production->endDate = $newDate->format('M d, Y');
											}
											echo '<br />('.$production->startDate.' - '.$production->endDate.')'
										?>
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
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-7' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-7'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(".showInfo").on("click","#btnWatchlistAdd",function(){
	$.ajax({
		type: "POST",
		url: cfg.showBaseUrl+'/watchlistAdd',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&showID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistAdd").replaceWith("<a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>")
		}
	});
});
$(".showInfo").on("click","#btnWatchlistRemove",function(){
	$.ajax({
		type: "POST",
		url: cfg.showBaseUrl+'/watchlistRemove',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&showID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistRemove").replaceWith("<a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>")
		}
	});
});
$(".showInfo").on("click","#btnOwnershipClaim",function(){
	bootbox.confirm("Are you sure you wish to claim this profile? By claiming this profile you acknowledge you are the owner or representative of the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.showBaseUrl+'/ownershipClaim',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&showID='+cfg.modelID,
				success: function(msg){
					location.reload();
					//$('.tooltip').remove();
					//$("#btnOwnershipClaim").replaceWith("<a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users.'><i class='icon-ok-circle icon-red'></i></a>")
				}
			});
		}
	}); 
});
$(".showInfo").on("click","#btnOwnershipRelinquish",function(){
	bootbox.confirm("Are you sure you want to release this profile? By clicking “OK” anyone registered with Theatre Profile will be able to edit the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.showBaseUrl+'/ownershipRelinquish',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&showID='+cfg.modelID,
				success: function(msg){
					location.reload();
					//$('.tooltip').remove();
					//$("#btnOwnershipRelinquish").replaceWith("<a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership to make changes to this profile.'><i class='icon-tag icon-red'></i></a>")
				}
			});
		}
	});
});
JS
, CClientScript::POS_READY);
?>
