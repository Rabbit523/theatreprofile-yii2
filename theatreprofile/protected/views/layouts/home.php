<?php
	header('Content-type: text/html; charset=utf-8');
	$cookie_name = 'theatreprofile-cookie-alert';
	$cookie_duration = 1000;
	
	/* Check if allow-cookies is set in the URL */
	if (isset($_GET['allow-cookies']) && $_GET['allow-cookies'] == 1):
	
		/* Set a cookie for 1,000 days to prevent the notice from showing */
		setcookie($cookie_name, true, time() + (86400 * (!empty($cookie_duration) ? $cookie_duration : 1000)), '/');
		
		/* Return to the previous page */
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	endif;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
	<meta name="language" content="en" />
	<meta name="keywords" content="theatre,broadway,shows,productions,actors,actresses,venues,database" />
	<?php
		//Yii::app()->clientScript->registerMetaTag("1444685225816091", null, null, array('property' => "fb:app_id"));
		Yii::app()->clientScript->registerMetaTag("website", null, null, array('property' => "og:type"));
		Yii::app()->clientScript->registerMetaTag("Theatre Profile", null, null, array('property' => "og:site_name"));
		Yii::app()->clientScript->registerMetaTag(Yii::app()->getBaseUrl(true).Yii::app()->request->getUrl(),null, null, array('property' => "og:url"));
		//Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/logo_250X250.jpg', null, null, array('property' => "og:image"));
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/app.css');
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/form.css');
		//<!-- Bootstrap framework -->
		echo Yii::app()->bootstrap->register();
		Yii::app()->clientScript->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/main.css');
		//<!-- Yiistrap framework -->
		//echo Yii::app()->yiistrap->register();
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/main.js',CClientScript::POS_END);
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootbox.min.js',CClientScript::POS_END);		
	?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php 
		//<!-- AddThis Pro BEGIN -->
		if(!YII_DEBUG) Yii::app()->clientScript->registerScriptFile('https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52ea6239668184dc',CClientScript::POS_END);
		//<!-- AddThis Pro END -->
	?>
<script type="text/javascript">
var cfg = {displayCookieAlert: <?php echo isset($_COOKIE[$cookie_name])? 0:1; ?>,cookieName: '<?= $cookie_name ?>',timeout:null,searchBaseUrl:'<?php echo yii::app()->createUrl('/search'); ?>',baseUrl:'<?php echo yii::app()->request->baseUrl; ?>',showBaseUrl:'<?php echo yii::app()->createUrl('/show'); ?>',productionBaseUrl:'<?php echo yii::app()->createUrl('/production'); ?>',peopleBaseUrl:'<?php echo yii::app()->createUrl('/people'); ?>',venueBaseUrl:'<?php echo yii::app()->createUrl('/venue'); ?>',companyBaseUrl:'<?php echo yii::app()->createUrl('/company'); ?>',DEBUG:<?php echo YII_DEBUG?1:0; ?>,userID:<?php echo isset(Yii::app()->user->id)? Yii::app()->user->id:0; ?>,csrfToken:'<?php echo Yii::app()->request->csrfToken; ?>',};
</script>
<script type='text/javascript'>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
  (function() {
    var gads = document.createElement('script');
    gads.async = true;
    gads.type = 'text/javascript';
    var useSSL = 'https:' == document.location.protocol;
    gads.src = (useSSL ? 'https:' : 'http:') +
      '//www.googletagservices.com/tag/js/gpt.js';
    var node = document.getElementsByTagName('script')[0];
    node.parentNode.insertBefore(gads, node);
  })();
</script>

<script type='text/javascript'>
  googletag.cmd.push(function() {
    googletag.defineSlot('/36455387/TP_Companies_160x600', [160, 600], 'div-gpt-ad-1452790578742-0').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Footer_970x90', [[728, 90], [970, 90], [468, 60]], 'div-gpt-ad-1452790578742-1').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Header_970x90', [[728, 90], [970, 90]], 'div-gpt-ad-1452790578742-2').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_MobileLeader_320x50', [320, 50], 'div-gpt-ad-1452790578742-3').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Mobile_Footr', [320, 50], 'div-gpt-ad-1452790578742-4').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_People_160x600', [160, 600], 'div-gpt-ad-1452790578742-5').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Productions_160x600', [160, 600], 'div-gpt-ad-1452790578742-6').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Shows_160x600', [160, 600], 'div-gpt-ad-1452790578742-7').addService(googletag.pubads());
    googletag.defineSlot('/36455387/TP_Venues_160x600', [160, 600], 'div-gpt-ad-1452790578742-8').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>
</head>
<body itemscope itemtype="http://schema.org/WebSite">
	<!-- header -->
	<div id="header">
		<!-- mainmenu -->
		<?php
		$reportAccess=false;
		if(!Yii::app()->user->isGuest)
		{	
			$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
			foreach($roles as $role) if($role->name == 'ReportUser') $reportAccess=true;
		}
		$this->widget('bootstrap.widgets.TbNavbar', array(
		'collapse'=>true, // requires bootstrap-responsive.css
		'fixed'=> 'top',
		'fluid' => true,
		//'brand' => 'TheaterProfile',
		'htmlOptions'=>array('id'=>'navbar',),
		'brand'=>CHtml::image(Yii::app()->getBaseUrl().'/images/logo.png','',array("width"=>"150px" ,"height"=>"59px","alt"=>"Theatre Profile")),
		'items'=>array(
			'<form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" class="navbar-search" action="'.yii::app()->createUrl('/search').'">
			<meta itemprop="target" content="http://www.theatreprofile.com/search?term={term}"/>
			<div class="input-append"><input itemprop="query-input" class="search-box span8 input-medium" placeholder="Search" autocomplete="off" type="text" id="term" name="term" /><span id="btnSearch" class="btn add-on"><i class="icon-search"></i></span></div></form>',
			'<div class="span9 menu-line2">',
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				'htmlOptions'=>array('class'=>'pull-left'),
				'items'=>array(
					//array('label'=>'Home', 'url'=>Yii::app()->getBaseUrl().'/'),
					array('label'=>'Shows', 'url'=>array('/show'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Shows', 'url'=>'#','items'=>array(
						array('label'=>'View all shows', 'url'=>array('/show')),
						array('label'=>'Create new show', 'url'=>array('/show/create')),
					), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Productions', 'url'=>array('/production'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Productions', 'url'=>'#','items'=>array(
						array('label'=>'View all productions', 'url'=>array('/production')),
						array('label'=>'Create new production', 'url'=>array('/production/create')),
					), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'People', 'url'=>array('/people'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'People', 'url'=>'#','items'=>array(
						array('label'=>'View all people', 'url'=>array('/people')),
						array('label'=>'Create new profile', 'url'=>array('/people/create')),
					), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Venues', 'url'=>array('/venue'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Venues', 'url'=>'#','items'=>array(
						array('label'=>'View all venues', 'url'=>array('/venue')),
						array('label'=>'Create new venue', 'url'=>array('/venue/create')),
					), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Companies', 'url'=>array('/company'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Companies', 'url'=>'#','items'=>array(
						array('label'=>'View all companies', 'url'=>array('/company')),
						array('label'=>'Create new company', 'url'=>array('/company/create')),
					), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'News', 'url'=> yii::app()->createUrl('news')),
					array('label'=>'Social', 'url'=> yii::app()->createUrl('social')),
					array('label'=>'About', 'url'=> "https://g3kelly.wixsite.com/theatreprofile/about"),
					//array('label'=>'Grow with us', 'url'=>array('/site/page', 'view'=>'grow')),
					//array('label'=>'Contact', 'url'=>array('/site/contact'))
				),
			),
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				//'htmlOptions'=>array('class'=>'pull-right'),
				'items'=>array(
					array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Register', 'url'=>array('/user/registration'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>Yii::app()->user->name, 'url'=>'#', 'items'=>array(
						array('label'=>'Account', 'url'=>array('/user/profile')),
						//array('label'=>'Reports', 'url'=>array('/launchreportingdashboard'),'visible'=>$reportAccess||Rights::getAuthorizer()->isSuperUser(Yii::app()->user->Id)),
						array('label'=>'Reports','url'=>'#','itemOptions'=>array('id'=>'launchReports'),'visible'=>$reportAccess||Rights::getAuthorizer()->isSuperUser(Yii::app()->user->Id)),
						array('label'=>'My Watchlist', 'url'=>array('/watchlist')),
						array('label'=>'Profiles I Own', 'url'=>array('/profileownership')),
						array('label'=>'Administrator', 'url'=>array('/site/administrator'),'visible'=>Rights::getAuthorizer()->isSuperUser(Yii::app()->user->Id)),
						array('label'=>'Logout', 'url'=>array('/user/logout')),
					),'visible'=>!Yii::app()->user->isGuest)
					//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
			),
			'</div>',
			'<div class="addthis_horizontal_follow_toolbox"></div>'
		),
		));
		?>
	</div>
	<div id="page">
		<div class="container">
			<?php if(!YII_DEBUG): ?>
				<div class="adspace hidden-phone">
					<div id='div-gpt-ad-1452790578742-2'>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-2'); });
						</script>
					</div>
				</div>
				<div class="adspace visible-phone">
					<div id='div-gpt-ad-1452790578742-3' style='height:50px; width:320px;'>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-3'); });
						</script>
					</div>
				</div>
			<?php endif; ?>
			<?php if(isset($this->breadcrumbs))
			{
				//$this->widget('zii.widgets.CBreadcrumbs', array('links'=>$this->breadcrumbs,));
				$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links'=>$this->breadcrumbs,));
			}
			?>
			<div id="flash">
				<?php echo $this->renderPartial('//layouts/_flashes'); ?>
			</div>
			<?php echo $content; ?>
		</div>
	</div>
	<div id="footer">
			<div class="addthis_horizontal_follow_toolbox"></div>
			<?php if(!YII_DEBUG): ?>
			<div class="adspace hidden-phone">
				<div id='div-gpt-ad-1452790578742-1'>
					<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-1'); });
					</script>
				</div>
			</div>
			<div class="adspace visible-phone">
				<div id='div-gpt-ad-1452790578742-4' style='height:50px; width:320px;'>
					<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1452790578742-4'); });
					</script>
				</div>
			</div>
			<?php endif; ?>
			<a href="<?php echo yii::app()->createUrl('terms'); ?>">Terms of use</a> | <a href="<?php echo yii::app()->createUrl('privacy'); ?>">Privacy Policy</a> | <a href="<?php echo yii::app()->createUrl('contact'); ?>">Contact us</a> | <a href="https://g3kelly.wixsite.com/theatreprofile" target="_blank">Learn more</a> | <a href="https://confirmsubscription.com/h/d/37A6B58F80965DBD" target="_blank">Join The Newsletter</a> | <a href="mailto:info@theatreprofile.com">Report a problem</a>
			<br /><br />
			Copyright &copy; <?php echo date('Y'); ?> by Theatre Profile.<br/>
			All Rights Reserved.
	</div><!-- footer -->
	
	<?php if (!isset($_COOKIE[$cookie_name])): /* Only load if cookie isn't already set */ ?>
	<div id="cookie-alert-container">
		<span>
			We use cookies to give you the best website experience. By using our website you agree to our use of cookies -
			<a href="<?php echo yii::app()->createUrl('privacy#cookiePolicy'); ?>">Find out more</a>
			<a href='#' id='accept-cookies'>Close</a>
		</span>
	</div>
	<?php endif;?>
</body>
<?php
	if(Yii::app()->user->hasState("launchReports"))
	{
		echo "<script>$(document).ready(function() {launchReports('".Yii::app()->user->getState("activeKey")."');});</script>";
		Yii::app()->user->setState("launchReports",null);
		Yii::app()->user->setState("activeKey",null);
	}
?>
</html>