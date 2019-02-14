<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
//$root=Yii::getPathOfAlias('webroot');
//$js=Yii::app()->assetManager->publish($root.'/protected/views/site/index.js');
//$css=Yii::app()->assetManager->publish($root.'/protected/views/site/index.css');
//Yii::app()->clientScript->registerScriptFile($js,CClientScript::POS_READY);
Yii::app()->clientScript->registerCss("viewStyle","
.header {border-bottom: 1px solid #ccc;}
#ticker {height:300px; overflow-y:auto;padding:0;margin:0;}
#ticker a{color:inherit;text-decoration:none;}
#ticker li{border-bottom: 1px solid #ccc;padding:5px;margin:0px; cursor:pointer;}
.newsDescr{padding:5px 0px;}
li:hover {background-color:#fafafa;}
.vertical-scroll{overflow-y:auto;}
.block-list .items .thumbnails {margin:0px}
.block-list .items .thumbnails li {float:none;margin:2px}
.productionList{height:300px;}
.productionList .list-view{padding-top:0px;}
.productionList .nav{margin-bottom:10px;}
.fringeproducutionlist .caption {max-height:964px;overflow-y:scroll};
");
Yii::app()->clientScript->registerMetaTag(Yii::app()->name, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag('Theatre Profile is a social website for everyone interested in theatre and the professional theatrical community.', null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/logo_522X203.png', null, null, array('property' => "og:image"));
?>

	
	
<?php
if(Yii::app()->user->isGuest)
{
	//$this->widget('bootstrap.widgets.TbCarousel', array('items'=>$items));
?>
<br />

<div class="row-fluid" class="fringeprodcutionlist">
	<div class="span9">
		<div class="thumbnail">
			<div class="caption">
				<h1>Theatre Profile is</h1>
				<p>
					An intelligent platform for Theatres and their audiences.<br /><br />
Use the search bar above and search our knowledge base.  We are a community contributed platform so create an account and let us know about things we might not yet have in our system.<br /><br />

If you are a theatre administrator create an account and start learning more about your audience and how we can help you grow.<br /><br />To learn more about the tool Theatre Profile is developing click <a target="_blank" href="https://g3kelly.wixsite.com/theatreprofile">here</a>.
				</p>
			</div>
		</div>
	</div>
	<div class="span3">
		<div class="thumbnail">
		<?php
			$this->widget('application.modules.user.widgets.LoginWidget');
		?>
		</div>
	</div>
</div>

<br />

<?php
}
else
{
?>
<br />
<div class="row-fluid">
	<h2>Welcome, <?php echo Yii::app()->getModule('user')->user()->profile->getAttribute('firstname'); ?><small><a class="pull-right" href="<?php echo yii::app()->createUrl('/user/profile'); ?>">Account settings</a></small></h2>
	<div class="thumbnail">
		<div class="header">
			<h3>My Watchlist<a class="btn btn-link pull-right" href="<?php echo yii::app()->createUrl('/profileownership'); ?>">View Owned Profiles</a></h3>
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
			
			$criteria5 = new CDbCriteria();
			$criteria5->condition = 'userID=:userID';
			$criteria5->params = array(':userID'=>Yii::app()->user->id);
			$criteria5->order="companyName";
			$criteria5->with=array('company');
			$dataProvider5 = new CActiveDataProvider('Companywatchlist',array('criteria'=>$criteria5,));
				
			//$this->render('index',array('dataProvider1'=>$dataProvider1,'dataProvider2'=>$dataProvider2,'dataProvider3'=>$dataProvider3,'dataProvider4'=>$dataProvider4));
		
			if($dataProvider1->totalItemCount+$dataProvider2->totalItemCount+$dataProvider3->totalItemCount+$dataProvider4->totalItemCount+$dataProvider5->totalItemCount==0)
			echo '<div>No items in your watchlist.</div>';
			else
			{
			?>
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
				<div class="tab-pane" id="companies">
				<?php
				if($dataProvider5->totalItemCount>0)
				{
					$this->widget('bootstrap.widgets.TbThumbnails', array(
					'dataProvider'=>$dataProvider5,
					'template'=>"{items}",
					'itemView'=>'_index5',
					'htmlOptions'=>array("class"=>"block-list list-view"),
					));
				}
				?>
				</div>
			</div>
		<?php
			}
		}
		?>
		</div>
	</div>
</div>
<br />
<?php 
}
?>

<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(window).load(function() {
	var max;
	$('.row-fluid, .row').each(function() {
		max = -1;
		$(this).find("[class$='thumbnail']").each(function() {
			var h = $(this).height(); 
			max = h > max ? h : max;
		});
		
		$(this).find("[class$='thumbnail']").each(function() {
			$(this).css({'min-height': max});
		});
	});
});
JS
, CClientScript::POS_READY);
?>