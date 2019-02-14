<?php
$pageTitle=$model->venueName." - Venue - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('Venues'=>array('/venue'),$model->venueName);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".recordList{overflow: hidden;position: relative;}
.btnToggleCollapse {position:absolute;bottom:3px;right:3px;}
#btnWatchlistRemove, #btnWatchlistAdd, #btnOwnershipClaim, #btnOwnershipClaimed, #btnOwnershipRelinquish {margin-left:5px;}
.pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:250px;height:150px;}
#links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
#links img {max-height:60px;margin:0 2px;}
#worksheet {width:100%;}");
$updateAccess=Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$model->venueownerships));
$Venueownership = Venueownership::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>$model->id,':userID'=>Yii::app()->user->id));
$reportAccess=false;
if($updateAccess)
{	
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
	foreach($roles as $role) if($role->name == 'ReportUser') $reportAccess=true;
}
?>
<div class="row">
	<div class="span10">
		<div class="venueInfo">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND imageType=1 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);	
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
				}
				else
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
						<h1 class="inline"><?php echo $model->venueName; ?></h1>
						<?php
							if($updateAccess)
							{
								echo "<small><a href='".yii::app()->createUrl('/venue/update',array('id'=>$model->id))."'>Edit Profile</a></small>";
							}
							if(!Yii::app()->user->isGuest)
							{
								$Venuewatchlist = Venuewatchlist::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>$model->id,':userID'=>Yii::app()->user->id));
								if(!empty($Venuewatchlist))
									echo " <a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>";
								else
									echo " <a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>";
								if(!empty($model->venueownerships))
								{
									if(!empty($Venueownership))
									{
										echo " <a id='btnOwnershipRelinquish' class='btn' rel='tooltip' data-placement='right' title='This profile is currently owned by you and cannot be edited by other users. Click to relinquish ownership.'><i class='icon-ok-circle icon-red'></i></a>";
										
										if($reportAccess||Rights::getAuthorizer()->isSuperUser(Yii::app()->user->Id))
										{
											echo " <a class='btn' rel='tooltip' data-placement='right' title='Upload ticket sale information' data-toggle='modal' data-target='#myModal'><i class='icon-upload icon-red'></i></a>";
										}
									}
									else
										echo " <a id='btnOwnershipClaimed' class='btn disabled' rel='tooltip' data-placement='right' title='This profile is claimed by another user. If you think the person who has claimed this profile is not the right person to edit the information please let us know.'><i class='icon-ban-circle icon-red'></i></a>";
								}
								else
									echo " <a id='btnOwnershipClaim' class='btn' rel='tooltip' data-placement='right' title='Claim ownership of this profile.'><i class='icon-tag icon-red'></i></a>";
								if($updateAccess)
									echo " <a class='btn' rel='tooltip' data-placement='right' title='View profile statistics.' href='".yii::app()->createUrl('/venue/analytics',array('id'=>$model->id))."'><i class='icon-info-sign icon-red'></i></a>";
							}
							else
							{
								echo " <a class='btn' rel='tooltip' data-placement='right' title='You need to be logged in to add this venue to your watchlist. Membership is free and you will get many other benefits.'><i class='icon-eye-open icon-red'></i></a>";
							}
						?>
					</div>
					<?php
						$Venuecontactinfo=Venuecontactinfo::model()->findAll("contactInfo>'' AND venueID=".$model->id);
						if(count($Venuecontactinfo)>0)
						{
							echo '<div class="social">';
							foreach($Venuecontactinfo as $x)
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
							echo '<li><span>'.$model->address->addr1.'</span></li>';
							echo '<li><span>'.$model->address->city.', '.$model->address->state.', '.$model->address->zip.'</span></li>';
							echo '<li><span>'.$model->address->country->countryName.'</span></li>';
						?>
					</ul>
				</div>
			</div>
			<?php
			echo '<p class="clear">'.$model->descr.'</p>';
			?>
		</div>
		
		<?php
			$profile_images=Profileimage::model()->findAll('profileType=4 AND imageType=2 AND profileID='.$model->id);
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
		
		<?php
		if($reportAccess||Rights::getAuthorizer()->isSuperUser(Yii::app()->user->Id))
		{
			$this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal'));
		?>>
		<div class='modal-header'><a class='close' data-dismiss='modal'>&times;</a><h4>Upload Ticket Sale Information</h4></div>;
		<div class='modal-body'>
			<?php
				$form=$this->beginWidget('CActiveForm', array(
					'id'=>'ticketSaleInfo-form',
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// There is a call to performAjaxValidation() commented in generated controller code.
					// See class documentation of CActiveForm for details on this.
					'enableAjaxValidation'=>false,
					'action' => Yii::app()->createUrl('/venue/uploadTicketSaleInfo',array('id'=>$model->id)),  //<- your form action here
					'htmlOptions' => array(
						'enctype' => 'multipart/form-data',
					),
				));
			?>
			<div class='well clear'><strong>Select a file to upload:</strong><br /><br />
			<input type='file' name='worksheet' id='worksheet' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />			
			<br />
			</div>
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label'=>'Cancel',
				'url'=>'#',
				'htmlOptions'=>array('data-dismiss'=>'modal','class'=>'pull-right'),
			));
			echo CHtml::submitButton('Upload',array('class' => 'btn pull-right','id'=>'btnUploadTicketSaleInfo','disabled'=>'true'));
			?>
		</div>
		<div class='modal-footer'>
		</div>
		<?php
			$this->endWidget();
			$this->endWidget();
		}
		?>

		<div class="clear"></div>
		<div class="row-fluid">
			<div class="mini-layout recordList customCollapse" data-maxHeight="800">
				<?php
				echo '<h4>Productions at this venue <a href="'.yii::app()->createUrl('/venue/schedule',array('id'=>$model->id)).'" class="btn btn-mini btn-danger">Event Schedule</a></h4>';
				?>
				<hr class="red_line"/>
				<?php
				if(count($model->productionvenues)==0)
				{
				?>
				<div class="media">No productions found</div>
				<?php
				}
				else
				{
				?>
				<?php
				foreach($model->productionvenues as $productionvenue)
				{
				?>
				<div class="media">
					<a href="<?php echo $productionvenue->production->createUrl(); ?>" class="pull-left">
						<?php
						$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$productionvenue->productionID);
						if(isset($profile_image->image->imageURL))
						{
							$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w42h66.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						}
						else
						{
							$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$productionvenue->production->showID);
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
								$startDate = $productionvenue->startDate;
								$endDate = $productionvenue->endDate;
								//if(empty($startDate)&&empty($endDate))
								//{
								//	$startDate = $productionvenue->production->startDate;
								//	$endDate = $productionvenue->production->endDate;
								//}
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
								?>
								<a href="<?php echo $productionvenue->production->createUrl(); ?>">
								<?php
								echo $productionvenue->production->show->showName.(!empty($productionvenue->production->productionName)?' - '.$productionvenue->production->productionName:'');
								?>
								</a>
								<?php
								if(!empty($startDate) or !empty($endDate))
								{
									if($startDate==$endDate)
									{
										echo '<br />('.$startDate.')';
									}
									else
									{
										echo '<br />('.$startDate.' - '.$endDate.')';
									}
								}
								$links = Link::model()->findAll('profileType=4 AND profileID='.$productionvenue->id.' and linkType=1');	
								echo '<br />';
								foreach($links as $link)
								{
									echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini  btn-danger">'.$link->label.'</a>';
								}
								if(empty($model->venueownerships))
									echo " <a href='".yii::app()->createUrl('/productionevent/create',array('id'=>$model->id,'pvid'=>$productionvenue->id,))."' class='btn btn-mini btn-primary'>Add Event Details</a>";
								else
								{
									if(!empty($Venueownership))
										echo " <a href='".yii::app()->createUrl('/productionevent/create',array('id'=>$model->id,'pvid'=>$productionvenue->id,))."' class='btn btn-mini btn-primary'>Add Event Details</a>";
								}
								?>
						</div>
					</div>
				</div>
				<?php }
					}
				?>
			</div>
		</div>
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-8' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-8'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(".venueInfo").on("click","#btnWatchlistAdd",function(){
	$.ajax({
		type: "POST",
		url: cfg.venueBaseUrl+'/watchlistAdd',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&venueID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistAdd").replaceWith("<a id='btnWatchlistRemove' class='btn' rel='tooltip' data-placement='right' title='Remove from Watchlist'><i class='icon-eye-close icon-red'></i></a>")
		}
	});
});
$(".venueInfo").on("click","#btnWatchlistRemove",function(){
	$.ajax({
		type: "POST",
		url: cfg.venueBaseUrl+'/watchlistRemove',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&venueID='+cfg.modelID,
		success: function(msg){
			$('.tooltip').remove();
			$("#btnWatchlistRemove").replaceWith("<a id='btnWatchlistAdd' class='btn' rel='tooltip' data-placement='right' title='Add to Watchlist'><i class='icon-eye-open icon-red'></i></a>")
		}
	});
});
$(".venueInfo").on("click","#btnOwnershipClaim",function(){
	bootbox.confirm("Are you sure you wish to claim this profile? By claiming this profile you acknowledge you are the owner or representative of the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.venueBaseUrl+'/ownershipClaim',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&venueID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});
$(".venueInfo").on("click","#btnOwnershipRelinquish",function(){
	bootbox.confirm("Are you sure you want to release this profile? By clicking “OK” anyone registered with Theatre Profile will be able to edit the content on this profile.", function(result) {
		if(result)
		{
			$.ajax({
				type: "POST",
				url: cfg.venueBaseUrl+'/ownershipRelinquish',
				data: 'YII_CSRF_TOKEN='+cfg.csrfToken+'&venueID='+cfg.modelID,
				success: function(msg){
					location.reload();
				}
			});
		}
	});
});

function handleFileSelect(evt) {
	var files;
	if (typeof evt.target.files != 'undefined') {
		files = evt.target.files;
	}
	if (files.length != 0) {
		$('#btnUploadTicketSaleInfo').prop('disabled', false);
		fileInput.files = files;
	}
	else
	{
		$('#btnUploadTicketSaleInfo').prop('disabled', true);	
	}
}
var fileInput = document.getElementById('worksheet');
fileInput.addEventListener('change', handleFileSelect, false);

JS
, CClientScript::POS_READY);
?>