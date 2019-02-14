<?php
/* @var $this SiteController */
$venue_count = count($model->productionvenues);
if($venue_count==1)
{
	$venue = array_values($model->productionvenues)[0];
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' at '.$venue->venue->venueName);
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' at '.$venue->venue->venueName)." - Production - ".Yii::app()->name;
}
else if($venue_count>1)
{
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' - Multiple venues');
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' - Multiple venues')." - Production - ".Yii::app()->name;
}
else
{
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' - Venue not available');
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' - Venue not available')." - Production - ".Yii::app()->name;
}
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag(($model->descr!=''?$model->descr:$model->show->showDesc), null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle","#rating {padding-right:10px;}
#btnWatchlistRemove, #btnWatchlistAdd, #btnOwnershipClaim, #btnOwnershipClaimed, #btnOwnershipRelinquish {margin-left:5px;}
.recordList{overflow: hidden;position: relative;}
.btnToggleCollapse {position:absolute;bottom:3px;right:3px;}
.thumbnail .caption {text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
.links{margin-bottom:0px;}
#links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
#links img {max-height:60px;margin:0 2px;}
.pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:140px;height:220px;}
.castRating{height:18px;}
.avgRating{vertical-align:top;}");
$updateAccess=Yii::app()->user->checkAccess('Production.UpdateAccess',array('ownerships'=>$model->productionownerships)); //include superadmin access
?>
<div class="row">
	<div class="span9">
		<div class="productionInfo">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$model->id);
				$show=Show::model()->with('category')->with('showcreators')->findByPk($model->show->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
					//Yii::app()->clientScript->registerMetaTag("140", null, null, array('property' => "og:image:width"));
					//Yii::app()->clientScript->registerMetaTag("220", null, null, array('property' => "og:image:height"));
				}
				else
				{
					$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$show->id);
					if(isset($profile_image->image->imageURL))
					{
						$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
						//Yii::app()->clientScript->registerMetaTag("140", null, null, array('property' => "og:image:width"));
						//Yii::app()->clientScript->registerMetaTag("220", null, null, array('property' => "og:image:height"));
					}
					else
					{					
						$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
						//Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/default/default_140x220.gif', null, null, array('property' => "og:image"));
						//Yii::app()->clientScript->registerMetaTag("140", null, null, array('property' => "og:image:width"));
						//Yii::app()->clientScript->registerMetaTag("220", null, null, array('property' => "og:image:height"));
					}
				}
				
				?>
				<div class="pull-left text-center">
					<div class="pnl-profile-pic">
						<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="" />
					</div>
				</div>
			
				<div class="media-body">
					<div class="media-heading"><h2 class="inline">
					<?php echo $show->showName; ?><small><?php echo ' '.$model->productionName; ?></small></h2>
					<?php
						if($updateAccess)
						{
							echo "<small><a href='".yii::app()->createUrl('/production/update',array('id'=>$model->id))."'>Edit Profile</a></small>";
							$Productionownership = Productionownership::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>$model->id,':userID'=>Yii::app()->user->id));
						}
						if(!Yii::app()->user->isGuest)
						{
							$Productionwatchlist = Productionwatchlist::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>$model->id,':userID'=>Yii::app()->user->id));
							if(!empty($Productionwatchlist))
								echo " <a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>";
							else
								echo " <a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>";
							if(count(Productioncompanycrew::model()->findByAttributes(array('companyID'=>131,'productionID'=>$model->id)))>0)
								echo ' <a class="btn" target="_blank" href="'.yii::app()->createUrl('/company/program/',array('id'=>131)).'" rel="tooltip" data-placement="right" title="Download program"><i class="icon-book icon-red"></i></a>';							
							if(!empty($model->productionownerships))
							{
								if(!empty($Productionownership))
									echo " <a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users. Click to relinquish ownership.'><i class='icon-ok-circle icon-red'></i></a>";
								else
									echo " <a id='btnOwnershipClaimed' class='btn disabled' rel='tooltip' data-placement='right' title='This profile is claimed by another user. If you think the person who has claimed this profile is not the right person to edit the information please let us know.'><i class='icon-ban-circle icon-red'></i></a>";
							}
							else
								echo " <a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership of this profile.'><i class='icon-tag icon-red'></i></a>";
							if($updateAccess)
								echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/production/analytics',array('id'=>$model->id))."'><i class='icon-info-sign icon-red'></i></a>";
						}
						else
						{
							echo " <a class='btn' rel='tooltip' data-placement='right' title='You need to be logged in to add this production to your watchlist. Membership is free and you will get many other benefits.'><i class='icon-eye-open icon-red'></i></a>";
							if(count(Productioncompanycrew::model()->findByAttributes(array('companyID'=>131,'productionID'=>$model->id)))>0)
								echo ' <a class="btn" target="_blank" href="'.yii::app()->createUrl('/company/program/',array('id'=>131)).'" rel="tooltip" data-placement="right" title="Download program"><i class="icon-book icon-red"></i></a>';							
						}
					?>
					</div>
					<?php
						$Productioncontactinfo=Productioncontactinfo::model()->findAll("contactInfo>'' AND productionID=".$model->id);
						if(count($Productioncontactinfo)>0)
						{
							echo '<div class="social">';
							foreach($Productioncontactinfo as $x)
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
					<div class="clearfix" itemscope itemtype="https://data-vocabulary.org/Review-aggregate">
					<?php
					if(!Yii::app()->user->isGuest)
					{	
						$Productionrating = Productionrating::model()->find("productionID=:productionID and userID=:userID",array(':productionID'=>$model->id,':userID'=>Yii::app()->user->id));
						$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'rating','name'=>'rating','starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>empty($Productionrating)?'0':(float)$Productionrating->rating,'htmlOptions'=>array('class'=>'hide'),
						'callback'=>'
							function(){
							$.ajax({
								type: "POST",
								url: "'.Yii::app()->createUrl('production/rating').'",
								data: "YII_CSRF_TOKEN='.Yii::app()->request->csrfToken.'&productionID='.$model->id.'&value=" + $(this).val(),
								success: function(msg){
									//alert("Thank you for rating this production a" + msg);
								}
							})}'
						));
					}
					else
					{
						$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'rating','readOnly'=>true,'name'=>'rating','starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>'0',
						'htmlOptions'=>array("rel"=>"tooltip","data-placement"=>"right","data-title"=>"You need to be logged in to rate this production. Membership is free and you will get many other benefits."),
						));
					}
					$Productionrating = Productionrating::model()->find("productionID=:productionID",array(':productionID'=>$model->id,));
						
					if($model->privateRatings==0||$updateAccess)
					{
						if($model->ratingcount!=0)
						{
							echo '<span itemprop="rating" itemscope itemtype="https://data-vocabulary.org/Rating">Average rating: <span itemprop="average" class="badge badge-important avgRating"><strong>'.$model->avgrating.'</strong></span> from <span itemprop="votes">'.$model->ratingcount.'</span> users.';
						}
						else
						{
							echo "<small>Average rating not available.</small>";
						}
					}
					else
					{
						echo "<small>Average rating not available.</small>";
					}
					?>
					
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div>
								<strong>Category: </strong><?php echo $show->category->categoryName; ?>, <strong>Duration: </strong><?php echo isset($model->duration)?$model->duration.' minutes':'NA'; ?>, <strong>Intermissions: </strong><?php echo isset($model->intermissions)?$model->intermissions:'NA'; ?>
							</div>
							<ul class="unstyled">
							<?php
								$book ='';
								$music='';
								$lyrics='';
								$adaptation='';
								$translation='';
								$concept='';
								
								foreach($show->showcreators as $creator)
								{
									$name = $creator->individual->firstName.' '.$creator->individual->middleName.' '.$creator->individual->lastName.' '.$creator->individual->suffix;	
									switch($creator->role->roleName)
									{
										case "Script/Book":$book = $book.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
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
								if($venue_count==1)
								{
									$productionvenue = array_values($model->productionvenues)[0];
									echo '<li>';
									echo '<strong>Venue: </strong><a href="'.$productionvenue->venue->createUrl().'">'.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->addr1.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->state.', '.$productionvenue->venue->address->country->countryName.'</a>';
									$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');						
									foreach($links as $link)
									{
										echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini btn-danger">'.$link->label.'</a>';
									}
									echo '</li>';
								}
								
								else if($venue_count>1)
								{
									echo '<li><a href="#venueList">Multiple venues</a></li>';
								}
								else
								{
									echo '<li>Venue not available</li>';
								}
								
								//echo $model->firstPreviewDate;
								//echo DateTime::createFromFormat('m-d-Y', $model->firstPreviewDate);
								if(!empty($model->firstPreviewDate))
								{
									$newDate = DateTime::createFromFormat('m-d-Y', $model->firstPreviewDate);
									$model->firstPreviewDate = $newDate->format('M d, Y');
								}
								if(!empty($model->startDate))
								{
									$newDate = DateTime::createFromFormat('m-d-Y', $model->startDate);
									$model->startDate = $newDate->format('M d, Y');
								}
								if(!empty($model->endDate))
								{
									$newDate = DateTime::createFromFormat('m-d-Y', $model->endDate);
									$model->endDate = $newDate->format('M d, Y');
								}
								echo '<li><strong>First Preview: </strong>'.$model->firstPreviewDate.'</li>';
								echo '<li><strong>Opening: </strong>'.$model->startDate.'</li>';
								echo '<li><strong>Closing: </strong>'.$model->endDate.'</li>';
							?>
							</ul>
						</div>
					</div>
				</div>
				<p class="clear">
					<?php
						if($model->descr!='')
							echo $model->descr;
						else
							echo $model->show->showDesc;
					?>
				</p>
			</div>
		</div>
		
		<?php
			$profile_images=Profileimage::model()->findAll('profileType=2 AND imageType=2 AND profileID='.$model->id);
			if(count($profile_images))
			{
				echo '<div class="row-fluid"><div id="links" class="well">';
				foreach ($profile_images as $profile_image)
				{
					if(isset($profile_image->image->imageURL))
					{
						//$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						$image_url=yii::app()->request->baseUrl.'/images/uploads/'.$profile_image->image->imageURL;
						echo '<a href="'.$image_url.'" data-gallery>';
						echo '<img src="'.$image_url.'" alt="" title="Title not available.">';
						echo '</a>';
					}
				}
				
				echo '</div></div><div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"><!-- The container for the modal slides --><div class="slides"></div><h3 class="title"></h3><a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a><ol class="indicator"></ol></div>';
			}
		?>
		
		<div class="clear"></div>
		<?php
			if($venue_count>1)
			{
		?>
		<br />
		<div class="mini-layout recordList customCollapse" id="venueList">
			<h4>Venues for this production</h4>
			<hr class="red_line" />
			<?php
				if(count($model->productionvenues)==0)
				{
					echo '<div class="media">No venues found.</div>';
				}
				else
				{
					foreach($model->productionvenues as $productionvenue){?>
					<div class="media">
							<a href="<?php echo $productionvenue->venue->createUrl() ?>" class="pull-left">
							<?php
								$profile_image=Profileimage::model()->with('image')->find('profileType=4 and imageType=1 AND profileID='.$productionvenue->venue->id);
								if(isset($profile_image->image->imageURL))
								{
									$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
								}
								else
								{
									$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
								}
							?>
							<img class="media-object" src="<?php echo $image_url; ?>" width="75px" height="45px" alt="">
							</a>
							
							<div class="media-body">
								<div class="media-heading">
								<?php
									$startDate = $productionvenue->startDate;
									$endDate = $productionvenue->endDate;
									if(!empty($startDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $startDate);
										$startDate = $newDate->format('M d, Y');
									}
									if(!empty($endDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $endDate);
										$endDate = $newDate->format('M d, Y');
									}
									if(empty($startDate)&&empty($endDate))
									{
										$startDate = $model->startDate;
										$endDate = $model->endDate;
									}
									
									echo '<a href="'.$productionvenue->venue->createUrl().'">'.$productionvenue->venue->venueName.' - '.$productionvenue->venue->address->addr1.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->state.' - '.$productionvenue->venue->address->country->countryName.'</a><br /> ('.$startDate.' - '.$endDate.')';
									$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');						
									foreach($links as $link)
									{
										echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini btn-danger">'.$link->label.'</a>';
									}
									//echo ' <a href="'.yii::app()->createUrl('/venue/schedule',array('id'=>$productionvenue->venueID)).'" target="_blank" class="btn btn-mini btn-danger">Event Schedule</a>';
									if(empty($productionvenue->venue->venueownerships))
										echo " <a href='".yii::app()->createUrl('/productionevent/create',array('id'=>$productionvenue->venueID,'pvid'=>$productionvenue->id))."' target='_blank' class='btn btn-mini btn-primary'>Add Event Details</a>";
									else
									{
										$Venueownership = Venueownership::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>$productionvenue->venue->id,':userID'=>Yii::app()->user->id));
										if(!empty($Venueownership))
											echo " <a href='".yii::app()->createUrl('/productionevent/create',array('id'=>$productionvenue->id))."' target='_blank' class='btn btn-mini btn-primary'>Add Event Details</a>";
									}
								?>
								</div>
							</div>
						</div>
					<?php }
				}
			?>
		</div>						
		<br />
		<?php
			}
		?>
		<div class="mini-layout recordList customCollapse" data-maxHeight="945">
			<h4>Cast</h4>
			<hr class="red_line" />
			<?php
				/*
				$criteria=new CDbCriteria;
				$criteria->condition="productionID = :productionID";
				$criteria->params=array(
				  ':productionID' => $model->id,
				);
				$criteria->order = '';
				$productioncasts = Productioncast::model()->with('individual')->findAll($criteria);
				*/
				if(count($model->productioncasts)==0)
				{
					echo '<div class="media">No production cast found.</div>';
				}
				else
				{
					$this->widget('bootstrap.widgets.TbThumbnails', array(
					'dataProvider'=>new CArrayDataProvider($model->productioncasts, array('pagination'=>false)),
					//'template'=>"{summary}{pager}\n{items}",
					'itemView'=>'_view1',
					//'summaryText'=>'Displaying {start}-{end} of {count} cast records.',
					'summaryText'=>'',
					'htmlOptions'=>array("class"=>"text-center","style"=>"margin-top:10px;"),
					));
				}
			?>
		</div>
		<div class="mini-layout recordList customCollapse" data-maxHeight="425">
			<h4>Creative, Crew and Staff</h4>
			<hr class="red_line" />
			<?php
			/*
				$criteria=new CDbCriteria;
				$criteria->condition="productionID = :productionID";
				$criteria->params=array(
				  ':productionID' => $model->id,
				);
				$criteria->order = 'endDate desc,startDate asc,individual.lastName asc';
				$productioncrews = Productioncrew::model()->with('individual')->findAll($criteria);
				*/
				if(count($model->productioncrews)==0&&count($model->productioncompanycrews)==0)
				{
					echo '<div class="media">No production crew found.</div>';
				}
				else
				{
					foreach($model->productioncrews as $crew){
						$individual=Individual::model()->findByPk($crew->profileID);
					?>
						<div class="media" itemscope itemtype="https://schema.org/Person">
							<a href="<?php $individual->createUrl() ?>" class="pull-left">
								<?php
								$profile_image=Profileimage::model()->with('image')->find('profileType=3 and imageType=1 AND profileID='.$crew->profileID);
								if(isset($profile_image->image->imageURL))
								{
									$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w28h44.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
								}
								else
								{
									$image_url=yii::app()->request->baseUrl.'/images/default/default_28x44.gif';
								}
								?>
								<img itemprop="image" class="media-object" src="<?php echo $image_url; ?>" width="28px" height="44px" alt="">
							</a>
							
							<div class="media-body">
								<div class="media-heading">			
								<?php
									$startDate = null;
									$endDate = null;
									if(!empty($crew->startDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $crew->startDate);
										$startDate = $newDate->format('M d, Y');
									}
									if(!empty($crew->endDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $crew->endDate);
										$endDate = $newDate->format('M d, Y');
									}
									/*
									if(empty($startDate)&&empty($endDate))
									{
										$startDate = Yii::app()->dateFormatter->format('yyyy',$model->startDate);
										$endDate = Yii::app()->dateFormatter->format('yyyy',$model->endDate);
									}
									*/
								
									$individual=Individual::model()->findByPk($crew->profileID);
									if($startDate==$endDate)
									{
										echo '<a itemprop="url" href="'.$individual->createUrl().'"><span itemprop="name">'.$individual->firstName.' '.$individual->middleName.' '.$individual->lastName.'<span></a> - '.$crew->role->roleName.'<br />'.$startDate.'';
									}
									else
									{
										echo '<a itemprop="url" href="'.$individual->createUrl().'"><span itemprop="name">'.$individual->firstName.' '.$individual->middleName.' '.$individual->lastName.'<span></a> - '.$crew->role->roleName.'<br />'.$startDate.' - '.$endDate;
									}
								?>
								
								<?php
									if(!Yii::app()->user->isGuest)
									{
										$Productioncrewrating = Productioncrewrating::model()->find("productionCrewID=:productionCrewID and userID=:userID",array(':productionCrewID'=>$crew->id,':userID'=>Yii::app()->user->id));
										$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'crewRating'.$crew->id,'name'=>'crewRating'.$crew->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>empty($Productioncrewrating)?'0':(float)$Productioncrewrating->rating,'htmlOptions'=>array('class'=>'hide crewRating'),
										'callback'=>'
											function(){
											$.ajax({
												type: "POST",
												url: "'.Yii::app()->createUrl('productioncrew/rating').'",
												data: "YII_CSRF_TOKEN='.Yii::app()->request->csrfToken.'&productionCrewID='.$crew->id.'&value=" + $(this).val(),
												success: function(msg){
													//alert("Thank you for rating this production a" + msg);
												}
											})}'
										));
									}
									else
									{
										$Productioncrewrating = Productioncrewrating::model()->find("productionCrewID=:productionCrewID and userID=:userID",array(':productionCrewID'=>$crew->id,':userID'=>Yii::app()->user->id));
										$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'crewRating'.$crew->id,'readOnly'=>true,'name'=>'crewRating'.$crew->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>'0',
										'htmlOptions'=>array('class'=>'crewRating',"rel"=>"tooltip","data-placement"=>"top","title"=>"Log in to rate performances"),
										));
									}
									$Productioncrewrating = Productioncrewrating::model()->find("productionCrewID=:productionCrewID",array(':productionCrewID'=>$crew->id,));

									if($updateAccess||$model->privateRatings==0)
									{
										if($crew->ratingcount!=0)
										{
											echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="right" itemprop="average" class="badge badge-important avgRating"><strong>'.$crew->avgrating.'</strong></span>';
										}
										else
											echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
									}
									else
										echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
								?>
								</div>
							</div>
						</div>
					<?php
					}
					foreach($model->productioncompanycrews as $crew){
						$company=$crew->company;
					?>
						<div class="media" itemscope itemtype="https://schema.org/Organization">
							<a href="<?php echo $company->createUrl() ?>" class="pull-left">
								<?php
								$profile_image=Profileimage::model()->with('image')->find('profileType=5 and imageType=1 AND profileID='.$company->id);
								if(isset($profile_image->image->imageURL))
								{
									$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w75h45.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
								}
								else
								{
									$image_url=yii::app()->request->baseUrl.'/images/default/default_75x45.gif';
								}
								?>
								<img itemprop="image" class="media-object" src="<?php echo $image_url; ?>" width="75px" height="45px" alt="">
							</a>
							
							<div class="media-body">
								<div class="media-heading">			
								<?php
									$startDate = null;
									$endDate = null;
									if(!empty($crew->startDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $crew->startDate);
										$startDate = $newDate->format('M d, Y');
									}
									if(!empty($crew->endDate))
									{
										$newDate = DateTime::createFromFormat('m-d-Y', $crew->endDate);
										$endDate = $newDate->format('M d, Y');
									}
									/*
									if(empty($startDate)&&empty($endDate))
									{
										$startDate = Yii::app()->dateFormatter->format('yyyy',$model->startDate);
										$endDate = Yii::app()->dateFormatter->format('yyyy',$model->endDate);
									}
									*/
									if($startDate==$endDate)
									{
										echo '<a itemprop="url" href="'.$company->createUrl().'"><span itemprop="name">'.$company->companyName.'</span></a> - '.$crew->role->roleName.'<br />'.$startDate.'';
									}
									else
									{
										echo '<a itemprop="url" href="'.$company->createUrl().'"><span itemprop="name">'.$company->companyName.'</span></a> - '.$crew->role->roleName.'<br />'.$startDate.' - '.$endDate;
									}
								?>
								<?php
									if(!Yii::app()->user->isGuest)
									{
										$Productioncompanycrewrating = Productioncompanycrewrating::model()->find("productionCompanyCrewID=:productionCompanyCrewID and userID=:userID",array(':productionCompanyCrewID'=>$crew->id,':userID'=>Yii::app()->user->id));
										$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'companycrewRating'.$crew->id,'name'=>'companycrewRating'.$crew->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>empty($Productioncompanycrewrating)?'0':(float)$Productioncompanycrewrating->rating,'htmlOptions'=>array('class'=>'hide companycrewRating'),
										'callback'=>'
											function(){
											$.ajax({
												type: "POST",
												url: "'.Yii::app()->createUrl('productioncompanycrew/rating').'",
												data: "YII_CSRF_TOKEN='.Yii::app()->request->csrfToken.'&productionCompanyCrewID='.$crew->id.'&value=" + $(this).val(),
												success: function(msg){
													//alert("Thank you for rating this production a" + msg);
												}
											})}'
										));
									}
									else
									{
										$Productioncompanycrewrating = Productioncompanycrewrating::model()->find("productionCompanyCrewID=:productionCompanyCrewID and userID=:userID",array(':productionCompanyCrewID'=>$crew->id,':userID'=>Yii::app()->user->id));
										$this->widget('CStarRating',array('allowEmpty'=>false,'id'=>'companycrewRating'.$crew->id,'readOnly'=>true,'name'=>'companycrewRating'.$crew->id,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>'0',
										'htmlOptions'=>array('class'=>'companycrewRating',"rel"=>"tooltip","data-placement"=>"top","title"=>"Log in to rate performances"),
										));
									}
									if($updateAccess||$model->privateRatings==0)
									{
										if($crew->ratingcount!=0)
										{
											echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="right" itemprop="average" class="badge badge-important avgRating"><strong>'.$crew->avgrating.'</strong></span>';
										}
										else
											echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
									}
									else
										echo '&nbsp;&nbsp;<span title="Average rating" rel="tooltip" data-placement="top" itemprop="average" class="badge badge-important avgRating"><strong>NA</strong></span>';
								?>
								
								
								</div>
							</div>
						</div>
					<?php }
				}
			?>
		</div>
	</div>
	
	<div class="span3 last visible-desktop">
		<div class="mini-layout clearfix">
			<strong>Related Links</strong>
			<div class="tabs-left">
				<ul class="nav nav-tabs links">
					<li class="active"><a data-toggle="tab" href="#tab1">Merchandise</a></li>
					<li class=""><a data-toggle="tab" href="#tab2">Tickets</a></li>
					<li class=""><a data-toggle="tab" href="#tab3">Other</a></li>
				</ul>
				<div class="tab-content">
					<div id="tab1" class="tab-pane fade active in"><p>
					<?php
						$links = Link::model()->findAll('profileType=2 AND profileID='.$model->id.' and linkType=2');						
						foreach($links as $link)
						{
							echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-danger" style="margin-bottom:5px;">'.$link->label.'</a>';
						}
					?>							
					</p></div>
					<div id="tab2" class="tab-pane fade"><p>
					<?php
						foreach($model->productionvenues as $productionvenue){
							$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');						
							foreach($links as $link)
							{
								echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-danger" style="margin-bottom:5px;">'.$link->label.'</a>';
							}
						}
					?>
					</p></div>
					<div id="tab3" class="tab-pane fade"><p>
					<?php
						$links = Link::model()->findAll('profileType=2 AND profileID='.$model->id.' and linkType=3');						
						foreach($links as $link)
						{
							echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-danger" style="margin-bottom:5px;">'.$link->label.'</a>';
						}
					?></p></div>
				</div>
			</div>
		</div>
		<?php
			if(count($events)>0){
		?>
		<div class="mini-layout recordList" id="venueList">
			<strong>Upcoming</strong>
			<hr class="red_line" />
			<?php
				$count=0;
				foreach($events as $event){
					if($count==8) break;
					$count++;
			?>
			<div class="media">
				<div class="media-body">
					<div class="media-heading">
					<?php
						//echo "<a href='#'>".$event['start']."</a>";
						echo $event['start'];
						//foreach($event['ticketLinks'] as $ticketLink)
						//{
						//	echo " <a class='btn btn-mini btn-danger href='".$ticketLink['href']."'>".$ticketLink['label']."</a>";
						//}
					?>
					</div>
				</div>					
			</div>
				<?php } ?>
		</div>
		<?php } ?>
		
		
		
		<?php if(!YII_DEBUG): ?>
		<div id='div-gpt-ad-1452790578742-6' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-6'); });
			</script>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(".productionInfo").on("click","#btnWatchlistAdd",function(){
	$.ajax({
		type: "POST",
		url: cfg.productionBaseUrl+'/watchlistAdd',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&productionID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistAdd").replaceWith("<a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>")
		}
	});
});
$(".productionInfo").on("click","#btnWatchlistRemove",function(){
	$.ajax({
		type: "POST",
		url: cfg.productionBaseUrl+'/watchlistRemove',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&productionID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistRemove").replaceWith("<a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>")
		}
	});
});
$(".productionInfo").on("click","#btnOwnershipClaim",function(){
	bootbox.confirm("Are you sure you wish to claim this profile? By claiming this profile you acknowledge you are the owner or representative of the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.productionBaseUrl+'/ownershipClaim',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&productionID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});
$(".productionInfo").on("click","#btnOwnershipRelinquish",function(){
	bootbox.confirm("Are you sure you want to release this profile? By clicking “OK” anyone registered with Theatre Profile will be able to edit the content on this profile.", function(result) {
		if(result)
		{	
			$.ajax({
				type: "POST",
				url: cfg.productionBaseUrl+'/ownershipRelinquish',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&productionID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});
$("#rating").removeClass("hide");
$(".castRating").removeClass("hide").css("display","inline-block");
$(".crewRating").removeClass("hide").css("display","inline-block");
$(".companycrewRating").removeClass("hide").css("display","inline-block");

JS
, CClientScript::POS_READY);
?>