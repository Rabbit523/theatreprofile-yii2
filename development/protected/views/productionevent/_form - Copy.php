<?php
/* @var $this ProductioneventController */
/* @var $model Productionevent */
/* @var $form CActiveForm */
$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($productionvenue->production->show->showName)?' '.$productionvenue->production->show->showName:$productionvenue->production->show->showName)=>$productionvenue->production->show->createUrl(),!empty($productionvenue->production->productionName)?$productionvenue->production->productionName:$productionvenue->production->show->showName.' at '.$productionvenue->venue->venueName => $productionvenue->production->createUrl(),'Events');
$pageTitle = (!empty($productionvenue->production->productionName)?$productionvenue->production->show->showName.' - '.$productionvenue->production->productionName:$productionvenue->production->show->showName.' at '.$productionvenue->venue->venueName)." - Production - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.ui',CClientScript::POS_END);
$cs->registerCssFile($cs->getCoreScriptUrl(). '/jui/css/base/jquery-ui.css');
$cs->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui-timepicker-addon.min.css');
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-ui-timepicker-addon.min.js',CClientScript::POS_END);
?>
<div class="form">
	<div class="well">
		<div class="productionInfo">
			<div class="media">
				<?php
				$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$productionvenue->productionID);
				$show=Show::model()->with('category')->with('showcreators')->findByPk($productionvenue->production->showID);
				if(isset($profile_image->image->imageURL))
				{
					$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
					Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
					//Yii::app()->clientScript->registerMetaTag("140", null, null, array('property' => "og:image:width"));
					//Yii::app()->clientScript->registerMetaTag("220", null, null, array('property' => "og:image:height"));
				}
				else
				{
					$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$show->id);
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
					<div class="media-heading"><h1 class="inline">
					<?php echo $show->showName; ?><small><?php echo ' '.$productionvenue->production->productionName; ?></small></h1>
					</div>
					<div class="clearfix" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
					<?php
					$Productionrating = Productionrating::model()->find("productionID=:productionID",array(':productionID'=>$productionvenue->production->id));
					if(!empty($Productionrating))
					{
						echo '<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">Average rating: <span itemprop="average" class="badge badge-important"><strong>'.$productionvenue->production->avgrating.'</strong></span>/<span itemprop="bestRating">5</span> from <span itemprop="votes">'.$productionvenue->production->ratingcount.'</span> users.';						
					}
					else
					{
						echo "<small>Average rating not available.</small>";
					}
					?>
					
					</div>
					<div class="row">
						<div><?php echo $show->category->categoryName; ?></div>
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
									case "Book":$book = $book.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									case "Lyrics":$lyrics = $lyrics.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									case "Music":$music = $music.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									case "Adaptation":$adaptation = $adaptation.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									case "Translation":$translation = $translation.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									case "Concept":$concept = $concept.'<span itemscope itemtype="http://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
									default:break;
								}
							}
							if($book!='')echo '<li><strong>Book: </strong>'.substr($book, 0, -2).'</li>';
							if($music!='')echo '<li><strong>Music: </strong>'.substr($music,0,-2).'</li>';
							if($lyrics!='')echo '<li><strong>Lyrics: </strong>'.substr($lyrics,0,-2).'</li>';
							if($adaptation!='')echo '<li><strong>Adaptation: </strong>'.substr($adaptation,0,-2).'</li>';
							if($translation!='')echo '<li><strong>Translation: </strong>'.substr($translation,0,-2).'</li>';
							if($concept!='')echo '<li><strong>Concept: </strong>'.substr($concept,0,-2).'</li>';								
							echo '<li>';
							echo '<strong>Venue: </strong><a href="'.$productionvenue->venue->createUrl().'">'.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->addr1.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->state.', '.$productionvenue->venue->address->country->countryName.'</a>';
							$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');						
							foreach($links as $link)
							{
								echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini btn-danger">'.$link->label.'</a>';
							}
							echo '</li>';
							if(!empty($productionvenue->production->firstPreviewDate))
							{
								$newDate = DateTime::createFromFormat('m-d-Y', $productionvenue->production->firstPreviewDate);
								$productionvenue->production->firstPreviewDate = $newDate->format('M d, Y');
							}
							if(!empty($productionvenue->production->startDate))
							{
								$newDate = DateTime::createFromFormat('m-d-Y', $productionvenue->production->startDate);
								$productionvenue->production->startDate = $newDate->format('M d, Y');
							}
							if(!empty($productionvenue->production->endDate))
							{
								$newDate = DateTime::createFromFormat('m-d-Y', $productionvenue->production->endDate);
								$productionvenue->production->endDate = $newDate->format('M d, Y');
							}
							echo '<li><strong>First Preview: </strong>'.$productionvenue->production->firstPreviewDate.'</li>';
							echo '<li><strong>Opening: </strong>'.$productionvenue->production->startDate.'</li>';
							echo '<li><strong>Closing: </strong>'.$productionvenue->production->endDate.'</li>';
						?>
						</ul>
					</div>
				</div>
				<p class="clear">
					<?php
						if($productionvenue->production->descr!='')
							echo $productionvenue->production->descr;
						else
							echo $productionvenue->production->show->showDesc;
					?>
				</p>
			</div>
		</div>
	</div>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'productionevent-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'startDate'); ?>
		<?php echo $form->textField($model,'startDate',array('class'=>'span3')); ?>
		<?php echo $form->error($model,'startDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endDate'); ?>
		<?php echo $form->textField($model,'endDate',array('class'=>'span3')); ?>
		<?php echo $form->error($model,'endDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<div class="compactRadioGroup">
		<?php echo $form->radioButtonList($model,'type',array(0=>'Public',1=>'Private'), array('separator' => " ")); ?>
		</div>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recurs'); ?>
		<div class="compactRadioGroup">
		<?php echo $form->radioButtonList($model,'recurs',array(0=>'None',1=>'Daily',2=>'Weekly',4=>'Monthly'), array('separator' => " ",'onchange' => 'toggleRecurAttributes(this.value);')); ?>
		</div>
		<?php echo $form->error($model,'recurs'); ?>
	</div>
	
	<div class="row recurAttribute hide">
		<?php echo $form->labelEx($model,'recursStartDate'); ?>
		<?php echo $form->textField($model,'recursStartDate',array('class'=>'span3',"rel"=>"tooltip","title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
		<?php echo $form->error($model,'recursStartDate'); ?>
	</div>

	<div class="row recurAttribute hide">
		<?php echo $form->labelEx($model,'recursEndDate'); ?>
		<?php echo $form->textField($model,'recursEndDate',array('class'=>'span3',"rel"=>"tooltip","title"=>"MM-DD-YYYY","data-placement"=>"right")); ?>
		<?php echo $form->error($model,'recursEndDate'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-large btn-primary')); ?>
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
		$(".recurAttribute").show('slidedown');
}
$("#Productionevent_startDate").datetimepicker();
$("#Productionevent_endDate").datetimepicker();
$("#Productionevent_recursStartDate").datepicker({dateFormat:"mm-dd-yy"});
$("#Productionevent_recursEndDate").datepicker({dateFormat:"mm-dd-yy"});
JS
);
?>