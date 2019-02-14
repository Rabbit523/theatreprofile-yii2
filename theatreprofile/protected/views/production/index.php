<?php
/* @var $this SiteController */

$this->pageTitle="Productions - ".Yii::app()->name;
$this->breadcrumbs=array(
	'Productions',
);

?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<div class="row">
	<div class="span10">
		<div class="page-header">
		  <h1>Productions <small>A list of productions of the shows past, present and future</small></h1>
		</div>
		<!--
		<div class="well">Sample explanation for this section.</div>
		-->
		<?php
		$this->widget('bootstrap.widgets.TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>"{summary}{pager}\n{items}\n{summary}{pager}",
		'summaryText'=>'Displaying {start}-{end} of {count} productions.',
		'itemView'=>'_index'
		));
		?>
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-6' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-6'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>