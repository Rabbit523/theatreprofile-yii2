<?php
/* @var $this ShowController */
/* @var $model Show */
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
$js=Yii::app()->assetManager->publish($root.'/protected/views/show/_form.js');
Yii::app()->clientScript->registerScriptFile($js,CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.isNewRecord=".($model->isNewRecord?1:0).";cfg.creator_counter=0;", CClientScript::POS_BEGIN);
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
  .pnl-profile-pic > .media-object{width:140px;height:220px;}
  #preview1 img {max-height:75px;}
  #links {padding: 5px;margin-top: 2px;margin-bottom: 2px;}
  #links img {max-height:60px;margin:0 2px;}");
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'show-form',
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
	<?php echo $form->errorSummary($model); ?>

	<div class="show">
		<div class="media">
			<?php
			if(!$model->isNewRecord)
			{
				$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$model->id);
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
						<div class="row">
							<?php echo $form->labelEx($model,'showName'); ?>
							<?php echo $form->textField($model,'showName',array('maxlength'=>100,'class'=>'span4')); ?>
							<?php echo $form->error($model,'showName'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model,'categoryID'); ?>
							<?php echo $form->dropDownList($model,'categoryID',
							 CHtml::listData(Showcategory::model()->findAll(), 'id', 'categoryName'), array('empty'=>'Select category','class'=>'span4')); ?>
							<?php echo $form->error($model,'categoryID'); ?>
						</div>

						<div class="row">
							<?php echo $form->labelEx($model,'Publication Date'); ?>
							<?php echo $form->textField($model,'showDate',array('class'=>'span4',"rel"=>"tooltip","data-title"=>"YYYY","data-placement"=>"right")); ?>
							<?php echo $form->error($model,'showDate'); ?>
						</div>
					</div>
				</div>
			</div>
			<br />
			<br />
			<div class="row">
				<?php echo $form->labelEx($model,'Show Description'); ?>
				<?php echo $form->textArea($model,'showDesc',array('maxlength'=>3000,'class'=>'span11','style'=>'height:150px')); ?>
				<?php echo $form->error($model,'showDesc'); ?>
			</div>
		</div>
	</div>
	
	<?php
		if(!$model->isNewRecord)
		{
			$profile_images=Profileimage::model()->findAll('profileType=1 AND imageType=2 AND profileID='.$model->id);			
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
			echo CHtml::ajaxLink ('<i class="icon-trash"></i> Delete',array('show/deleteProfileImage'),array('data'=>array('showID'=>$model->id,'id'=>'js:$("#links>a")[$("#blueimp-gallery").data("gallery").getIndex()].getAttribute("data-id")','YII_CSRF_TOKEN'=>'js:cfg.csrfToken'),'type'=>'POST','success' => 'function() {location.reload();}'),array('class'=>'delete btn'));
			echo '<a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a><ol class="indicator"></ol></div>';
		}
	?>
	
	<div class="row-fluid">
		<div class="span11 mini-layout">
			<h4>Show Creators</h4>
			<?php
			$roles=Role::model()->with(array(
				'department'=>array(
					'select'=>false,
					'joinType'=>'INNER JOIN',
					'condition'=>'department.departmentName=:departmentName',
					'params'=>array(':departmentName'=>'Authors')
				)
			))->findAll();

			foreach($roles as $role){
			?>
				<div class="row-fluid">
					<div class="span11 mini-layout">
						<p><strong><?php echo $role->roleName ?>: <a id="role_adder" href="javascript:void(0);" onclick="role_adder('<?php echo $role->id ?>')">ADD</a></strong></p>
						<div class="record_list">
							<div id="role_<?php echo $role->id ?>">
								<?php
								if(!$model->isNewRecord)
								{
									foreach($model->showcreators as $showcreator)
									{
										if($showcreator->role->id==$role->id)
										echo '<div class="well"><a class="ui-icon ui-icon-close pull-right deleteConfirm" href="'.yii::app()->createUrl('show/removecreator',array('id'=>$showcreator->id,'showid'=>$model->id)).'"></a><strong>'.$showcreator->individual->firstName.' '.$showcreator->individual->middleName.' '.$showcreator->individual->lastName.' '.$showcreator->individual->suffix.' </strong></div>' ;
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>

	<div class="row buttons">
		<?php
			echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary btn-large','id'=>'btnSubmit',));
			if(!$model->isNewRecord)
			//echo ' <a href="'.yii::app()->createUrl('/production/create',array('showid'=>$model->id)).'" class="btn btn-primary btn-large"></a> ';
			echo CHtml::Link('Add Production',yii::app()->createUrl('/production/create/show/'.$model->id),array('class'=>'btn btn-primary btn-large','style'=>'margin-left:10px;'));
			echo CHtml::button('Cancel',array('onclick'=>'js:history.go(-1);returnFalse;','class'=>'btn btn-primary btn-large','style'=>'margin-left:10px;'));
		 ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->