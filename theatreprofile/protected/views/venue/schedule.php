<?php
$pageTitle="Event Schedule -".$model->venueName." - Venue - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('Venues'=>array('/venue'),$model->venueName=>$model->createUrl(),'Event Schedule');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/fullcalendar.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/moment.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/fullcalendar.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:250px;height:150px;}
.modal-body{max-height:800px}
.fc-event-container{cursor:pointer;}
.dropdown-menu{width:inherit;text-align:left;}
.dropdown-menu>li>div{padding: 3px 20px;}"
)
?>
<div class="row-fluid">
	<div class="media">
		<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=4 AND profileID='.$model->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w250h150.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);	
			Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
		}
		else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_250x150.gif';
		}
		?>
		<div class="pull-left text-center">
			<div class="pnl-profile-pic">
				<a href ="<?php echo $model->createUrl(); ?>"><img class="media-object" src="<?php echo $image_url; ?>" width="250px" height="150px" alt="" /></a>
			</div>
		</div>
	
		<div class="media-body">
			<div class="media-heading"><h1 class="inline"><a href ="<?php echo $model->createUrl(); ?>"><?php echo $model->venueName; ?></a></h1></div>
			<ul class="unstyled">
				<?php
					echo '<li><span>'.$model->address->addr1.'</span></li>';
					echo '<li><span>'.$model->address->city.', '.$model->address->state.', '.$model->address->zip.'</span></li>';
					echo '<li><span>'.$model->address->country->countryName.'</span></li>';
				?>
			</ul>
		</div>
	</div>
	<?php
	echo '<p class="clear">'.$model->descr.'</p>';
	?>
</div>

<div class="row-fluid">
	<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('productionevent/create').'/'.$model->id; ?>"\>Add Event Details</a>
	<div id="calendar">
	</div>
</diV>


<div id="eventInfo" class="modal fade hide">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
                <h4 id="eventTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
				<div class="media">
					<div class="pull-left text-center">
						<img class="media-object" id="eventImage" width="140px" height="220px" alt="" />
					</div>
					<div class="media-body">
						<ul class="unstyled">
							<li id="eventDescription"></li>
							<li><span><strong>Start: </strong></span><span id="eventStartTime"></span></li>
							<li><span><strong>Duration: </strong></span><span id="eventDuration"></span></li>
							<li><span><strong>Intermissions: </strong></span><span id="eventIntermissions"></span></li>
							<li><a class='btn btn-primary' id='eventUrl'>More Info</a> <span id="eventTickets"></span><li>
						</ul>
					</div>
				</div>
			</div>
            <div class="modal-footer">
			<?php
				if(empty($model->venueownerships))
					echo "<a class='btn btn-primary' id='eventEditUrl'>Edit event</a>";
				else
				{
					$Venueownership = Venueownership::model()->find("venueID=:venueID and userID=:userID",array(':venueID'=>$model->id,':userID'=>Yii::app()->user->id));
					$Eventlistingservices = Eventlistingservice::model()->findAll('submitType=1');
					echo '<div class="dropdown inline-block" id="eventShare"><a class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Share <span class="caret"></span></a>';
					echo '<ul class="dropdown-menu" role="menu">';
					foreach($Eventlistingservices as $Eventlistingservice)
					{
						echo '<li><a role="menu-item" href="#" data-elsid="'.$Eventlistingservice->id.'"><i class="icon-share"></i> '.$Eventlistingservice->name.'</a><div class="request-status"></div></li>';
					}
					echo '</ul></div> <a class="btn btn-primary" id="eventEditUrl">Edit event</a>';
				}
			?>
                <a class="btn btn-default" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>


<?php Yii::app()->clientScript->registerScript('scheduleScript',
<<<JS
$('#calendar').fullCalendar({
	editable: false,
	timezone: false,
	eventLimit: true,
	eventSources: cfg.venueBaseUrl+'/getevents/'+cfg.modelID,
	eventClick:  function(event, jsEvent, view) {		
		$('#eventTitle').html(event.title);
		$('#eventDescription').html(event.description==''?'Information for this event is not available.':event.description);
		$('#eventStartTime').html(moment(event.start).format('MMM Do YYYY, h:mm:ss a'));
		//$('#eventEndTime').html(moment(event.end).format('MMM Do YYYY, h:mm:ss a'));
		$('#eventDuration').html(event.eventDuration==''?'Information for this event is not available.':event.eventDuration);
		$('#eventIntermissions').html(event.eventIntermissions==''?'Information for this event is not available.':event.eventIntermissions);
		$('#eventImage').attr('src',event.imageUrl);
		if(event.eventUrl!='')
			$('#eventUrl').attr('href',event.eventUrl);
		else
			$('#eventUrl').attr('class','btn btn-primary disabled');
		if(event.eventEditUrl!='')
			$('#eventEditUrl').attr('href',event.eventEditUrl);
		else
			$('#eventEditUrl').attr('class','.btn btn-primary disabled');
		$('#eventTickets').html("");
		event.ticketLinks.forEach(function(link) {
			$('#eventTickets').append(' <a class="btn btn-danger" target="_blank" href="'+link.href+'">'+link.label+'</a>');
		});
		$('#eventInfo').attr("data-id",event.id).modal();
		return false;
	},
});
$('#eventSchedule').on('shown.bs.modal', function () {
   $("#calendar").fullCalendar('render');
});
$(document).on('click', '#eventShare .dropdown-menu li a', function(event){
	var e = $(this);
	e.parent().addClass("disabled");
	$.ajax({
		url: cfg.baseUrl+'/productionevent/submiteventinfo/'+$('#eventInfo').attr("data-id")+'?elsid='+e.attr("data-elsid"),
		method: 'POST',
		data: 'YII_CSRF_TOKEN='+cfg.csrfToken,
	})
	.done(function(data, textStatus, jqXHR) {
		e.next().html("<i class='icon-ok-circle'></i>"+data);
		setTimeout(function() {
			e.next().html("");
		}, 3000);
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		e.next().html("<i class='icon-exclamation-sign'></i>"+jqXHR.responseText);
		setTimeout(function() {
			e.next().html("");
		}, 3000);
	})
	.always(function() {
		e.parent().removeClass("disabled");
		e.blur();
	});
	return false;
});
JS
, CClientScript::POS_READY);
?>