<?php
class NewsController extends Controller
{
	
	
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	
	public function filters()
	{
		return array(
			'rights',
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function allowedActions()
	{
		return 'index,read,view';
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model=Feeditem::model()->with('feed')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$this->render('view',array(
			'model'=>$model,			
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
    {
            $criteria = new CDbCriteria;
			$criteria->order = 'publishDate desc';
            $total = Feeditem::model()->count();
            $pages = new CPagination($total);
            $pages->pageSize = 6;
            $pages->applyLimit($criteria);
			$feeditems = Feeditem::model()->findAll($criteria);
			$this->render('index', array(
                'feeditems' => $feeditems,
                'pages' => $pages,
            ));
    }
	
	public function actionRead()
    {
        $feeds = Feed::model()->findAll(array('condition'=>'status=0'));
        foreach ($feeds as $feed) {
            /* Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);*/
			$client= new Zend\Http\Client(null, array(
			  'adapter' => 'Zend\Http\Client\Adapter\Socket',
			  'sslverifypeer' => false
			));
			\Zend\Feed\Reader\Reader::setHttpClient($client);
			$channel = Zend\Feed\Reader\Reader::import($feed->url);
			//$reader = Zend\Feed\Reader\Reader::import('http://example.com/path/to/feed.xml');
			$i=0;
            foreach ($channel as $item) 
            {
				$Feeditem=new Feeditem;
                $Feeditem->feedID = $feed->id;
                $Feeditem->title = $item->getTitle();
                $Feeditem->href = $item->getLink();
                $Feeditem->descr = $item->getDescription();				
                $publishDate = $item->getDateModified();
				$publishDate->setTimeZone(new DateTimeZone('UTC'));
                $Feeditem->publishDate =  $publishDate->format('Y-m-d H:i:s');;
				if($i==0)
				{
					$lastUpdateDate =  $Feeditem->publishDate;
				}				
				if($Feeditem->publishDate > $feed->lastUpdateDate)
				{
					if(!$Feeditem->save()) 
					{
						echo '<div>'.$feed->name.': Feed item read unsuccessful (Unknown error when processing post "'.$Feeditem->title.'")</div>';
					}
				}
				else
				{
					break;
				}
				$i++;
            }
			$feed->lastUpdateDate=$lastUpdateDate;
			$feed->save();
			echo '<div>'.$feed->name.": Feed item read successful.</div>";
        }
    }
}