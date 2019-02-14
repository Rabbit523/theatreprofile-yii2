<?php
/* @var $this SiteController */

$this->pageTitle='Pilot Programs - '.Yii::app()->name ;
$this->breadcrumbs=array(
	'Pilot Programs',
);
?>
<style type="text/css">

</style>

<h1>Join one of the Theatre Profile Pilot Programs and be heard!</h1>

<p>Our Pilot Programs are designed to hear from our users and develop tools around the feedback we receive.  Programs are broken down into groups with similar goals.  Anyone participant will have first, and free, access to the tools developed.  Read about the Pilot Programs on offer and let use know if you would like to participate, its free but space is limited.</p>


<div class="row-fluid">
	<div class="thumbnails">
		<div class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h4>Actors & Theatre Professionals</h4>
					<p>
						Designed around the needs of the Theatre Professional, on or off stage.  In this program we aim to create tools for individuals look to advance their career or just keep tabs on what is going on in the industry.
					</p>
					<br />
					<br />
					<p>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/documents/Actor-TheatreProfessionals.pdf'; ?>">Learn more</a>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/Actor-TheatreProfessionals-Form'; ?>">Apply</a>
					</p>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h4>Consumers</h4>
					<p>
						Anyone who attends any production is a consumer and almost everyone falls into this group.   The largest of the Pilot Groups will focus on making Theatre Profile easy to use and content easy to find.  As well, we will work to develop ways to stay connected with what you like and recommend new shows you might enjoy.
					</p>
					<p>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/documents/Consumer-Pilot-Program.pdf'; ?>">Learn more</a>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'Consumer-Pilot-Program-Form'; ?>">Apply</a>
					</p>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h4>Education Organizations</h4>
					<p>
						If you are a school, conservatory or a teacher then this group is right for you.  The focus will be on expanding education of your art form, if it is reaching new students, communicating with current students or tracking your students after they have moved on.
					</p>
					<br />
					<br />
					<p>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/documents/Education-Pilot-Program.pdf'; ?>">Learn more</a>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/Education-Application-Form'; ?>">Apply</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<br />
<div class="row-fluid">
	<div class="thumbnails">
		<div class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h4>Producing & Marketing</h4>
					<p>
						We have combined these two groups to provide an overall view of a production’s potential and reach.  Focusing on tools that educate you about your audience and using that information to grow and reach new audiences.<br /><br />

						*This Pilot Program has already begun and we are only accepting a very limited number of new participants.  
					</p>
					<p>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/documents/Theatre-Production.pdf'; ?>">Learn more</a>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/Theatre-Form'; ?>">Apply</a>
					</p>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h4>Venues</h4>
					<p>
						Anything from a Theatre to a space in the back of a pub.  We are going to work to create tools that help Venues increase their visibility, communicate with their audience and do anything else they need.
					</p>
					<br />
					<br />
					<br />
					<br />
					<br />
					<p>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/documents/Venue-Pilot-Program.pdf'; ?>">Learn more</a>
						<a class="btn btn-primary" target="_blank" href="<?php echo Yii::app()->request->baseUrl.'/Venue-Pilot-Program-Form'; ?>">Apply</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php Yii::app()->clientScript->registerScript('viewScript',
<<<JS
$(window).load(function() {
	var max;
	$('.row-fluid,.row').each(function() {
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