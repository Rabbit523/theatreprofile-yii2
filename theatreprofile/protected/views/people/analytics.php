<?php
$pageTitle = 'Analytics - '.$model->firstName.' '.($model->middleName!=''?$model->middleName.' ':'').($model->lastName!=''?$model->lastName.' ':'').$model->suffix." - People - ".Yii::app()->name;
$this->pageTitle=$pageTitle;
Yii::app()->clientScript->registerMetaTag($pageTitle, null, null, array('property' => "og:title"));
Yii::app()->clientScript->registerMetaTag($model->descr, null, null, array('property' => "og:description"));
$this->breadcrumbs=array('People'=>array('/people'),$model->firstName.' '.($model->middleName!=''?$model->middleName.' ':'').($model->lastName!=''?$model->lastName.' ':'').$model->suffix=>$model->createUrl(),'Analytics');
Yii::app()->clientScript->registerScript('initScript', "cfg.modelID=".(isset($model->id)?$model->id:0).";", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCss("viewStyle",".pnl-profile-pic{padding:1px;margin:0 auto;border: 1px solid #ddd;}
.pnl-profile-pic > .media-object{width:140px;height:220px;}");
Yii::app()->clientScript->registerScriptFile("https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}",CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="media">
		<?php
		$profile_image=Profileimage::model()->with('image')->find('profileType=3 AND imageType=1 AND profileID='.$model->id);
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			Yii::app()->clientScript->registerMetaTag(yii::app()->getBaseUrl(true).'/images/uploads/'.$profile_image->image->imageURL, null, null, array('property' => "og:image"));
		}else
		{
			$image_url=yii::app()->request->baseUrl.'/images/default/default_140x220.gif';
		}
		?>
		<div class="pull-left text-center">
			<div class="pnl-profile-pic">
				<span itemprop="photo">
				<img class="media-object" src="<?php echo $image_url; ?>" width="140px" height="220px" alt="">
				</span>
			</div>
		</div>
	
		<div class="media-body">
			<div class="media-heading"><h1 class="inline"><span itemprop="name"><?php echo $model->firstName.' '.$model->middleName.' '.$model->lastName.' '.$model->suffix; ?></span></h1>
			</div>
			<ul class="unstyled">
				<?php
					if($model->gender==1)
						echo '<li><span>Male</span></li>';
					else if($model->gender==2)
						echo '<li><span>Female</span></li>';
				?>
			</ul>
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