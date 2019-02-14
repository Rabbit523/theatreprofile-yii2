<?php
/* @var $this FeeditemController */
/* @var $model Feeditem */

$this->pageTitle=$model->title." - News & Updates - ".Yii::app()->name;
Yii::app()->clientScript->registerMetaTag($model->title." - News & Updates - ".Yii::app()->name, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array(
	'News & Updates'=>array('index'),
	$model->title,
);
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
?>
<style type="text/css">
.fb-comments iframe[style], .fb-comments>span {width: 100% !important;}
</style>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=780804988616596&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="mini-layout">
	<h2><?php echo $model->title; ?></h2>
	<small>Published <?php echo formatDateDiff(new DateTime($model->publishDate)); ?> on <?php echo $model->feed->name; ?></small>
	<br /><br />
	<p>
		<?php echo CHtml::decode($model->descr); ?>
		<br /><br />
		<span>Read article on <a target="_blank" href="<?php echo $model->href ?>"><?php echo $model->feed->name; ?></a></span>
	</p>
</div>
<div class="row">
	<div class="span12">
		<div class="fb-comments" data-href="<?php echo Yii::app()->request->hostInfo.Yii::app()->getBaseUrl().'/news/'.$model->id;?>" data-width="100%" data-numposts="10" data-colorscheme="light"></div>
	</div>
</div>
