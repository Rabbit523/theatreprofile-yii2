<?php
$venue_count = count($model->productionvenues);
if($venue_count==1)
{
	$venue = array_values($model->productionvenues)[0];
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' at '.$venue->venue->venueName=>$model->createUrl(),'Analytics');
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' at '.$venue->venue->venueName)." - Production - ".Yii::app()->name;
}
else if($venue_count>1)
{
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' - Multiple venues'=>$model->createUrl(),'Analytics');
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' - Multiple venues')." - Production - ".Yii::app()->name;
}
else
{
	$this->breadcrumbs=array('Shows'=>array('/show'),(is_numeric($model->show->showName)?' '.$model->show->showName:$model->show->showName)=>$model->show->createUrl(),!empty($model->productionName)?$model->productionName:$model->show->showName.' - Venue not available'=>$model->createUrl(),'Analytics');
	$pageTitle = (!empty($model->productionName)?$model->show->showName.' - '.$model->productionName:$model->show->showName.' - Venue not available')." - Production - ".Yii::app()->name;
}
$this->pageTitle='Analytics - '.$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag(($model->descr!=''?$model->descr:$model->show->showDesc), null, null, array('property' => "og:description"));
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:140px;height:220px;}"
);
Yii::app()->clientScript->registerScriptFile("https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}",CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="media">
		<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND imageType=1 AND profileID='.$model->id);
		$show=Show::model()->with('category')->with('showcreators')->findByPk($model->show->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
		}
		else
		{
			$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND imageType=1 AND profileID='.$show->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
				Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
			}
			else
			{					
				$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
			}
		}
		
		?>
		<div class="pull-left text-center">
			<div class="pnl-profile-pic">
				<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="" />
			</div>
		</div>
	
		<div class="media-body">
			<div class="media-heading"><h1 class="inline">
			<?php echo $show->showName; ?><small><?php echo ' '.$model->productionName; ?></small></h1>
			</div>
			<div class="row-fluid">
				<div class="span7">
					<div><?php echo $show->category->categoryName; ?></div>
					<ul class="unstyled">
					<?php
						$book ='';
						$music='';
						$lyrics='';
						$adaptation='';
						$translation='';
						$concept='';
						
						foreach($show->showcreators as $creator)
						{
							$name = $creator->individual->firstName.' '.$creator->individual->middleName.' '.$creator->individual->lastName.' '.$creator->individual->suffix;	
							switch($creator->role->roleName)
							{
								case "Book":$book = $book.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Lyrics":$lyrics = $lyrics.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Music":$music = $music.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Adaptation":$adaptation = $adaptation.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Translation":$translation = $translation.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								case "Concept":$concept = $concept.'<span itemscope itemtype="https://schema.org/Person"><a itemprop="url" href="'.$creator->individual->createUrl().'"><span class="itemprop" itemprop="name">'.trim($name).'</span></a></span>, ';break;
								default:break;
							}
						}
						if($book!='')echo '<li><strong>Book: </strong>'.substr($book, 0, -2).'</li>';
						if($music!='')echo '<li><strong>Music: </strong>'.substr($music,0,-2).'</li>';
						if($lyrics!='')echo '<li><strong>Lyrics: </strong>'.substr($lyrics,0,-2).'</li>';
						if($adaptation!='')echo '<li><strong>Adaptation: </strong>'.substr($adaptation,0,-2).'</li>';
						if($translation!='')echo '<li><strong>Translation: </strong>'.substr($translation,0,-2).'</li>';
						if($concept!='')echo '<li><strong>Concept: </strong>'.substr($concept,0,-2).'</li>';
						if($venue_count==1)
						{
							$productionvenue = array_values($model->productionvenues)[0];
							echo '<li>';
							echo '<strong>Venue: </strong><a href="'.$productionvenue->venue->createUrl().'">'.$productionvenue->venue->venueName.', '.$productionvenue->venue->address->addr1.', '.$productionvenue->venue->address->city.', '.$productionvenue->venue->address->state.', '.$productionvenue->venue->address->country->countryName.'</a>';
							$links = Link::model()->findAll('profileType=5 AND profileID='.$productionvenue->id.' and linkType=1');						
							foreach($links as $link)
							{
								echo ' <a href="'.$link->href.'" target="_blank" class="btn btn-mini btn-danger">'.$link->label.'</a>';
							}
							echo '</li>';
						}
						
						else if($venue_count>1)
						{
							echo '<li><a href="#venueList">Multiple venues</a></li>';
						}
						else
						{
							echo '<li>Venue not available</li>';
						}
						
						//echo $model->firstPreviewDate;
						//echo DateTime::createFromFormat('m-d-Y', $model->firstPreviewDate);
						if(!empty($model->firstPreviewDate))
						{
							$newDate = DateTime::createFromFormat('m-d-Y', $model->firstPreviewDate);
							$model->firstPreviewDate = $newDate->format('M d, Y');
						}
						if(!empty($model->startDate))
						{
							$newDate = DateTime::createFromFormat('m-d-Y', $model->startDate);
							$model->startDate = $newDate->format('M d, Y');
						}
						if(!empty($model->endDate))
						{
							$newDate = DateTime::createFromFormat('m-d-Y', $model->endDate);
							$model->endDate = $newDate->format('M d, Y');
						}
						echo '<li><strong>First Preview: </strong>'.$model->firstPreviewDate.'</li>';
						echo '<li><strong>Opening: </strong>'.$model->startDate.'</li>';
						echo '<li><strong>Closing: </strong>'.$model->endDate.'</li>';
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="mini-layout">
	<h4>Profile hits (last 12 months)</h4>
	<div id="chart_line"></div>
</div>

<div class="mini-layout">
	<h4>Profile hits by geographical location (last 12 months)</h4>
	<div id="chart_map"></div>
<?php
$jsonLineChartData=array();
$jsonMapChartData[] = array('City','Hits');
$jsonPieChartData = array();
$jsonColumnChartData = array();

try 
{
	$lineChartResults = getLineChartResults($analytics, $profileId,$model);
	if($lineChartResults ->getTotalResults())
	{
		foreach ($lineChartResults->getRows() as $row) 
		{
			$dt = DateTime::createFromFormat('Ymd',$row[0].'01');
			$jsonLineChartData[]= array($dt->format('M Y'),$row[1]);
		}
	}
	$mapChartResults = getMapChartResults($analytics, $profileId,$model);
	if($mapChartResults ->getTotalResults())
	{
		foreach ($mapChartResults->getRows() as $row) 
		{
			$jsonMapChartData[]= array($row[0],$row[1]);
		}
	}
	printResults($mapChartResults);
	
	$pieChartResults = getPieChartResults($analytics, $profileId,$model);
	if($pieChartResults ->getTotalResults())
	{
		foreach ($pieChartResults->getRows() as $row) 
		{
			$jsonPieChartData[]= array($row[0],$row[1]);
		}
	}	
	$columnChartResults = getColumnChartResults($analytics, $profileId,$model);
	if($columnChartResults ->getTotalResults())
	{
		foreach ($columnChartResults->getRows() as $row) 
		{
			$jsonColumnChartData[]= array($row[0],$row[1]);
		}
	}
}
catch (Exception $e)
{
	print 'An unknown error occurred: ' . $e->getMessage();
}

function getLineChartResults(&$analytics, $profileId,$model) {
   $optParams = array(
		'dimensions'=> 'ga:yearMonth',
		'filters' => 'ga:pagePath=='.$model->createUrl(),
		//'filters' => 'ga:pagePath==/venue/586/theatre-arlington',
		'max-results' => 12,
	);
   
	$start = new Datetime();
	$end = new DateTime();   
	$start = $start->modify('-1 year');
	$start = $start->setDate($start->format('Y'), $start->format('m')+1, 1);
   
	return $analytics->data_ga->get(
		'ga:' . $profileId,
		$start->format('Y-m-01'),
		$end->format('Y-m-t'),
		'ga:pageviews',
		$optParams
	);
}

function getMapChartResults(&$analytics, $profileId,$model) {
   $optParams = array(
		'dimensions'=> 'ga:city',
		'filters' => 'ga:pagePath=='.$model->createUrl(),
		//'filters' => 'ga:pagePath==/venue/586/theatre-arlington',
		'max-results' => 25,
	);
   
	$start = new Datetime();
	$end = new DateTime();   
	$start = $start->modify('-1 year');
	$start = $start->setDate($start->format('Y'), $start->format('m')+1, 1);
   
	return $analytics->data_ga->get(
		'ga:' . $profileId,
		$start->format('Y-m-01'),
		$end->format('Y-m-t'),
		'ga:pageviews',
		$optParams
	);
}

function getPieChartResults(&$analytics, $profileId,$model) {
   $optParams = array(
		'dimensions'=> 'ga:userGender',
		'filters' => 'ga:pagePath=='.$model->createUrl(),
		//'filters' => 'ga:pagePath==/venue/586/theatre-arlington',
		'max-results' => 2,
	);
   
	$start = new Datetime();
	$end = new DateTime();   
	$start = $start->modify('-1 year');
	$start = $start->setDate($start->format('Y'), $start->format('m')+1, 1);
   
	return $analytics->data_ga->get(
		'ga:' . $profileId,
		$start->format('Y-m-01'),
		$end->format('Y-m-t'),
		'ga:pageviews',
		$optParams
	);
}

function getColumnChartResults(&$analytics, $profileId,$model) {
   $optParams = array(
		'dimensions'=> 'ga:userAgeBracket',
		'filters' => 'ga:pagePath=='.$model->createUrl(),
		//'filters' => 'ga:pagePath==/venue/586/theatre-arlington',
		'max-results' => 10,
	);
   
	$start = new Datetime();
	$end = new DateTime();   
	$start = $start->modify('-1 year');
	$start = $start->setDate($start->format('Y'), $start->format('m')+1, 1);
   
	return $analytics->data_ga->get(
		'ga:' . $profileId,
		$start->format('Y-m-01'),
		$end->format('Y-m-t'),
		'ga:pageviews',
		$optParams
	);
}

function printResults(&$results)
{
	if (count($results->getRows()) > 0) 
	{
		$table='';
		if (count($results->getRows()) > 0) {
		$table .= '<table class="table table-striped">';

		// Print headers.
		$table .= '<tr>';

		foreach ($results->getColumnHeaders() as $header) {
		$table .= '<th>'.ucfirst(substr($header->name, 3)).'</th>';
		}
		$table .= '</tr>';

		// Print table rows.
		foreach ($results->getRows() as $row) {
		$table .= '<tr>';
		foreach ($row as $cell) {
		  $table .= '<td>'
				 . htmlspecialchars($cell, ENT_NOQUOTES)
				 . '</td>';
		}
		$table .= '</tr>';
		}
		$table .= '</table>';

		} else {
		$table .= '<p>No Results Found.</p>';
		}
		print $table;
	}
	else 
	{
	print '<p>No results found.</p>';
	}
}
?>
</div>


<div class="mini-layout">
	<h4>Audience information (last 12 months)</h4>
	<div id="chart_pie"></div>		
	<div id="chart_column"></div>
</div>
<script type="text/javascript">
	cfg.jsonLineChartData=<?=json_encode($jsonLineChartData, JSON_NUMERIC_CHECK);?>;
	cfg.jsonMapChartData=<?=json_encode($jsonMapChartData, JSON_NUMERIC_CHECK);?>;
	cfg.jsonPieChartData=<?=json_encode($jsonPieChartData, JSON_NUMERIC_CHECK);?>;
	cfg.jsonColumnChartData=<?=json_encode($jsonColumnChartData, JSON_NUMERIC_CHECK);?>;
</script>
<?php Yii::app()->clientScript->registerScript('scheduleScript',
<<<JS
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(draw);
	
	
	function draw()
	{
		drawLineChart();
		drawMapChart();
		drawPieChart();
		drawColumnChart();
	}

	// Callback that creates and populates a data table,
	function drawLineChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string','Time');
	data.addColumn('number','Hits');
	data.addRows(cfg.jsonLineChartData);

	var options = {
	};

	var chart = new google.visualization.LineChart(document.getElementById('chart_line'));
	chart.draw(data, options);
	}

	function drawMapChart() {
	  var data = google.visualization.arrayToDataTable(cfg.jsonMapChartData);

	  var options = {
		displayMode: 'markers',
		colorAxis: {colors: ['green', 'blue']}
	  };

	  var chart = new google.visualization.GeoChart(document.getElementById('chart_map'));
	  chart.draw(data, options);
	};
	
	function drawPieChart() {        
		var data = new google.visualization.DataTable();
		data.addColumn('string','Gender');
		data.addColumn('number','Hits');
		data.addRows(cfg.jsonPieChartData);

        var options = {
		title: 'Gender Breakdown',
		hAxis: {
		title: 'Gender',
		},
		vAxis: {
		title: 'Hits'
		}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_pie'));
        chart.draw(data, options);
	}
	
	
	function drawColumnChart() 
	{
		var data = new google.visualization.DataTable();
		data.addColumn('string','Age');
		data.addColumn('number','Hits');
		data.addRows(cfg.jsonColumnChartData);
		
		var options = {
		title: "Age Breakdown",
		hAxis: {
		title: 'Age',
		},
		vAxis: {
		title: 'Hits'
		}
		};
		var chart = new google.visualization.ColumnChart(document.getElementById("chart_column"));
		chart.draw(data, options);
	}
JS
, CClientScript::POS_READY);
?>