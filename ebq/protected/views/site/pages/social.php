<?php
/* @var $this SiteController */

$this->pageTitle='Social - '.Yii::app()->name;
$this->breadcrumbs=array('Social',);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.isotope.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.magnific-popup.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.dpSocialTimeline.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/dpSocialTimeline.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/magnific-popup.css');
?>
<h1>Social</h1>
<div id="socialTimeline" style="width:100%;"></div>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(document).ready(function(){
	$('#socialTimeline').dpSocialTimeline({
		feeds:{facebook_page: {data: '520980071349827'},tumblr: {data: 'theatreprofile'},twitter: {data: 'TheatreProfile'},},
		timelineItemWidth: '550px',
		columnsItemWidth: '280px',
		oneColumnItemWidth: '20%',
		showLayoutOneColumn: false,
		skin: 'modern',
		share: true,
		addLightbox: true,
		total: 50,
		layoutMode: 'columns',
		showSocialIcons: true,
		showFilter: false,
		cache: false,
	});
});
JS
, CClientScript::POS_READY);
?>