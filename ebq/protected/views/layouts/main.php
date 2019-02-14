<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="language" content="en" />
<meta name="keywords" content="theatre,broadway,shows,productions,actors,actresses,venues,database" />
<?php
	Yii::app()->clientScript->registerMetaTag("website", null, null, array('property' => "og:type"));
	Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:site_name"));
	Yii::app()->clientScript->registerMetaTag(Yii::app()->getBaseUrl(true).Yii::app()->request->getUrl(),null, null, array('property' => "og:url"));
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
<!--  Bootstrap framework CSS and theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!--load font awesome-->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/main.css');
?>
</head>
<body itemscope itemtype="http://schema.org/WebSite">
	<header>
		<div class="navbar-wrapper">
				<nav class="navbar navbar-inverse navbar-static-top">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
						<a href="<?php echo Yii::app()->getBaseUrl();?>" class="navbar-brand"><img src="<?php echo yii::app()->getBaseUrl(true).'/images/logo.png'; ?>" /></a>
						</div>
						<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li><a href="<?php echo Yii::app()->getBaseUrl();?>">HOME</a></li>
							<!--<li><a href="#">SERVICES</a></li>
							<li><a href="#contact">PRESS</a></li>
							<li><a href="#">MEDIA & AUDIO</a></li>
-->
							<li><a href="#footer">CONNECT</a></li>
						</ul>
					</div>
				</nav>
		</div>
	</header>
	
	<div class="content">
	<?php echo $content; ?>
	</div>

	

	<!-- FOOTER -->
	<footer id="footer">
		<hr class="divider-large">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
				<div class="col-lg-11">
					<h4><strong>MAIN MENU</strong></h4>
					<hr>
					<ul class="nav nav-pills nav-stacked">
						<li><a href="<?php echo Yii::app()->getBaseUrl();?>">HOME</a></li>
						<li><a href="http://www.theatreprofile.com/company/141/ebq-entertainment" target="_new">EVENTS</a></li>
<!--						<li><a href="#">SERVICES</a></li>
						<li><a href="#">CONNECT</a></li>
						<li><a href="#">MEDIA & AUDIO</a></li>
-->
					</ul>
				</div>
				</div><!-- /.col-lg-4 -->
				<div class="col-lg-4">
				<div class="col-lg-11">
					<h4><strong>ABOUT EBQ</strong></h4>
					<hr>
					<p style="line-height:3" class="text-justify">
					WE WOULDN'T SAY THAT EBQ - OR EVIL BUTTER QUEEN - IS THE GREATEST THING SINCE SLICED BREAD.. THAT IS,  WE WOULDN'T SAY IT IF IT WEREN'T TRUE!
					<a href="http://www.theatreprofile.com/company/141/ebq-entertainment" target="_new" style="color:black;font-weight:700;border-bottom:1px dotted;">READ MORE</a>
					</p>
				</div>
				</div><!-- /.col-lg-4 -->
				<div class="col-lg-4">
				<div class="col-lg-11">
					<h4><strong>CONNECT</strong></h4>
					<hr>
					<p style="line-height:3" class="text-justify">
					GET THE LATEST IN UPCOMING LIVE ENTERTAINMENT, DISCOUNTS AND SPECIAL OFFERS. COME TO OUR HOUSE; BE ONE OF US.
					</p>
<div class="createsend-button" style="height:22px;display:inline-block;" data-listid="d/04/BF9/018/405DC2C2DEFD33CA">
</div><script type="text/javascript">(function () { var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true; e.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://btn.createsend1.com/js/sb.min.js?v=3'; e.className = 'createsend-script'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s); })();</script>
				</div>
				</div><!-- /.col-lg-4 -->
			</div><!-- /.row -->
			
			<div class="row">
				<div class="col-lg-4">
				</div>
				<div class="col-lg-4">
<!--
					<div class="social text-center clearfix">
					<a href="#"><i class="fa fa-lg fa-facebook"></i></a>
					<a href="#"><i class="fa fa-lg fa-twitter"></i></a>
					<a href="#"><i class="fa fa-lg fa-pinterest"></i></a>
					<a href="#"><i class="fa fa-lg fa-google-plus"></i></a>
					<a href="#"><i class="fa fa-lg fa-instagram"></i></a>
					<a href="#"><i class="fa fa-lg fa-youtube"></i></a>
				</div>
-->
				</div>
				<div class="col-lg-4">
					<span class="pull-right">POWERED BY</span><br/>
					<a href="http://www.theatreprofile.com" target="_new" class="pull-right"><img src="<?php echo yii::app()->getBaseUrl(true).'/images/tp_logo.png'; ?>" /></a>
				</div>
			</div>
		</div>
	</footer>
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Bootstrap framework Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity ="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>