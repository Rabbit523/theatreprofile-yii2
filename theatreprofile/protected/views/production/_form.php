<?php
/* @var $this ProductionController */
/* @var $model Production */
/* @var $form CActiveForm */
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery',CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui',CClientScript::POS_HEAD);
$cs->registerCssFile($cs->getCoreScriptUrl(). '/jui/css/base/jquery-ui.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.Jcrop.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.Jcrop.min.js',CClientScript::POS_END);
$root=Yii::getPathOfAlias('webroot');
$js=Yii::app()->assetManager->publish($root.'/protected/views/production/_form.js');
Yii::app()->clientScript->registerScriptFile($js,CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.isNewRecord=".($model->isNewRecord?1:0).";cfg.cast_counter=0;cfg.crew_counter=0;cfg.venue_counter=0;cfg.link_counter=0;", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle","
  .well {padding: 5px;margin-top:2px;margin-bottom:2px;}
  .row.buttons {margin-top:25px !important;}
  .record_list{padding:10px 0px;}
  .combine{margin-top:-5px;border-top:none;}
  #drop_zone
  {
	border: 2px dashed #bbb;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	height:100px;
	text-align: center;
	background-image: url(<?php echo yii::app()->request->baseUrl.'/images/camera.png'; ?>);
	background-position: center center;
	background-repeat: no-repeat;
	cursor:pointer;
	margin-bottom:35px;
	margin-bottom:50px;
	opacity:0.5;
  }
  #drop_zone1
  {
	border: 2px dashed #bbb;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	height:100px;
	text-align: center;
	background-image: url(<?php echo yii::app()->request->baseUrl.'/images/camera.png'; ?>);
	background-position: center center;
	background-repeat: no-repeat;
	cursor:pointer;
	margin-bottom:35px;
	margin-bottom:50px;
	opacity:0.5;
  }
  #drop_zone:hover{opacity:1;}
  #drop_zone1:hover{opacity:1;}
  #preview{text-align:center;}
  #preview1{text-align:center;}
  .modal-body {max-height: 900px;}
  .jcrop-holder {margin:0 auto;}
  .pnl-profile-pic {cursor:pointer;margin:0 auto;padding:1px;border: 1px solid #ddd;}
  .pnl-profile-pic > .media-object{width:140px;height:220px;}
  #preview1 img {max-height:75px;}
  #links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
  #links img {max-height:60px;margin:0 2px;}");
?>

<style  type="text/css">
  
</style>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'production-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
		'htmlOptions' => array(
			'enctype' => 'multipart/form-data',
		),
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php if(Yii::app()->user->hasFlash('notify')):?>
		<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'notifyModal','autoOpen'=>'true')); ?>
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4>Hey there!</h4>
		</div>
		<div class="modal-body">
			<p>
				We noticed you created the following new venues, thanks for the info! It would be great to know more. Click the Edit button to add more info, a new window will open for you.<br /><br />
				<?php echo Yii::app()->user->getFlash('notify'); ?>
			</p>
		</div>
		<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'type'=>'primary',
				'label'=>'Close',
				'url'=>'#',
				'htmlOptions'=>array('data-dismiss'=>'modal'),
			)); ?>
		</div>
		<?php $this->endWidget(); ?>
	<?php endif; ?>
	
	<?php
		echo $form->errorSummary($model);
	?>

	<div class="show">
		<div class="media">
			<?php
			if(!$model->isNewRecord)
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);	
				}
				else
				{
					$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
				}
			}
			else
			{
				$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
			}
			?>
			
			<div class="pull-left text-center">
				<div class="pnl-profile-pic" data-toggle="modal" data-target="#myModal" rel="tooltip" data-title="Click to update profile picture" data-placement="bottom">
					<img id="imgProfilePic" class="media-object" data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="140px" height="220px" />
				</div>
				<br />
				<button type="button" data-toggle="modal" data-target="#myModal">Change Profile Picture</button>
			</div>
			
			<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal')); ?>
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4>Select profile picture</h4>
			</div>
			 
			<div class="modal-body">
				<div class="img-upload alert alert-error hide"></div>
				<p>	
					<input type="file" name="image" id="image" class="hide" />
					<div class="well"><strong>Click or drag & drop image file below (Recommended dimensions: 280X440 pixels).</strong></div>
					<div id="drop_zone" class="mini-layout combine" rel="tooltip" data-title="Click or drag & drop image file here" data-placement="bottom"></div>
					<div id="pnlPreview">
						<div class="well clearfix">
							<strong>Preview and confirm.</strong>
							<?php $this->widget('bootstrap.widgets.TbButton', array(
								'label'=>'Cancel',
								'url'=>'#',
								'htmlOptions'=>array('data-dismiss'=>'modal','id'=>'btnCancelImgUpd','class'=>'pull-right'),
							)); ?>						
							<button class="btn pull-right" disabled="true" data-dismiss="modal" id="btnImgUpd">Update</button>
						</div>
						<div id="preview" class="mini-layout combine"></div>
					</div>
					
					<input id="crop_x" name="crop_x" type="hidden" value="0" /><input id="crop_y" name="crop_y" type="hidden" value="0" />
					<input id="width" name="width" type="hidden" value="0"  /><input id="height" name="height" type="hidden" value="0" /> 
				</p>
			</div>
			 
			<div class="modal-footer">
			</div>
			<?php $this->endWidget(); ?>
			
			<?php
				if(!$model->isNewRecord)
				{
					$this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal1'));
			?>
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4>Add Picture to Gallery</h4>
			</div>
			 
			<div class="modal-body">
				<div class="img-upload1 alert alert-error hide"></div>
				<p>	
					<input type="file" name="images[]" id="images" class="hide" accept="image/*" multiple />
					<div class="well"><strong>Click to select or drag & drop image files below.</strong></div>
					<div id="drop_zone1" class="mini-layout combine" rel="tooltip" data-title="Click to select or drag & drop image files here" data-placement="bottom"></div>
					<div id="pnlPreview1">
						<div class="well clearfix">
							<strong>Preview and confirm.</strong>
							<?php $this->widget('bootstrap.widgets.TbButton', array(
								'label'=>'Cancel',
								'url'=>'#',
								'htmlOptions'=>array('data-dismiss'=>'modal','id'=>'btnCancelImgUpd1','class'=>'pull-right'),
							)); ?>						
							<button class="btn pull-right" disabled="true" data-dismiss="modal" id="btnImgUpd1">Update</button>
						</div>
						<div id="preview1" class="mini-layout combine clearfix">
						</div>
					</div>
				</p>
			</div>
			 
			<div class="modal-footer">
			</div>
			 
			<?php
					$this->endWidget();
				}
			?>
			
			<div class="media-body">
				<div class="row">
					<div class="span4">
						<div class="hide">
							<?php echo $form->labelEx($model,'showID'); ?>
							<?php echo $form->textField($model,'showID',array('class'=>'span4')); ?>
							<?php echo $form->error($model,'showID'); ?>
						</div>
						
						<div class="row">
							<?php						
							if(!$model->isNewRecord)
							{
								echo $form->labelEx($model->show,'showName');
							}
							else
							{
								echo $form->labelEx($model->show,'showName');
							}
							?>
							<?php 
							if(!$model->isNewRecord)
								echo $form->textField($model->show,'showName',array('class'=>'span4','maxlength'=>100, 'readonly'=>'readonly'));
							else
								echo $form->textField($model->show,'showName',array('class'=>'span4','maxlength'=>100, 'rel'=>'tooltip','data-title'=>'Input a minimum of 3 characters to receive suggestions', 'data-placement'=>'right'));
							?>
							<?php echo $form->error($model->show,'showName'); ?>
						</div>
						
						<div class="row">
							<?php echo $form->labelEx($model,'categoryID'); ?>
							<?php echo $form->dropDownList($model,'categoryID',
							 CHtml::listData(Productioncategory::model()->findAll(), 'id', 'categoryName'), array('empty'=>'Select category','class'=>'span4')); ?>
							<?php echo $form->error($model,'categoryID'); ?>
						</div>
						
						<div class="row">
							<?php echo $form->labelEx($model,'productionName'); ?>
							<?php echo $form->textField($model,'productionName',array('class'=>'span4','maxlength'=>100,"rel"=>"tooltip",'data-title'=>'optional; recommended for productions with multiple venues','data-placement'=>'right')); ?>
							<?php echo $form->error($model,'productionName'); ?>
						</div>
					</div>
					<div class="span4">
						<div class="row">
							<?php echo $form->labelEx($model,'First Preview'); ?>
							<?php echo $form->textField($model,'firstPreviewDate',array('class'=>'span4',"rel"=>"tooltip","data-title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
							<?php echo $form->error($model,'firstPreviewDate'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model,'Opening'); ?>
							<?php echo $form->textField($model,'startDate',array('class'=>'span4',"rel"=>"tooltip","data-title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
							<?php echo $form->error($model,'startDate'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model,'Closing'); ?>
							<?php echo $form->textField($model,'endDate',array('class'=>'span4',"rel"=>"tooltip","data-title"=>"MM-DD-YYYY","data-placement"=>"right"));?>
							<?php echo $form->error($model,'endDate'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span2">
							<?php echo $form->labelEx($model,'duration'); ?>
							<?php echo $form->textField($model,'duration',array('class'=>'span2',"rel"=>"tooltip","data-title"=>"Duration in minutes","data-placement"=>"right"));?>
							<?php echo $form->error($model,'duration'); ?>
					</div>
					
					<div class="span2">
							<?php echo $form->labelEx($model,'intermissions'); ?>
							<?php echo $form->textField($model,'intermissions',array('class'=>'span2',));?>
							<?php echo $form->error($model,'intermissions'); ?>						
					</div>
					
					<div class="span2">
							<?php
							if(count($model->productionownerships))
							{
								echo $form->labelEx($model,'privateRatings',array());
								echo $form->checkBox($model,'privateRatings',array("rel"=>"tooltip","data-title"=>"Check to have production, cast and crew ratings be only visible to you","data-placement"=>"bottom"));
								echo $form->error($model,'privateRatings');
							}
							else
							{
								echo '<div rel="tooltip" data-title="Ratings can only be made private if you own the profile." data-placement="bottom">';
								echo $form->labelEx($model,'privateRatings',array());
								echo $form->checkBox($model,'privateRatings',array("disabled"=>"disabled"));
								echo '</div>';
							}
							?>
					</div>
				</div>
			</div>
			<div class="row">
				<label>Social Links</label>
				<?php
				if(!$model->isNewRecord)
				{
					$Productioncontactinfo1=Productioncontactinfo::model()->find('contactTypeID=1 AND productionID='.$model->id);
					if($Productioncontactinfo1==null)
					{
						echo '<input class="span11" name="contactInfo_facebook" type="text" value="" placeholder="Facebook" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_facebook" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_facebook" type="text" value="'.$Productioncontactinfo1->contactInfo.'" placeholder="Facebook" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_facebook" type="text" value="'.$Productioncontactinfo1->id.'">';
					}
					$Productioncontactinfo2=Productioncontactinfo::model()->find('contactTypeID=2 AND productionID='.$model->id);
					if($Productioncontactinfo2==null)
					{
						echo '<input class="span11" name="contactInfo_googleplus" type="text" value="" placeholder="Google Plus" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_googleplus" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_googleplus" type="text" value="'.$Productioncontactinfo2->contactInfo.'" placeholder="Google Plus" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_googleplus" type="text" value="'.$Productioncontactinfo2->id.'">';
					}
					$Productioncontactinfo3=Productioncontactinfo::model()->find('contactTypeID=3 AND productionID='.$model->id);
					if($Productioncontactinfo3==null)
					{
						echo '<input class="span11" name="contactInfo_twitter" type="text" value="" placeholder="Twitter" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_twitter" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_twitter" type="text" value="'.$Productioncontactinfo3->contactInfo.'" placeholder="Twitter" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_twitter" type="text" value="'.$Productioncontactinfo3->id.'">';
					}
					$Productioncontactinfo4=Productioncontactinfo::model()->find('contactTypeID=4 AND productionID='.$model->id);
					if($Productioncontactinfo4==null)
					{
						echo '<input class="span11" name="contactInfo_instagram" type="text" value="" placeholder="Instagram" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_instagram" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_instagram" type="text" value="'.$Productioncontactinfo4->contactInfo.'" placeholder="Instagram" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_instagram" type="text" value="'.$Productioncontactinfo4->id.'">';
					}
					$Productioncontactinfo5=Productioncontactinfo::model()->find('contactTypeID=5 AND productionID='.$model->id);
					if($Productioncontactinfo5==null)
					{
						echo '<input class="span11" name="contactInfo_website" type="text" value="" placeholder="Website" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_website" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_website" type="text" value="'.$Productioncontactinfo5->contactInfo.'" placeholder="Website" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_website" type="text" value="'.$Productioncontactinfo5->id.'">';
					}
				}
				else
				{
					echo '<input class="span11" name="contactInfo_facebook" type="text" value="" placeholder="Facebook" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
					echo '<input type="hidden" name="contactInfoID_facebook" type="text" value="0">';
					echo '<input class="span11" name="contactInfo_googleplus" type="text" value="" placeholder="Google Plus" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
					echo '<input type="hidden" name="contactInfoID_googleplus" type="text" value="0">';
					echo '<input class="span11" name="contactInfo_twitter" type="text" value="" placeholder="Twitter" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
					echo '<input type="hidden" name="contactInfoID_twitter" type="text" value="0">';
					echo '<input class="span11" name="contactInfo_instagram" type="text" value="" placeholder="Instagram" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
					echo '<input type="hidden" name="contactInfoID_instagram" type="text" value="0">';
					echo '<input class="span11" name="contactInfo_website" type="text" value="" placeholder="Website" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
					echo '<input type="hidden" name="contactInfoID_website" type="text" value="0">';
				}
			?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'About'); ?>
				<?php echo $form->textArea($model,'descr',array('class'=>'span11','maxlength'=>3000,"rel"=>"tooltip",'data-title'=>'optional; leave blank to display show description','data-placement'=>'right')); ?>
				<?php echo $form->error($model,'descr'); ?>
			</div>
		</div>
	</div>
	
	
	
	<?php
		if(!$model->isNewRecord)
		{
			$profile_images=Profileimage::model()->findAll('profileType=2 AND imageType=2 AND profileID='.$model->id);			
			if(count($profile_images))
			{
				echo '<div class="row-fluid"><div class="well span11">';
				echo '<a class="btn btn-large" data-toggle="modal" data-target="#myModal1" style="padding:20px 25px" rel="tooltip" data-title="Add picture to gallery">';
				echo '<i class="icon-plus"></i>';
				echo '</a>';
				echo '<div id="links" class="inline-block">';
				foreach ($profile_images as $profile_image)
				{
					if(isset($profile_image->image->imageURL))
					{
						//$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
						$image_url=Yii::app()->params["mediaServeUrl"].'/images/uploads/'.$profile_image->image->imageURL;
						echo '<a href="'.$image_url.'" data-id="'.$profile_image->id.'" data-gallery>';
						echo '<img  src="'.$image_url.'" alt="" title="Title not available.">';
						echo '</a>';
					}
				}
				echo '</div></div></div>';
			}
			else
			{
					echo '<div class="row-fluid"><div class="well span11">';
					echo '<a class="btn btn-large" data-toggle="modal" data-target="#myModal1" style="padding:20px 25px"  rel="tooltip" data-title="Add picture to gallery">';
					echo '<i class="icon-plus"></i>';
					echo '</a> Add picture to gallery';
					echo '</div></div>';
			}
			echo '<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"><!-- The container for the modal slides --><div class="slides"></div><h3 class="title"></h3>';
			echo CHtml::ajaxLink ('<i class="icon-trash"></i> Delete',array('production/deleteProfileImage'),array('data'=>array('productionID'=>$model->id,'id'=>'js:$("#links>a")[$("#blueimp-gallery").data("gallery").getIndex()].getAttribute("data-id")','YII_CSRF_TOKEN'=>'js:cfg.csrfToken'),'type'=>'POST','success' => 'function() {location.reload();}'),array('class'=>'delete btn'));
			echo '<a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a><ol class="indicator"></ol></div>';
		}
	?>
			
	<div class="row-fluid">
		<div class="span11 mini-layout">
			<p><strong>Venues: <a id="venue_adder" href="javascript:void(0);" onclick="venue_adder()">ADD</a></strong></p>
			<div id="venue_container" class="record_list">
				<?php
					if(!$model->isNewRecord)
					{
						$data = $model->productionvenues(array('order'=>'venue.venueName'));
						$venueCounter=0;
						if(count($data)>0)
						{
							foreach($data as $data_obj)
							{
								echo '<div>';
								
								echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteVenueConfirm" href="'.yii::app()->createUrl('production/removevenue',array('id'=>$data_obj->id,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a><strong>'.$data_obj->venue->venueName.', '.$data_obj->venue->address->city.', '.$data_obj->venue->address->country->countryCode.' | Start Date: '.$data_obj->startDate.' End Date: '.$data_obj->endDate.'</strong></div>';
								
								echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteVenueConfirm" href="'.yii::app()->createUrl('production/removevenue',array('id'=>$data_obj->id,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><label>Enter Venue Name <span class="required">*</span></label><input type="text" name="venue[existing]['.$venueCounter.'][venueName]" required="required" data-autocomplete="venue" value="'.$data_obj->venue->venueName.'" rel="tooltip" data-placement="right" data-title="Enter venue name only; Input a minimum of 3 characters to receive suggestions" /><label>Start date</label><input type="text" data-exttype="date" name="venue[existing]['.$venueCounter.'][startDate]" value="'.$data_obj->startDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right" /><span class="help-block">Leave empty to use production end date.</span><label>End date</label><input type="text" data-exttype="date" name="venue[existing]['.$venueCounter.'][endDate]" value="'.$data_obj->endDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right" /><span class="help-block">Leave empty to use production start date.</span><input id="venueID_'.$data_obj->id.'" type="hidden" name="venue[existing]['.$venueCounter.'][venueID]" value="'.$data_obj->venueID.'" /><input type="hidden" name="venue[existing]['.$venueCounter.'][id]" value="'.$data_obj->id.'" /></div>';
								
								echo '<div class="mini-layout combine" id="ticketinglinks_'.$data_obj->id.'">';
								$ticketingLinks = Link::model()->findAll('profileType=5 AND profileID='.$data_obj->id.' and linkType=1');						
								if(count($ticketingLinks)>0)
								{
									$linkCounter=0;
									echo '<a class="btn btn-mini btn-danger" onclick="link_adder('.$data_obj->id.');">Add ticketing link</a>';
									foreach($ticketingLinks as $ticketingLink)
									{
										echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Production/removelink',array('id'=>$ticketingLink->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a>'.$ticketingLink->label.': '.$ticketingLink->href.'</div>';
										echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Production/removelink',array('id'=>$ticketingLink->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><label>Label <span class="required">*</span></label><input type="text" required="required" value="'.$ticketingLink->label.'" class="input-block-level" name="links[existing]['.$linkCounter.'][label]" /><label>URL <span class="required">*</span></label><input type="text" class="input-block-level" value="'.$ticketingLink->href.'" name="links[existing]['.$linkCounter.'][href]" rel="tooltip" data-placement="right" data-title="Input full URL including http:// or https://" required="required" /><input type="hidden" name="links[existing]['.$linkCounter.'][id]" value="'.$ticketingLink->id.'"/></div>';
										$linkCounter++;
									}
								}
								else
								{
									echo '<a class="btn btn-mini btn-danger" onclick="link_adder('.$data_obj->id.');">Add ticketing link</a>';
								}
								echo '</div></div>';
								$venueCounter++;
							}
						}
					}
				?>
			</div>
		</div>
	</div>
	
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary btn-large')); ?>

	<div class="row-fluid">
		<div class="span11 mini-layout">
			<p><strong>Cast : <a id="cast_adder" href="javascript:void(0);" onclick="cast_adder()">ADD</a></strong></p>
			<div id="cast_container" class="record_list">
				<?php
				if(!$model->isNewRecord)
				{
					$data = Productioncast::model()->with('individual')->findAll(array('order'=>'individual.firstName','condition'=>'productionID='.$model->id));
					if(count($data)>0)
					{	
						$castCounter=0;
						foreach($data as $data_obj)
						{
							//print_r($data_obj->individual);
							//die();
							echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteCastConfirm" href="'.yii::app()->createUrl('production/removecast',array('id'=>$data_obj->id,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a><strong>'.$data_obj->individual->firstName.' '.$data_obj->individual->middleName.' '.$data_obj->individual->lastName.' - '.$data_obj->roleName.' | Start Date: '.$data_obj->startDate.' End Date: '.$data_obj->endDate.'</strong></div>';
							
							echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteCastConfirm" href="'.yii::app()->createUrl('production/removecast',array('id'=>$data_obj->id,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><strong><label>Enter Cast Name <span class="required">*</span></label><input type="text" data-autocomplete="individual" name="cast[existing]['.$castCounter.'][castName]" required="required" autocomplete="off" rel="tooltip" data-title="Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions" data-placement="right" value="'.$data_obj->individual->firstName.' '.$data_obj->individual->middleName.' '.$data_obj->individual->lastName.'"><label>Role Name</label><input type="text" name="cast[existing]['.$castCounter.'][roleName]" value="'.$data_obj->roleName.'"  maxlength="100"><label>Start date</label><input type="text" data-exttype="date" name="cast[existing]['.$castCounter.'][startDate]" value="'.$data_obj->startDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><label>End Date</label><input type="text" data-extType="date" name="cast[existing]['.$castCounter.'][endDate]" value="'.$data_obj->endDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><input type="hidden" id="individualID_'.$data_obj->id.'" name="cast[existing]['.$castCounter.'][individualID]" value="'.$data_obj->individualID.'" ><input type="hidden"  name="cast[existing]['.$castCounter.'][id]" value="'.$data_obj->id.'" ></strong></div>';
							
							$castCounter++;
						}
					}
				}
				?>
			</div>
		</div>
	</div>
	
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary btn-large')); ?>

	<div class="row-fluid">
		<div class="span11 mini-layout">
			<p><strong>Creative, Crew and Staff: <a id="crew_adder" href="javascript:void(0);" onclick="crew_adder()">ADD</a></strong></p>
			<div id="crew_container" class="record_list">
				<?php
					$crewCounter=0;
					if(!$model->isNewRecord)
					{
						$data = Productioncrew::model()->with('individual')->findAll(array('order'=>'individual.firstName','condition'=>'productionID='.$model->id));
						if(count($data)>0)
						{	
							foreach($data as $data_obj)
							{
								$individual=Individual::model()->find('id='.$data_obj->profileID);
								echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteCrewConfirm" href="'.yii::app()->createUrl('production/removecrew',array('id'=>$data_obj->id,'type'=>1,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a><strong>'.$individual->firstName.' '.$individual->middleName.' '.$individual->lastName.' '.$individual->suffix.' - '.$data_obj->role->roleName.' | Start Date: '.$data_obj->startDate.' End Date: '.$data_obj->endDate.'</strong></div>';
								
								echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteCrewConfirm" href="'.yii::app()->createUrl('production/removecrew',array('id'=>$data_obj->id,'type'=>1,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><strong>
								<label class="radio inline"><input type="radio" class="crewType" name="crew[existing]['.$crewCounter.'][crewType]" id="crewType1_'.$data_obj->id.'" value="1" checked>Individual</label><label class="radio inline"><input type="radio" class="crewType" name="crew[existing]['.$crewCounter.'][crewType]" id="crewType2_'.$data_obj->id.'" value="2">Company</label>
								<label>Enter Crew Name <span class="required">*</span></label><input type="text" data-autocomplete="individual" name="crew[existing]['.$crewCounter.'][crewName]" required="required" rel="tooltip" data-title="Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions" data-placement="right" value="'.$individual->firstName.' '.$individual->middleName.' '.$individual->lastName.' '.$individual->suffix.'"><label>Role <span class="required">*</span></label><input type="text" data-autocomplete="role" name="crew[existing]['.$crewCounter.'][roleName]" value="'.$data_obj->role->roleName.'" maxlength="100" required="required" rel="tooltip" data-placement="right" data-title="Input a minimum of 3 characters to receive suggestions" /><label>Start date</label><input type="text" data-exttype="date" name="crew[existing]['.$crewCounter.'][startDate]" value="'.$data_obj->startDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><label>End Date</label><input type="text" data-extType="date" name="crew[existing]['.$crewCounter.'][endDate]" value="'.$data_obj->endDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><input type="hidden" id="individualID_'.$data_obj->id.'" name="crew[existing]['.$crewCounter.'][profileID]" value="'.$data_obj->profileID.'" ><input type="hidden" id="roleID_'.$data_obj->id.'" name="crew[existing]['.$crewCounter.'][roleID]" value="'.$data_obj->roleID.'" ><input type="hidden"  name="crew[existing]['.$crewCounter.'][id]" value="'.$data_obj->id.'" ><input type="hidden" name="crew[existing]['.$crewCounter.'][crewTypePrev]" value="1" /><input type="hidden"  name="crew[existing]['.$crewCounter.'][id]" value="'.$data_obj->id.'" ></strong></div>';
								
								$crewCounter++;
							}
						}
						$data = Productioncompanycrew::model()->with('company')->findAll(array('order'=>'company.companyName','condition'=>'productionID='.$model->id));
						if(count($data)>0)
						{	
							foreach($data as $data_obj)
							{
								$Company=Company::model()->find('id='.$data_obj->companyID);
								echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteCrewConfirm" href="'.yii::app()->createUrl('production/removecrew',array('id'=>$data_obj->id,'type'=>2,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a><strong>'.$Company->companyName.' - '.$data_obj->role->roleName.' | Start Date: '.$data_obj->startDate.' End Date: '.$data_obj->endDate.'</strong></div>';
								
								echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteCrewConfirm" href="'.yii::app()->createUrl('production/removecrew',array('id'=>$data_obj->id,'type'=>2,'productionID'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><strong>
								<label class="radio inline"><input type="radio" class="crewType" name="crew[existing]['.$crewCounter.'][crewType]" id="crewType1_'.$data_obj->id.'" value="1">Individual</label><label class="radio inline"><input type="radio" class="crewType" name="crew[existing]['.$crewCounter.'][crewType]" id="crewType2_'.$data_obj->id.'" value="2" checked>Company</label>
								<label>Enter Crew Name <span class="required">*</span></label><input type="text" data-autocomplete="company" name="crew[existing]['.$crewCounter.'][crewName]" required="required" rel="tooltip" data-title="Company name; Input a minimum of 3 characters to receive suggestions" data-placement="right" value="'.$Company->companyName.'"><label>Role <span class="required">*</span></label><input type="text" data-autocomplete="role" required="required" name="crew[existing]['.$crewCounter.'][roleName]" value="'.$data_obj->role->roleName.'" maxlength="100" rel="tooltip" data-placement="right" data-title="Input a minimum of 3 characters to receive suggestions" /><label>Start date</label><input type="text" data-exttype="date" name="crew[existing]['.$crewCounter.'][startDate]" value="'.$data_obj->startDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><label>End Date</label><input type="text" data-extType="date" name="crew[existing]['.$crewCounter.'][endDate]" value="'.$data_obj->endDate.'" rel="tooltip" data-title="MM-DD-YYYY" data-placement="right"><input type="hidden" id="individualID_'.$data_obj->id.'" name="crew[existing]['.$crewCounter.'][profileID]" value="'.$data_obj->companyID.'" ><input type="hidden" id="roleID_'.$data_obj->id.'" name="crew[existing]['.$crewCounter.'][roleID]" value="'.$data_obj->roleID.'" ><input type="hidden" name="crew[existing]['.$crewCounter.'][crewTypePrev]" value="2" /><input type="hidden"  name="crew[existing]['.$crewCounter.'][id]" value="'.$data_obj->id.'" ></strong></div>';
								
								$crewCounter++;
							}
						}
					}
				?>
			</div>
		</div>
	</div>
	
	<div class="row buttons">
		<?php
			echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary btn-large','id'=>'btnSubmit',));
			echo CHtml::button('Cancel',array('onclick'=>'js:history.go(-1);returnFalse;','class'=>'btn btn-primary btn-large','style'=>'margin-left:10px;'));
		 ?>
	</div>

	<?php 
		//echo CHtml::dropDownList('role', '',CHtml::listData(Role::model()->findAll(), 'id', 'roleName'),array('style'=>'display:none;') );
	?>
<?php $this->endWidget(); ?>
</div><!-- form -->