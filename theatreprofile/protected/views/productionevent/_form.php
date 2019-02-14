<?php
/* @var $this ProductioneventController */
/* @var $model Productionevent */
/* @var $form CActiveForm */
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery',CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui',CClientScript::POS_HEAD);
$cs->registerCssFile($cs->getCoreScriptUrl(). '/jui/css/base/jquery-ui.css');
$cs->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui-timepicker-addon.min.css');
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-ui-timepicker-addon.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerCss("viewStyle",".combine-parent {padding: 5px;margin-top:2px;margin-bottom:2px;} .combine-child{margin-top:-5px;border-top:none;}"
);
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'productionevent-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
	)); ?>
	
	<div class="row-fluid">
		<div class="well">
			<div class="venueInfo">
				<div class="media">
					<?php
					$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND profileID='.$venue->id);
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
							<h1 class="inline"><?php echo $venue->venueName; ?></h1>
						</div>
						<ul class="unstyled">
							<?php
								echo '<li><span>'.$venue->address->addr1.'</span></li>';
								echo '<li><span>'.$venue->address->city.', '.$venue->address->state.', '.$venue->address->zip.'</span></li>';
								echo '<li><span>'.$venue->address->country->countryName.'</span></li>';
							?>
						</ul>
					</div>
				</div>
				<?php
				echo '<p class="clear">'.$venue->descr.'</p>';
				?>
			</div>
		</div>
	</div>
	
	
	<div class="row-fluid">
		<div class="span12">
			
			<?php echo $form->errorSummary($model); ?>
			<div class="row span12">
				<div class="span6">
					<p class="note">Fields with <span class="required">*</span> are required.</p>
					<?php echo $form->labelEx($model,'productionVenueID'); ?>
					<?php
					echo $form->dropDownList($model,'productionVenueID', $data,array('class'=>'span12','prompt'=>'Select'));?>
					<?php echo $form->error($model,'productionVenueID'); ?>
				
					<?php echo $form->labelEx($model,'startDate'); ?>
					<?php echo $form->textField($model,'startDate',array('class'=>'span12',"rel"=>"tooltip","title"=>"MM-DD-YYYY HH:MM","data-placement"=>"right")); ?>
					<?php echo $form->error($model,'startDate'); ?>
				
					<?php echo $form->labelEx($model,'type'); ?>
					<?php
					if(!empty($venue->venueownerships))
					{
						echo '<div class="compactRadioGroup">';
						echo $form->radioButtonList($model,'type',array(0=>'Public',1=>'Private'), array('separator' => " "));
						echo '</div>';
					}
					else
					{
						echo '<div class="compactRadioGroup" rel="tooltip" data-placement="right" title="Private events are only available on claimed profiles.">';
						echo $form->radioButtonList($model,'type',array(0=>'Public',1=>'Private'), array('separator' => " ","disabled"=>"disabled")); 
						echo '</div>';
					}
					?>
					<?php echo $form->error($model,'type'); ?>
				
					<?php echo $form->labelEx($model,'recurs'); ?>
					<div class="compactRadioGroup">
					<?php echo $form->radioButtonList($model,'recurs',array(0=>'None',1=>'Daily',2=>'Weekly'), array('separator' => " ",'onchange' => 'toggleRecurAttributes(this.value);')); ?>
					</div>
					<?php echo $form->error($model,'recurs'); ?>
				
					<div class="recurAttribute <?php echo $model->recurs==0?'hide':''; ?>">
						<?php echo $form->labelEx($model,'Recurs Start Date <span class="required">*</span>'); ?>
						<?php echo $form->textField($model,'recursStartDate',array('class'=>'span12',"rel"=>"tooltip","title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
						<?php echo $form->error($model,'recursStartDate'); ?>
					</div>

					<div class="recurAttribute <?php echo $model->recurs==0?'hide':''; ?>">
						<?php echo $form->labelEx($model,'Recurs End Date <span class="required">*</span>'); ?>
						<?php echo $form->textField($model,'recursEndDate',array('class'=>'span12',"rel"=>"tooltip","title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
						<?php echo $form->error($model,'recursEndDate'); ?>
					</div>

					<div>
						<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-large btn-primary')); ?>
						<?php
							echo CHtml::link("Delete Event", '#', array('submit'=>array('productionevent/delete', "id"=>$model->id),"class"=>"btn btn-large btn-primary"));
						?>
						<?php if(!$model->isNewRecord) echo '<a class="btn btn-large btn-primary inline" href="'.Yii::app()->createUrl('productionevent/create').'/'.$venue->id.'">Add New Event</a>'; ?>
				</div>
					</div>
				<div class="span6 pull-right">
					<div class="well well-small combine-parent">
						<strong>All events for this production</strong>
					</div>
					<div class="mini-layout combine-child">
						<?php
							if(isset($events))
							{
								foreach($events as $event)
								{
									echo '<a class="btn btn-small btn-block" href="'.$event['href'].'">'.$event['startDate'].' '.$event['startTime'].'</a>';
								}								
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
function toggleRecurAttributes(val)
{
	if(val==0)
		$(".recurAttribute").hide('slideup');
	else
	{
		$(".recurAttribute").show('slidedown');
		$("#Productionevent_recursStartDate").datepicker( "setDate", $("#Productionevent_startDate").datetimepicker('getDate'));
		$("#Productionevent_recursStartDate").datepicker( "setDate", $("#Productionevent_startDate").datetimepicker('getDate'));
		$("#Productionevent_recursEndDate").datepicker( "setDate", $("#Productionevent_startDate").datetimepicker('getDate'));
	}
}
$("#Productionevent_startDate").datetimepicker({dateFormat:"mm-dd-yy",timeFormat: 'HH:mm', minDate: '-1Y',  maxDate: '+2Y', changeMonth:true, changeYear:true});
$("#Productionevent_recursStartDate").datepicker({dateFormat:"mm-dd-yy", minDate: '-1Y',  maxDate: '+2Y', changeMonth:true, changeYear:true});
$("#Productionevent_recursEndDate").datepicker({dateFormat:"mm-dd-yy", minDate: '-1Y',  maxDate: '+2Y', changeMonth:true, changeYear:true});
$("#Productionevent_recursStartDate").change(function (e) {
	if($("#Productionevent_recursStartDate").datetimepicker('getDate')>$("#Productionevent_recursEndDate").datetimepicker('getDate'))
		$("#Productionevent_recursEndDate").datepicker( "setDate", $("#Productionevent_recursStartDate").datetimepicker('getDate'));
});
$("#Productionevent_productionVenueID").change(function (e) {
	if($("#Productionevent_productionVenueID").val())
		window.location.replace(window.location.pathname+'?pvid='+$("#Productionevent_productionVenueID").val());
	else
				window.location.replace(window.location.pathname);
});
//$("#Productionevent_startDate").change(function (e) {
//	if($('input[name="Productionevent\\[recurs\\]"]:checked').val()!==0)
//	{
//		$("#Productionevent_startDate").val($("#Productionevent_startDate").datetimepicker('getDate'));
//		$("#Productionevent_recursEndDate").val($("#Productionevent_startDate").datetimepicker('getDate'));
//	}
//});
JS
);
?>