<?php
$this->pageTitle='Search - '.Yii::app()->name;
$this->breadcrumbs=array(
	'Search',
);
?>
<style>
.thumbnail .caption {text-align:center; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.block-list .items .thumbnails {margin:0px}
.block-list .items .thumbnails li {float:none;margin:2px}

</style>
<div class="page-header">
  <h1>Search Results for <?php echo '"'.$term.'"'; ?></h1>
</div>

<?php
if($dataProvider1->totalItemCount+$dataProvider2->totalItemCount+$dataProvider3->totalItemCount+$dataProvider4->totalItemCount+$dataProvider5->totalItemCount==0)
echo '<div>No results found</div>';

if($dataProvider1->totalItemCount>0)
{
	echo '<div id="shows" class="mini-layout"><h4>Shows</h4><hr class="red_line">';
	$this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>$dataProvider1,
	'template'=>"{summary}{pager}\n{items}",
	'itemView'=>'_index1',
	'htmlOptions'=>array("class"=>"block-list list-view"),
	'summaryText'=>'Displaying {start}-{end} of {count} shows.'
	));
	echo '</div>';
}
if($dataProvider2->totalItemCount>0)
{
	echo '<div id="productions" class="mini-layout"><h4>Productions</h4><hr class="red_line">';
	$this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>$dataProvider2,
	'template'=>"{summary}{pager}\n{items}",
	'itemView'=>'_index2',
	'htmlOptions'=>array("class"=>"block-list list-view"),
	'summaryText'=>'Displaying {start}-{end} of {count} productions.'
	));
	echo '</div>';
}
if($dataProvider3->totalItemCount>0)
{
	echo '<div id="people" class="mini-layout"><h4>People</h4><hr class="red_line">';
	$this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>$dataProvider3,
	'template'=>"{summary}{pager}\n{items}",
	'itemView'=>'_index3',
	'summaryText'=>'Displaying {start}-{end} of {count} people.'
	));
	echo '</div>';
}
if($dataProvider4->totalItemCount>0)
{
	echo '<div id="venues" class="mini-layout"><h4>Venues</h4><hr class="red_line">';
	$this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>$dataProvider4,
	'template'=>"{summary}{pager}\n{items}",
	'itemView'=>'_index4',
	'summaryText'=>'Displaying {start}-{end} of {count} venues.'
	));
	echo '</div>';
}
if($dataProvider5->totalItemCount>0)
{
	echo '<div id="companies" class="mini-layout"><h4>Companies</h4><hr class="red_line">';
	$this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>$dataProvider5,
	'template'=>"{summary}{pager}\n{items}",
	'itemView'=>'_index5',
	'summaryText'=>'Displaying {start}-{end} of {count} companies.'
	));
	echo '</div>';
}		
?>
