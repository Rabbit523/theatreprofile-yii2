<?php
$this->pageTitle="Companies - ".Yii::app()->name;
$this->breadcrumbs=array('Companies',);
?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<div class="row">
	<div class="span10">
		<div class="page-header">
		  <h1>Companies <small>A list of all theatre companies, organizations & groups</small></h1>
		</div>
		<?php 
		$dataProvider = new CActiveDataProvider('Company',array(
		'pagination'=>array('pageSize'=>15),
		'criteria'=>array(
			'order'=>'companyName',
		),
		));
		$this->widget('bootstrap.widgets.TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>"{summary}{pager}\n{items}\n{summary}{pager}",
		'summaryText'=>'Displaying {start}-{end} of {count} companies.',
		'itemView'=>'_index'
		));
		?>
	</div>
	<?php if(!YII_DEBUG): ?>
	<div class="span2 last visible-desktop">
		<div id='div-gpt-ad-1452790578742-0' style='height:600px; width:160px;'>
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-0'); });
			</script>
		</div>
	</div>
	<?php endif; ?>
</div>