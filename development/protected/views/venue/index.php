<?php
/* @var $this SiteController */

$this->pageTitle="Venues - ".Yii::app()->name;
$this->breadcrumbs=array(
	'Venues',
);

?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<div class="row">
	<div class="span10">
		<div class="page-header">
		  <h1>Venues <small>A list of all theatre venues</small></h1>
		</div>
		<!--
		<div class="well">Sample explanation for this section.</div>
		-->
		<?php 
		$dataProvider = new CActiveDataProvider('Venue',array(
		'pagination'=>array('pageSize'=>15),
		'criteria'=>array(
			'order'=>'venueName',
		),
		));
		$this->widget('bootstrap.widgets.TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>"{summary}{pager}\n{items}\n{summary}{pager}",
		'summaryText'=>'Displaying {start}-{end} of {count} venues.',
		'itemView'=>'_index'
		));
		?>
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