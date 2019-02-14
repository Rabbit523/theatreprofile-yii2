<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbCarousel', array('items'=>$items));

$baseUrl = Yii::app()->baseUrl; 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/index.js');
?>
<style type="text/css">
.header {border-bottom: 1px solid #ccc;}
#ticker {height:250px; overflow-y:auto;padding:0;margin:0;}
#ticker a{color:inherit;text-decoration:none;}
#ticker li{border-bottom: 1px solid #ccc;padding:5px;margin:0px; cursor:pointer;}
.newsDescr{padding:5px 0px;}
li:hover {background-color:#fafafa;}
.vertical-scroll{overflow-y:auto;}
.block-list .items .thumbnails {margin:0px}
.block-list .items .thumbnails li {float:none;margin:2px}
.productionList,.watchList{height:300px;}
.watchList .list-view{padding-top:0px;}
.watchList .nav{margin-bottom:10px;}
.productionList .list-view{padding-top:0px;}
.productionList .nav{margin-bottom:10px;}
</style>

<div class="row-fluid">
	<div class="thumbnails">
		<div class="span6">
			<div class="thumbnail">
				<div class="caption">
					<h1>Theatre Profile <small>BETA version 1.0</small></h1>
					<p>Here&rsquo;s what we offer: information on past, current and planned productions and shows, networking opportunities for all theatre professionals, opportunity to create personal pages, and the latest information about what theatre professionals are doing.</p>
					<p>Our website is for everyone: whether you are a theatre professional or someone interested in theatre, so please sign up! It&rsquo;s free!</p>
					<p>We cannot build Theatre Profile without your help – please give us your info! Whether you are in community theatre or large scale productions, a professional actor or you do it for fun, we would love to have you on our site. We will let the world know about your shows and productions! Email us, <a href="mailto:info@theatreprofile.com">info@TheatreProfile.com</a>, with the information and we will get it posted as fast as we can.</p>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="thumbnail">	
				<div class="header">		
					<h3>News & Updates<a class="btn btn-link pull-right" href="<?php echo Yii::app()->getBaseUrl(); ?>/news">View All</a></h3>
				</div>
				<ul id="ticker">
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
				$criteria = new CDbCriteria;
				$criteria->order = 'publishDate desc';
				$criteria->limit=10;
				$feeditems = Feeditem::model()->with('feed')->findAll($criteria);
				foreach($feeditems as $feeditem)
				{
					echo '<li><a href="'.Yii::app()->getBaseUrl().'/news/'.$feeditem->id.'"><div><strong>'.$feeditem->title.'</strong></div><div><small class="muted">'.formatDateDiff(new DateTime($feeditem->publishDate)).', '.$feeditem->feed->name.'</small></div><div class="newsDescr">'.strip_tags(CHtml::decode($feeditem->descr)).'</div></a></li>';
				}
				?>
				</ul>
			</div>
		</div>
	</div>
</div>

<br />

<div class="row">
	<div class="span12">
		<div class="thumbnail">
			<div class="caption">
				<h3>Become an Editor and Contribute </h3>
				<p>Help us by becoming an editor and content provider. We are granting a limited number of editor privileges to those who love theatre and are  knowledgeable in the field. We will expand the editing as we grow and soon everyone will be able to edit but, for now apply to be one! After creating your account, email <a href="mailto:info@TheatreProfile.com">info@TheatreProfile.com</a> to explain why you would be a good content provider and editor. Don&rsquo;t forget to let us know the email you used to register on the site so we can activate your editing capabilities quickly.
				</p>
			</div>
		</div>
	</div>
</div>

<br />

<div class="row-fluid">
	<div class="thumbnails">
		<div class="span4">
			<div class="thumbnail">
				<a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/production/1083"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/360x200/Charming_360x200.jpg"/></a>
				<div class="caption">
					<a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/production/1083"><strong>Charming: A Take of an American Prince</strong></a><br>
					<br>
                    In his one-man show Shuford, according to press notes, "tells the tale of one Prince's trek from the faraway Kingdom of Texas to a castle in The East Village. Shuford's quest is highlighted by the music of Sondheim, Lutvak, Prince and more, with a little Disney magic thrown in for good measure. Friendship bracelets, giants and maybe even a furry woodland creature help guide this Prince along the way. Will he get his 'Happily Ever After’?".<br><br>
                    Now on <a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/production/1083">tour</a>.
				</div>
			</div>
		</div>
		
		<div class="span4">
			<div class="thumbnail">
				<a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/people/2593"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/360x200/RichardShows.jpg" /></a>
				<div class="caption"><a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/people/2593"><strong>Richard Weeden</strong></a><br><br>
                    Richard trained in the UK as a classical pianist with Dr Anne Holmes MBE. He gained diplomas from the Trinity College of Music and the London College of Music and became a professional freelance musician, performing worldwide at the age of 16. He is presently Musical Director on Dirty Dancing UK Tour and is a Director of UK Music Management Ltd., managing artists such as the Titanium String Quartet.<br><br>
                     <a href="<?php echo Yii::app()->getBaseUrl(); ?>/index.php/people/2593">More</a>.
				</div>
			
			</div>
		</div>
		
		<div class="span4">
			<div class="thumbnail">
				<img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/360x200/You.jpg" />
				<div class="caption">Every aspect of theatre is important to us and <strong>you</strong> most of all. Let the world know what you are doing and what you know about theatre. Create an account, become part of the community and contribute. While we are BETA testing we are limiting the number of contributors and editors. Contact us at <a href="mailto:info@TheatreProfile.com">info@TheatreProfile.com</a> if you are interested in adding or editing content.</div>
			</div>
		</div>
	</div>
</div>
<br />
<div class="row-fluid">
	<div class="thumbnails">
		<div class="span6">
			<div class="thumbnail">
				<div class="header">
					<h3>Top Rated Productions<a class="btn btn-link pull-right" href="<?php echo yii::app()->createUrl('/production'); ?>">View All</a></h3>
				</div>
				<div class="productionList vertical-scroll">
					<div class="block-list list-view" id="yw1">
						<div class="items">
							<ul class="thumbnails">
								<?php
								$data = Yii::app()->db->createCommand('SELECT productionID FROM tbl_productionrating GROUP by productionID ORDER BY AVG(rating)desc,COUNT(*) desc limit 10')->queryAll();
								foreach($data as $data_obj)
								{
									//print_r($data_obj["productionID"]);
									$production=Production::model()->findByPK($data_obj["productionID"])->with('show,productionvenue');
								?>
									<li >
										<div class="media">
											<a href="<?php echo yii::app()->createUrl('/production/view',array('id'=>$production->id)) ?>" class="pull-left">
												<?php
												$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$production->id);
												if(isset($profile_image->image->imageURL))
												{
													$image_url=yii::app()->request->baseUrl.'/images/uploads/'.$profile_image->image->imageURL;
												}else
												{
													$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$production->showID);
													if(isset($profile_image->image->imageURL))
													{
														$image_url=yii::app()->request->baseUrl.'/images/uploads/'.$profile_image->image->imageURL;
													}
													else
													{					
														$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
													}
												}
												?>

												<img class="media-object" src="<?php echo $image_url; ?>" style="height:44px;width:28px" alt="">
											</a>
											
											<div class="media-body">
												<div class="media-heading text-overflow-hide">
													<?php
														$venueInfo='';
														if(count($production->productionvenues)==1)
														{
															$venues=array_values($production->productionvenues)[0];
															$venueInfo = $venues->venue->venueName.', '.$venues->venue->address->city.', '.$venues->venue->address->state.', '.$venues->venue->address->country->countryName;
														}
														else if(count($production->productionvenues)>1)
														{
															$venueInfo = 'Multiple venues';
														}
														else
														{
															$venueInfo = 'Venue not available';
														}
													?>
												
													<a href="<?php echo yii::app()->createUrl('/production/view',array('id'=>$production->id)) ?>">
														<?php echo $production->show->showName.' - '.(!empty($production->productionName)?$production->productionName:$venueInfo).'<br />';														//$this->widget('CStarRating',array('name'=>'rating'.$production->id,'id'=>'rating'.$production->id,'readOnly'=>true,'starCount'=>5,'ratingStepSize'=>0.5,'minRating' => 0.5,'maxRating' => 5,'value'=>$production->avgrating,));
														echo "Rating: <span class='badge badge-important' rel='tooltip' title='Total ratings: ".$production->ratingcount."' data-placement='right' data-trigger='hover'><strong>".$production->avgrating."</strong></span>";						
														?>
													</a>
												</div>
											</div>
										</div>
									</li>
								<?php
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="thumbnail">
				<div class="header">
					<h3>My Watchlist<a class="btn btn-link pull-right" href="<?php echo yii::app()->createUrl('/watchlist'); ?>">View All</a></h3>
				</div>
				<div class="watchList vertical-scroll">
				<?php
				if(!Yii::app()->user->isGuest)
				{
					$criteria1 = new CDbCriteria();
					$criteria1->condition = 'userID=:userID';
					$criteria1->params = array(':userID'=>Yii::app()->user->id);
					$criteria1->order="showName";
					$criteria1->with=array('show');
					$dataProvider1 = new CActiveDataProvider('Showwatchlist',array('criteria'=>$criteria1,));		
					
					$criteria2 = new CDbCriteria();
					$criteria2->condition = 'userID=:userID';
					$criteria2->params = array(':userID'=>Yii::app()->user->id);
					$criteria2->with=array('production.show');
					$criteria2->order="showName";
					$dataProvider2 = new CActiveDataProvider('Productionwatchlist',array('criteria'=>$criteria2,));
					
					$criteria3 = new CDbCriteria();
					$criteria3->condition = 'userID=:userID';
					$criteria3->params = array(':userID'=>Yii::app()->user->id);
					$criteria3->order="firstName";
					$criteria3->with=array('individual');
					$dataProvider3 = new CActiveDataProvider('Individualwatchlist',array('criteria'=>$criteria3,));
					
					$criteria4 = new CDbCriteria();
					$criteria4->condition = 'userID=:userID';
					$criteria4->params = array(':userID'=>Yii::app()->user->id);
					$criteria4->order="venueName";
					$criteria4->with=array('venue');
					$dataProvider4 = new CActiveDataProvider('Venuewatchlist',array('criteria'=>$criteria4,));
						
					//$this->render('index',array('dataProvider1'=>$dataProvider1,'dataProvider2'=>$dataProvider2,'dataProvider3'=>$dataProvider3,'dataProvider4'=>$dataProvider4));
				
					if($dataProvider1->totalItemCount+$dataProvider2->totalItemCount+$dataProvider3->totalItemCount+$dataProvider4->totalItemCount==0)
					echo '<div>No items in your watchlist.</div>';
					else
					{
					?>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#shows" data-toggle="tab">Shows</a></li>
						<li><a href="#productions" data-toggle="tab">Productions</a></li>
						<li><a href="#people" data-toggle="tab">People</a></li>
						<li><a href="#venues" data-toggle="tab">Venues</a></li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="shows">
						<?php
						if($dataProvider1->totalItemCount>0)
						{
							$this->widget('bootstrap.widgets.TbThumbnails', array(
							'dataProvider'=>$dataProvider1,
							'template'=>"{items}",
							'itemView'=>'_index1',
							'htmlOptions'=>array("class"=>"block-list list-view"),
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
							'template'=>"{items}",
							'itemView'=>'_index2',
							'htmlOptions'=>array("class"=>"block-list list-view"),
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
							'template'=>"{items}",
							'itemView'=>'_index3',
							'htmlOptions'=>array("class"=>"block-list list-view"),
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
							'template'=>"{items}",
							'itemView'=>'_index4',
							'htmlOptions'=>array("class"=>"block-list list-view"),
							));
						}
						?>
						</div>
					</div>
				<?php
					}
				}
				else
				{
					echo '<div class="well">You need to be logged in to view your watchlist.</div>';
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>
			
			