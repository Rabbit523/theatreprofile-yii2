<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag(Yii::app()->getBaseUrl(true).'/images/logo.png', null, null, array('property' => "og:image"));
?>
<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
	<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
  </ol>
  <div class="carousel-inner" role="listbox">
	<div class="item active">
		<a target="_new" href="http://www.theatreprofile.com/production/9885/an-evening-with-betty-buckley-at-portland5-winningstad-theatre"><img src="<?php echo yii::app()->getBaseUrl(true).'/images/1.jpg'; ?>" /></a>
	</div>
  </div>
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	<span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
	<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	<span class="sr-only">Next</span>
  </a>
</div><!-- /.carousel -->

<div class="well well-sm text-center" style="background-color:#637579;color:white;border:0;">
	<strong>UPCOMING EVENTS</strong>
</div>
<div class="container" >
<?php
	$Productioncompanycrews=Productioncompanycrew::model()->findAll(array('condition'=>'companyID=141 and roleID in (1235,2058)'));
	$len = count($Productioncompanycrews);
	$i=0;
	foreach($Productioncompanycrews as $Productioncompanycrew)
	{
		$i++;
		$Production=$Productioncompanycrew->production;
if($Production->endDate>date("m-d-Y"))
{
		$Show=$Production->show;
		$Productionvenues = $Production->productionvenues;
		$venue_count = count($Productionvenues);
		$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$Production->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
			//Yii::app()->clientScript->registerMetaTag("140", null, null, array('property' => "og:image:width"));
			//Yii::app()->clientScript->registerMetaTag("220", null, null, array('property' => "og:image:height"));
		}
		else
		{
			$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$Show->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			}
			else
			{					
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/default/default_140x220.gif';
			}
		}
?>
	<div class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="">
			</a>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $Show->showName; ?>
			<br><small><?php echo ' '.$Production->productionName; ?></small>
			</h4>
			<?php
				if($Production->descr!='')
					echo $Production->descr;
				else
					echo $Show->showDesc;
			?>
			<br /><a target="_new" href="<?php echo Yii::app()->params['theatreprofileBaseUrl'].'/production/'.$Production->seo->params['id'].'/'.$Show->seo->params['title']; ?>" style="color:black;font-weight:700;border-bottom:1px dotted;">READ MORE</a><br /><br />
			<?php
echo $Production->startDate." to ".$Production->endDate."<br />";
			if($venue_count==1)
			{
				$Productionvenue = array_values($Productionvenues)[0];
				$Venue=$Productionvenue->venue;
				echo '<strong><a href="'.Yii::app()->params['theatreprofileBaseUrl'].'/venue/'.$Venue->seo->params['id'].'/'.$Venue->seo->params['title'].'">'.$Venue->venueName.'</a></strong><br />'.$Venue->address->addr1.', '.$Venue->address->city.', '.$Venue->address->state.', '.$Venue->address->country->countryName.'<br/><br/>';
				$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionvenue->id.' and linkType=1');						
				foreach($links as $link)
				{
					echo ' <a href="'.$link->href.'" target="_new" class="btn btn-large btn-danger">'.$link->label.'</a>';
				}
			}
			else if($venue_count>1)
			{
				echo '<a href="#">Multiple venues</a>';
			}
			else
			{
				echo 'Venue not available';
			}
			?>
			<a target="_new" href="<?php echo Yii::app()->params['theatreprofileBaseUrl'].'/production/'.$Production->seo->params['id'].'/'.$Show->seo->params['title']; ?>" class="btn btn-default pull-right">MORE ON THEATREPROFILE</a>
		</div>
		<?php if($i!=$len) echo '<hr class="divider-small">'; ?>
	</div>
<?php
}
	}
?>
</div>