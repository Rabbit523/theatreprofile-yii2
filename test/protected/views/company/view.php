<?php
$pageTitle=$model->companyName." - Company - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('Companies'=>array('/company'),$model->companyName);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".recordList{overflow: hidden;position: relative;}
.btnToggleCollapse {position:absolute;bottom:3px;right:3px;}
#btnWatchlistRemove, #btnWatchlistAdd, #btnOwnershipClaim, #btnOwnershipClaimed, #btnOwnershipRelinquish {margin-left:5px;}
.pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:250px;height:150px;}
#links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
#links img {max-height:60px;margin:0 2px;}");
$updateAccess=Yii::app()->user->checkAccess('Company.UpdateAccess',array('ownerships'=>$model->companyownerships));
$Companyownership = Companyownership::model()->find("companyID=:companyID and userID=:userID",array(':companyID'=>$model->id,':userID'=>Yii::app()->user->id));
?>
<div class="row">
	<div class="span10">
		<div class="companyInfo">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=5 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
				}else
				{
					$image_url=yii::app()->request->baseUrl.'/images/default/default_250x150.gif';
				}
				?>
				<div class="pull-left text-center">
					<div class="pnl-profile-pic">
						<img class="media-object" src="<?php echo $image_url; ?>" width="250px" height="150px" alt="" />
					</div>
				</div>
			
				<div class="media-body">
					<div class="media-heading">
						<h1 class="inline"><?php echo $model->companyName; ?></h1>
						<?php
							if($updateAccess)
							{
								echo "<small><a href='".yii::app()->createUrl('/company/update',array('id'=>$model->id))."'>Edit Profile</a></small>";
							}
							if(!Yii::app()->user->isGuest)
							{
								$Companywatchlist = Companywatchlist::model()->find("companyID=:companyID and userID=:userID",array(':companyID'=>$model->id,':userID'=>Yii::app()->user->id));
								if(!empty($Companywatchlist))
									echo " <a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>";
								else
									echo " <a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>";
								if($model->id==131)
									echo ' <a class="btn" target="_blank" href="'.yii::app()->createUrl('/company/program/',array('id'=>$model->id)).'" rel="tooltip" data-placement="right" title="Download program"><i class="icon-book icon-red"></i></a>';
								if(!empty($model->companyownerships))
								{
									if(!empty($Companyownership))
										echo " <a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users. Click to relinquish ownership.'><i class='icon-ok-circle icon-red'></i></a>";
									else
										echo " <a id='btnOwnershipClaimed' class='btn disabled' rel='tooltip' data-placement='right' title='This profile is claimed by another user. If you think the person who has claimed this profile is not the right person to edit the information please let us know.'><i class='icon-ban-circle icon-red'></i></a>";
								}
								else
									echo " <a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership of this profile.'><i class='icon-tag icon-red'></i></a>";
								if($updateAccess)
									echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/company/analytics',array('id'=>$model->id))."'><i class='icon-info-sign icon-red'></i></a>";
							}
							else
							{
								echo " <a class='btn' rel='tooltip' data-placement='right' title='You need to be logged in to add this company to your watchlist. Membership is free and you will get many other benefits.'><i class='icon-eye-open icon-red'></i></a>";
								if($model->id==131)
									echo ' <a class="btn" target="_blank" href="'.yii::app()->createUrl('/company/program/',array('id'=>$model->id)).'" rel="tooltip" data-placement="right" title="Download program"><i class="icon-book icon-red"></i></a>';
							}
						?>
					</div>
					<?php
						$Companycontactinfo=Companycontactinfo::model()->findAll("contactInfo>'' AND companyID=".$model->id);
						if(count($Companycontactinfo)>0)
						{
							echo '<div class="social">';
							foreach($Companycontactinfo as $x)
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
							$ComapanyAddress = array_values($model->companyaddresses)[0];
							echo '<li><span>'.$ComapanyAddress->address->addr1.'</span></li>';
							echo '<li><span>'.$ComapanyAddress->address->city.', '.$ComapanyAddress->address->state.', '.$ComapanyAddress->address->zip.'</span></li>';
							echo '<li><span>'.$ComapanyAddress->address->country->countryName.'</span></li>';
						?>
					</ul>
				</div>
			</div>
			<?php
			echo '<p class="clear">'.$model->descr.'</p>';
			?>
		</div>
		
		<?php
			$profile_images=Profileimage::model()->findAll('profileType=5 AND imageType=2 AND profileID='.$model->id);
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
		<div class="mini-layout recordList customCollapse" data-maxHeight="800">
			<h4>Creative, Crew and Staff</h4>
			<hr class="red_line"/>
			<?php
			//$productioncrews = Productioncrew::model()->with('role')->findAll('profileID='.$model->id);
			$productioncrews = $model->productioncompanycrews;
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
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-0' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-0'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(".companyInfo").on("click","#btnWatchlistAdd",function(){
	$.ajax({
		type: "POST",
		url: cfg.companyBaseUrl+'/watchlistAdd',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&companyID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistAdd").replaceWith("<a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>")
		}
	});
});
$(".companyInfo").on("click","#btnWatchlistRemove",function(){
	$.ajax({
		type: "POST",
		url: cfg.companyBaseUrl+'/watchlistRemove',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&companyID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistRemove").replaceWith("<a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>")
		}
	});
});
$(".companyInfo").on("click","#btnOwnershipClaim",function(){
	bootbox.confirm("Are you sure you wish to claim this profile? By claiming this profile you acknowledge you are the owner or representative of the content on this profile.", function(result) {
		if(result)
		{	
			$.ajax({
				type: "POST",
				url: cfg.companyBaseUrl+'/ownershipClaim',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&companyID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});
$(".companyInfo").on("click","#btnOwnershipRelinquish",function(){
	bootbox.confirm("Are you sure you want to release this profile? By clicking “OK” anyone registered with Theatre Profile will be able to edit the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.companyBaseUrl+'/ownershipRelinquish',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&companyID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});

var bPageNotOpenedByJavascript = window.opener ? false : true;
if (bPageNotOpenedByJavascript) {
    $(".downloadLink").each (interceptLink);
}
else {
    /***** Page opened by JS in either a popup or new tab.
        This was *most likely* done by us, using window.open.
    */
    $(window).bind ("beforeunload",  function (zEvent) {
        //-- Allow time for the file dialog to actually open.
        setTimeout ( function () {
                /*-- Since the time it takes for the user to respond
                    to the File dialog can vary radically, use a confirm
                    to keep the File dialog open long enough for the user
                    to act.
                */
                var doClose = confirm ("Close this window?");
                if (doClose) {
                    window.close ();
                }
            },
            444 // 0.444 seconds
        );
    } );
}

function interceptLink (index, node) {
    var jNode   = $(node);
    jNode.click (openInNewTab);
    jNode.addClass ("intercepted");
}

function openInNewTab (zEvent) {
    //-- Optionally adjust the href here, if needed.
    var targURL     = this.href;
    var newTab      = window.open (targURL, "_blank");

    //--- Stop the link from doing anything else.
    zEvent.preventDefault ();
    zEvent.stopPropagation ();
    return false;
}
JS
, CClientScript::POS_READY);
?>