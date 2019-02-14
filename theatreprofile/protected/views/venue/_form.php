<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery',CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui',CClientScript::POS_HEAD);
$cs->registerCssFile($cs->getCoreScriptUrl(). '/jui/css/base/jquery-ui.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/blueimp-gallery.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.blueimp-gallery.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.Jcrop.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.Jcrop.min.js',CClientScript::POS_END);
$root=Yii::getPathOfAlias('webroot');
$js=Yii::app()->assetManager->publish($root.'/protected/views/venue/_form.js');
Yii::app()->clientScript->registerScriptFile($js,CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.isNewRecord=".($model->isNewRecord?1:0).";cfg.production_counter=0;cfg.link_counter=0;", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".well {padding: 5px;margin-top:2px;margin-bottom:2px;}
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
  .pnl-profile-pic > .media-object{width:250px;height:150px;}
  #preview1 img {max-height:75px;}
  #links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
  #links img {max-height:60px;margin:0 2px;}");
?>

<div class="form">
	<?php
		$form=$this->beginWidget('CActiveForm', array(
		'id'=>'venue-form',
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
	<?php echo $form->errorSummary($model);	?>
	<?php if(Yii::app()->user->hasFlash('notify')):?>
		<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'notifyModal','autoOpen'=>'true')); ?>
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4>Hey there!</h4>
		</div>
		<div class="modal-body">
			<p>
				We noticed you created the following new productions, thanks for the info! It would be great to know more. Click the Edit button to add more info, a new window will open for you.<br /><br />
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
	
	<div class="venue">
		<div class="media">
			<?php
			if(!$model->isNewRecord)
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND imageType=1 AND profileID='.$model->id);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);		
				}
				else
				{
					$image_url=yii::app()->request->baseUrl.'/images/default/default_250x150.gif';
				}
			}
			else
			{
				$image_url=yii::app()->request->baseUrl.'/images/default/default_250x150.gif';
			}
			?>
			<div class="pull-left text-center">
				<div class="pnl-profile-pic" data-toggle="modal" data-target="#myModal" rel="tooltip" data-title="Click to update profile picture" data-placement="bottom">
					<img id="imgProfilePic" class="media-object" data-src="<?php echo $image_url; ?>" src="<?php echo $image_url; ?>" width="250px" height="150px" alt="" />
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
					<div class="well"><strong>Click or drag & drop image file below (Recommended dimensions: 500X300 pixels).</strong></div>
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
						<div class="row">
							<?php echo $form->labelEx($model,'venueName'); ?>
							<?php echo $form->textField($model,'venueName',array('class'=>'span4','maxlength'=>100)); ?>
							<?php echo $form->error($model,'venueName'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model->address,'Address'); ?>
							<?php echo $form->textField($model->address,'addr1',array('class'=>'span4','maxlength'=>45)); ?>
							<?php echo $form->error($model->address,'addr1'); ?>
						</div>
						
						<div class="row">
							<?php echo $form->labelEx($model->address,'city'); ?>
							<?php echo $form->textField($model->address,'city',array('class'=>'span4','maxlength'=>45)); ?>
							<?php echo $form->error($model->address,'city'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model->address,'state'); ?>
							<?php echo $form->textField($model->address,'state',array('class'=>'span4','maxlength'=>45)); ?>
							<?php echo $form->error($model->address,'state'); ?>
						</div>
					</div>
				
					<div class="span4">
						<div class="row">
							<?php echo $form->labelEx($model->address,'Postal Code'); ?>
							<?php echo $form->textField($model->address,'zip',array('class'=>'span4','maxlength'=>10)); ?>
							<?php echo $form->error($model->address,'zip'); ?>
						</div>
						
						<div class="row">
							<?php echo $form->labelEx($model->address, 'countryID'); ?>
							<?php echo $form->dropDownList($model->address,'countryID', CHtml::listData(Country::model()->findAll(),
								'id', //this is the attribute name(of Venue model- could be the id of the venue) for list option values 
								'countryName' // this is the attribute name(of Venue model- could be the name of the venue) for list option texts 
							   ),array('class'=>'span4')
							); ?>
							<?php echo $form->error($model->address,'countryID'); ?>
						</div>
						
						<div class="row">
							<?php echo $form->labelEx($model,'descr'); ?>
							<?php echo $form->textArea($model,'descr',array('rows'=>4,'maxlength'=>3000,'class'=>'span4')); ?>
							<?php echo $form->error($model,'descr'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<label>Social Links</label>
				<?php
				if(!$model->isNewRecord)
				{
					$Venuecontactinfo1=Venuecontactinfo::model()->find('contactTypeID=1 AND venueID='.$model->id);
					if($Venuecontactinfo1==null)
					{
						echo '<input class="span11" name="contactInfo_facebook" type="text" value="" placeholder="Facebook" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_facebook" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_facebook" type="text" value="'.$Venuecontactinfo1->contactInfo.'" placeholder="Facebook" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_facebook" type="text" value="'.$Venuecontactinfo1->id.'">';
					}
					$Venuecontactinfo2=Venuecontactinfo::model()->find('contactTypeID=2 AND venueID='.$model->id);
					if($Venuecontactinfo2==null)
					{
						echo '<input class="span11" name="contactInfo_googleplus" type="text" value="" placeholder="Google Plus" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_googleplus" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_googleplus" type="text" value="'.$Venuecontactinfo2->contactInfo.'" placeholder="Google Plus" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_googleplus" type="text" value="'.$Venuecontactinfo2->id.'">';
					}
					$Venuecontactinfo3=Venuecontactinfo::model()->find('contactTypeID=3 AND venueID='.$model->id);
					if($Venuecontactinfo3==null)
					{
						echo '<input class="span11" name="contactInfo_twitter" type="text" value="" placeholder="Twitter" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_twitter" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_twitter" type="text" value="'.$Venuecontactinfo3->contactInfo.'" placeholder="Twitter" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_twitter" type="text" value="'.$Venuecontactinfo3->id.'">';
					}
					$Venuecontactinfo4=Venuecontactinfo::model()->find('contactTypeID=4 AND venueID='.$model->id);
					if($Venuecontactinfo4==null)
					{
						echo '<input class="span11" name="contactInfo_instagram" type="text" value="" placeholder="Instagram" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_instagram" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_instagram" type="text" value="'.$Venuecontactinfo4->contactInfo.'" placeholder="Instagram" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_instagram" type="text" value="'.$Venuecontactinfo4->id.'">';
					}
					$Venuecontactinfo5=Venuecontactinfo::model()->find('contactTypeID=5 AND venueID='.$model->id);
					if($Venuecontactinfo5==null)
					{
						echo '<input class="span11" name="contactInfo_website" type="text" value="" placeholder="Website" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_website" type="text" value="0">';
					}
					else
					{
						echo '<input class="span11" name="contactInfo_website" type="text" value="'.$Venuecontactinfo5->contactInfo.'" placeholder="Website" rel="tooltip" data-placement="left" data-title="Input full URL including http:// or https://">';
						echo '<input type="hidden" name="contactInfoID_website" type="text" value="'.$Venuecontactinfo5->id.'">';
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
		</div>
	</div>
	
	<?php
		if(!$model->isNewRecord)
		{
			$profile_images=Profileimage::model()->findAll('profileType=4 AND imageType=2 AND profileID='.$model->id);			
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
					echo '<a class="btn btn-large" data-toggle="modal" data-target="#myModal1" style="padding:20px 25px" rel="tooltip" data-title="Add picture to gallery">';
					echo '<i class="icon-plus"></i>';
					echo '</a> Add picture to gallery';
					echo '</div></div>';
			}
			echo '<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"><!-- The container for the modal slides --><div class="slides"></div><h3 class="title"></h3>';
			echo CHtml::ajaxLink ('<i class="icon-trash"></i> Delete',array('venue/deleteProfileImage'),array('data'=>array('venueID'=>$model->id,'id'=>'js:$("#links>a")[$("#blueimp-gallery").data("gallery").getIndex()].getAttribute("data-id")','YII_CSRF_TOKEN'=>'js:cfg.csrfToken'),'type'=>'POST','success' => 'function() {location.reload();}'),array('class'=>'delete btn'));
			echo '<a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a><ol class="indicator"></ol></div>';
		}
	?>
	
	<div class="row-fluid">
		<div class="span11 mini-layout">
			<p><strong>Productions at this venue: <a id="production_adder" href="javascript:void(0);" onclick="production_adder()">ADD</a></strong></p>
			<div class="record_list" id="production_container">
			<?php
				if(!$model->isNewRecord)
				{
					$productionCounter=0;
					foreach($model->productionvenues as $data_obj)
					{
						echo '<div>';
						echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Venue/removeproduction',array('id'=>$data_obj->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a><strong>'.$data_obj->production->show->showName.(!empty($data_obj->production->productionName)?' - '.$data_obj->production->productionName:'').'<br />Start Date: '.str_replace('00:00:00', '', $data_obj->startDate).' End Date: '.str_replace('00:00:00', '', $data_obj->endDate).'</strong></div>';
						
						echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Venue/removeproduction',array('id'=>$data_obj->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><label>Show <span class="required">*</span></label><input type="text" data-autocomplete="show" name="production[existing]['.$productionCounter.'][showName]" required="required" value="'.$data_obj->production->show->showName.'" rel="tooltip" data-placement="right" data-title="Input a minimum of 3 characters to receive suggestions" /><label>Production <span class="required">*</span></label>';
						
						$productions = Production::model()->with(array('show'=>array('condition'=>'showID='.$data_obj->production->showID),))->with('productionvenues.venue.address.country')->findAll();
						$item=array();
						foreach($productions as $production)
						{
							if(count($production->productionvenues)>1)
							{
								$item[$production->id]=$production->show->showName.(!empty($production->productionName)?' - '.$production->productionName:' - Multiple venues');
							}
							else if(count($production->productionvenues)==1)
							{
								$productionvenue = array_values($production->productionvenues)[0];
								$item[$production->id]=$production->show->showName.(!empty($production->productionName)?' - '.$production->productionName:' - '.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->country->countryCode);
							}
						}
						echo CHtml::dropdownlist('production[existing]['.$productionCounter.'][productionID]','',array('0'=>'Select','0'=>'Create new production')+$item,array('id'=>'productionID_'.$data_obj->id, 'options'=>array($data_obj->productionID=>array('selected'=>true))));
						
						echo '<label>Start date</label><input type="text" data-exttype="date" name="production[existing]['.$productionCounter.'][startDate]" value="'.str_replace('00:00:00', '', $data_obj->startDate).'" /><label>End date</label><input type="text" data-exttype="date" name="production[existing]['.$productionCounter.'][endDate]" value="'.str_replace('00:00:00', '', $data_obj->endDate).'" /><input id="showID_'.$data_obj->id.'" type="hidden" name="production[existing]['.$productionCounter.'][showID]" value="'.$data_obj->production->showID.'" /><input type="hidden" name="production[existing]['.$productionCounter.'][id]" value="'.$data_obj->id.'" /></div>';
						
						echo '<div class="mini-layout combine" id="ticketinglinks_'.$data_obj->id.'">';
						echo '<a class="btn btn-mini btn-danger" onclick="link_adder('.$data_obj->id.');">Add ticketing link</a>';
						$ticketingLinks = Link::model()->findAll('profileType=5 AND profileID='.$data_obj->id.' and linkType=1');						
						if(count($ticketingLinks)>0)
						{
							$linkCounter=0;
							
							foreach($ticketingLinks as $ticketingLink)
							{
								echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Venue/removelink',array('id'=>$ticketingLink->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-pencil pull-right edit"></a>'.$ticketingLink->label.': '.$ticketingLink->href.'</div>';
								
								echo '<div class="well hide"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('Venue/removelink',array('id'=>$ticketingLink->id,'venueid'=>$model->id)).'"></a><a class="ui-icon ui-icon-arrowreturnthick-1-w pull-right editCancel"></a><label>Label <span class="required">*</span></label><input type="text" value="'.$ticketingLink->label.'" class="input-block-level" name="links[existing]['.$linkCounter.'][label]" required="required" /><label>URL <span class="required">*</span></label><input type="text" class="input-block-level" value="'.$ticketingLink->href.'" name="links[existing]['.$linkCounter.'][href]" rel="tooltip" data-trigger="focus" data-placement="right" data-title="Input full URL including http:// or https://" required="required" /><input type="hidden" name="links[existing]['.$linkCounter.'][id]" value="'.$ticketingLink->id.'" /></div>';
								
								$linkCounter++;
							}
						}
						echo '</div></div>';
						$productionCounter++;
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

<?php $this->endWidget(); ?>
</div><!-- form -->