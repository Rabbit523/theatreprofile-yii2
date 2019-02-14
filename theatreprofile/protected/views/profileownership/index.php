<?php
$this->pageTitle='Profile Ownership - '.Yii::app()->name;
$this->breadcrumbs=array(
	'Profile Ownership',
);
?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.block-list .items .thumbnails {margin:0px}
.block-list .items .thumbnails li {float:none;margin:2px}
</style>
<div class="page-header">
  <h1>Profiles Owned</h1>
</div>

<?php
if($dataProvider1->totalItemCount+$dataProvider2->totalItemCount+$dataProvider3->totalItemCount+$dataProvider4->totalItemCount+$dataProvider5->totalItemCount==0)
echo '<div>You do not own any profiles.</div>';
else
{
?>

<div class="mini-layout">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#shows" data-toggle="tab">Shows</a></li>
		<li><a href="#productions" data-toggle="tab">Productions</a></li>
		<li><a href="#people" data-toggle="tab">People</a></li>
		<li><a href="#venues" data-toggle="tab">Venues</a></li>
		<li><a href="#companies" data-toggle="tab">Companies</a></li>
	</ul>


	<div class="tab-content">
		<div class="tab-pane active" id="shows">
		<?php
		if($dataProvider1->totalItemCount>0)
		{
			$this->widget('bootstrap.widgets.TbThumbnails', array(
			'dataProvider'=>$dataProvider1,
			'template'=>"{summary}{pager}\n{items}",
			'itemView'=>'_index1',
			'htmlOptions'=>array("class"=>"block-list list-view"),
			'summaryText'=>'Displaying {start}-{end} of {count} shows.'
			));
		}
		?>
		</div>
		<div class="tab-pane" id="productions">
		<?php
		if($dataProvider2->totalItemCount>0)
		{
			$this->widget('bootstrap.widgets.TbThumbnails', array(
			'dataProvider'=>$dataProvider2,
			'template'=>"{summary}{pager}\n{items}",
			'itemView'=>'_index2',
			'htmlOptions'=>array("class"=>"block-list list-view"),
			'summaryText'=>'Displaying {start}-{end} of {count} productions.'
			));
		}
		?>
		</div>
		<div class="tab-pane" id="people">
		<?php
		if($dataProvider3->totalItemCount>0)
		{
			$this->widget('bootstrap.widgets.TbThumbnails', array(
			'dataProvider'=>$dataProvider3,
			'template'=>"{summary}{pager}\n{items}",
			'itemView'=>'_index3',
			'htmlOptions'=>array("class"=>"block-list list-view"),
			'summaryText'=>'Displaying {start}-{end} of {count} people.'
			));
		}
		?>
		</div>
		<div class="tab-pane" id="venues">
		<?php
		if($dataProvider4->totalItemCount>0)
		{
			$this->widget('bootstrap.widgets.TbThumbnails', array(
			'dataProvider'=>$dataProvider4,
			'template'=>"{summary}{pager}\n{items}",
			'itemView'=>'_index4',
			'htmlOptions'=>array("class"=>"block-list list-view"),
			'summaryText'=>'Displaying {start}-{end} of {count} venues.'
			));
		}
		?>
		</div>
		<div class="tab-pane" id="companies">
		<?php
		if($dataProvider5->totalItemCount>0)
		{
			$this->widget('bootstrap.widgets.TbThumbnails', array(
			'dataProvider'=>$dataProvider5,
			'template'=>"{summary}{pager}\n{items}",
			'itemView'=>'_index5',
			'htmlOptions'=>array("class"=>"block-list list-view"),
			'summaryText'=>'Displaying {start}-{end} of {count} companies.'
			));
		}
		?>
		</div>
	</div>
</div>
<?php
}
?>