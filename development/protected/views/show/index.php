<?php
/* @var $this SiteController */

$this->pageTitle="Shows - ".Yii::app()->name;
$this->breadcrumbs=array(
	'Shows',
);

?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.block-parent {text-align: center;height: 220px;width:140px; background-color:lightgray;margin:0 auto;display: table;}
.centered-child {display: table-cell;text-align: center;vertical-align: middle;color:black;}
.centered-child a {}
</style>

<div class="row">
	<div class="span10">
		<div class="page-header">
		  <h1>Shows <small>A list of all shows past, present and future</small></h1>
		</div>
		<!--
		<div class="well">Sample explanation for this section.</div>
		-->
		<?php 
		$dataProvider = new CActiveDataProvider('Show',array(
			'pagination'=>array('pageSize'=>20,),
			'criteria'=>array(
				'order'=>'showName',
			),
		));
		$this->widget('bootstrap.widgets.TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>"{summary}{pager}\n{items}\n{summary}{pager}",
		'itemView'=>'_index',
		'summaryText'=>'Displaying {start}-{end} of {count} shows.'
		));
		?>
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-7' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-7'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>