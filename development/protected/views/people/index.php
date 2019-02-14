<?php
/* @var $this SiteController */

$this->pageTitle="People - ".Yii::app()->name;
$this->breadcrumbs=array(
	'People',
);

?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<div class="row">
	<div class="span10">
		<div class="page-header">
		  <h1>People <small>A list of all theatre cast, creatives and crew</small></h1>
		</div>
		<!--
		<div class="well">Sample explanation for this section.</div>
		-->
		<?php 
		$dataProvider = new CActiveDataProvider('Individual',array(
		'pagination'=>array('pageSize'=>20),
		'criteria'=>array(
			'order'=>'firstName,middleName,lastName,suffix',
		),
		));
		$this->widget('bootstrap.widgets.TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>"{summary}{pager}\n{items}\n{summary}{pager}",
		'itemView'=>'_index',
		'summaryText'=>'Displaying {start}-{end} of {count} people.'
		));
		?>
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