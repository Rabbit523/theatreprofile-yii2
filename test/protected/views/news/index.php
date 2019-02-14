<?php
$this->pageTitle=Yii::app()->name. " - News & Updates";
$this->breadcrumbs=array('News & Updates',);
Yii::app()->clientScript->registerMetaTag(Yii::app()->name. " - News & Updates", null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag('News & Updates from the world of theatre.', null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.infinitescroll.min.js',CClientScript::POS_END);
?>
<style type="text/css">
.feeditem:hover {background-color:#fafafa;cursor:pointer;}
#infscr-loading {text-align: center;}
</style>
<h1>News & Updates</h1>
<div class="row">
	<?php
	function formatDateDiff($start, $end=null) { 
		if(!($start instanceof DateTime)) {$start = new DateTime($start);}
		if($end === null) {$end = new DateTime();}
		if(!($end instanceof DateTime)) {$end = new DateTime($start);}
		$interval = $end->diff($start); 
		$doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals 
		$format = array();
		if($interval->y !== 0) {$format[] = "%y ".$doPlural($interval->y, "year");} 
		if($interval->m !== 0) {$format[] = "%m ".$doPlural($interval->m, "month");} 
		if($interval->d !== 0) {$format[] = "%d ".$doPlural($interval->d, "day");} 
		if($interval->h !== 0) {$format[] = "%h ".$doPlural($interval->h, "hour");} 
		if($interval->i !== 0) {$format[] = "%i ".$doPlural($interval->i, "minute");} 
		if($interval->s !== 0) {if(!count($format)) {return "less than a minute ago";} else {$format[] = "%s ".$doPlural($interval->s, "second");}} 
		// We use the two biggest parts 
		if(count($format) > 1) {$format = array_shift($format)." and ".array_shift($format);} else {$format = array_pop($format);} 
		// Prepend 'since ' or whatever you like 
		return $interval->format($format).' ago'; 
	} 
	
	echo '<div class="span12" id="feeditems">';
	foreach ($feeditems as $feeditem)
	{
		echo '<div class="feeditem mini-layout clearfix" data-href="'.$feeditem->createUrl().'">';
		echo '<h3>'.$feeditem->title.'<br />';
		echo '<small>'.formatDateDiff(new DateTime($feeditem->publishDate)).'</small></h3>';
		echo '<p>'.CHtml::decode($feeditem->descr).'</p>';
		echo '<a href="'.$feeditem->createUrl().'" >Permalink</a> <span class="pull-right">Read more on <a href="'.$feeditem->href.'" target="_blank">'.$feeditem->feed->name.'.</a></span>';
		echo '</div>';
	}
	echo '</div><div class="infinite_navigation"><a href="'.yii::app()->getBaseUrl().'/news/index?page=2">next</a></div>';
	?>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$('#feeditems').infinitescroll({loading: {
finished: undefined,
finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
img: cfg.baseUrl+'/images/loading.gif',
msg: null,
msgText: " ",
selector: null,
speed: 0,
animate:false,
start: undefined
},'itemSelector':'div.feeditem','navSelector':'div.infinite_navigation','nextSelector':'div.infinite_navigation a:first','bufferPx':300
});
  
$("#feeditems").on("click",".feeditem",function(){
	window.location.href = $(this).attr("data-href");
});
JS
, CClientScript::POS_READY);
?>