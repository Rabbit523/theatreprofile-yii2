<?php

class ProductioneventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
			'rights',
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function allowedActions()
	{
		return '';
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id,$pvid=NULL)
	{
		$Venue = Venue::model()->findByPk($id);
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$Venue->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($Venue->createUrl());
		}
		
        foreach($Venue->productionvenues as $productionvenue) {
			$data[$productionvenue->id] = $productionvenue->production->show->showName.($productionvenue->production->productionName==''?'':' - '.$productionvenue->production->productionName);
        }
	
		$model=new Productionevent;
		$events=array();
		if(!empty($pvid))
		{
			$model->productionVenueID=$pvid;
			$Productionvenue=Productionvenue::model()->findByPk($pvid);	
			$Productionevents=$Productionvenue->productionevents;
			foreach($Productionevents as $Productionevent)
			{
				$events= array_merge_recursive($events,$this->processEvent($Productionevent));
			}
			usort($events, array($this, "date_compare"));
			
			
			
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Productionevent']))
		{
			$model->attributes=$_POST['Productionevent'];
			if(empty($Venue->venueownerships))
			{
				$model->type=0;
			}
			if($model->recurs==0)
			{
				$model->recursStartDate=null;
				$model->recursEndDate=null;
			}
			if($model->save())
			{
				Yii::app()->user->setFlash('success', "Event successfully added.");
				$this->redirect(array('update','id'=>$model->id));
			}
		}
		
		$this->render('create',array(
			'model'=>$model,'venue'=>$Venue,'data'=>$data,'events'=>$events
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$Venue = $model->productionvenue->venue;
		$events=array();
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$Venue->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect($Venue->createUrl());
		}
		
		$Productionvenue=$model->productionvenue;	
		$Productionevents=$Productionvenue->productionevents;
		foreach($Productionevents as $Productionevent)
		{
			$events= array_merge_recursive($events,$this->processEvent($Productionevent));
		}
		usort($events, array($this, "date_compare"));
		
		if($model->recurs!=0)
		{
			Yii::app()->user->setFlash('info', "This is a recurring event. Modifying this record will affect all recurring instances of the event.");
		}			
		
		foreach($model->productionvenue->venue->productionvenues as $productionvenue) {
                $data[$productionvenue->id] = $productionvenue->production->show->showName.($productionvenue->production->productionName==''?'':' - '.$productionvenue->production->productionName);
        }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		
		if(isset($_POST['Productionevent']))
		{
			$model->attributes=$_POST['Productionevent'];
			if(empty($Venue->venueownerships))
			{
				$model->type=0;
			}
			if($model->recurs==0)
			{
				$model->recursStartDate=null;
				$model->recursEndDate=null;
			}
			if($model->save())
			{
				Yii::app()->user->setFlash('success', "Event successfully updated.");
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,'venue'=>$model->productionvenue->venue,'data'=>$data,'events'=>$events
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$Venue = $model->productionvenue->venue;
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$Venue->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			$this->redirect(array('venue/schedule','id'=>$Venue->id));
		}
		
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success', "Event successfully deleted.");
		$this->redirect(array('venue/schedule','id'=>$Venue->id));

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
			//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Productionevent the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Productionevent::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Productionevent $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='productionevent-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	/**
	 * Performs the AJAX validation.
	 * @param Productionevent $model the model to be validated
	 */
	public function actionSubmitEventInfo($id,$elsid)
	{
		$Productionevent=$this->loadModel($id);	
		$vid = $Productionevent->productionvenue->venueID;
		$Venue=$Productionevent->productionvenue->venue;
		if(!Yii::app()->user->checkAccess('Venue.UpdateAccess',array('ownerships'=>$Venue->venueownerships)))
		{
			Yii::app()->user->setFlash('error', "You do not have the required privileges to modify this profile because it is owned by another user.");
			throw new CHttpException(403,'You do not have the privileges required to perform this action.');
		}
		
		$Production=$Productionevent->productionvenue->production;
		$Productionevents=$Productionevent->productionvenue->productionevents;
		$Show=$Production->show;
		$title = $Show->showName;
		$description = ($Production->descr==''?$Show->showDesc:$Production->descr);
		$eventUrl = $Production->createUrl();
		$profile_image=Profileimage::model()->with('image')->find('profileType=2 AND profileID='.$Production->id);
		$image_url='';
				
		if(isset($profile_image->image->imageURL))
		{
			$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
		}
		else
		{
			$profile_image=Profileimage::model()->with('image')->find('profileType=1 AND profileID='.$Show->id);
			if(isset($profile_image->image->imageURL))
			{
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/serve/uploads/'.pathinfo($profile_image->image->imageURL,PATHINFO_FILENAME).'_w140h220.'.pathinfo($profile_image->image->imageURL,PATHINFO_EXTENSION);
			}
			else
			{					
				$image_url=Yii::app()->params["mediaServeUrl"].'/images/default/default_140x220.gif';
			}
		}
		$links = Link::model()->findAll('profileType=5 AND profileID='.$Productionevent->productionVenueID.' and linkType=1');
		$subject='Event Schedule - '.$title;
		$header='<tr><td colspan="2">This event information is being submitted by <a target="_blank" href="http://www.theatreprofile.com">Theatre Profile</a> on behalf of '.Yii::app()->getModule('user')->user()->profile->getAttribute('firstname').' '.Yii::app()->getModule('user')->user()->profile->getAttribute('lastname').'.</td></tr>';
		$header=$header.'<tr><td valign="top" width="105px" height="165px"><img src="'.$image_url.'" width="105px" height="165px" /></td><td valign="top">';
		$header=$header.'<p><a target="_blank" href="'.$Production->createAbsoluteUrl().'">'.$title.'</a></p>';
		$header=$header.'<p><a target="_blank" href="'.$Venue->createAbsoluteUrl().'">'.$Venue->venueName.'</a>: '.$Venue->address->addr1.', '.$Venue->address->city.', '.$Venue->address->state.', '.$Venue->address->country->countryName.'</p>';
		$header=$header.'<p>First preview: '.$Production->firstPreviewDate.'</p>';
		$header=$header.'<p>Open: '.$Production->startDate.'</p>';
		$header=$header.'<p>Close: '.$Production->endDate.'</p>';
		if(count($links))
		{
			$header=$header.'<p>';
			foreach($links as $link)
			{
				$header=$header.'<a href="'.$link->href.'">'.$link->label.'</a> ('.$link->href.')<br />';
			}
			$header=$header.'</p>';
		}
		$header=$header.'</td></tr>';
		if($description!='')
			$header=$header.'<tr><td colspan="2"><p>'.$description.'</p></td></tr>';
		else
			$header=$header.'<tr><td colspan="2"><p>Event description not available.</p></td></tr>';
		$header=$header.'<tr><td colspan="2" style="border-bottom:1px solid black"><strong>Schedule*</strong></td></tr>';
		$items=array();
		$body='';
		foreach($Productionevents as $Productionevent)
		{
			$items= array_merge_recursive($items,$this->processEvent($Productionevent));
		}
		usort($items, array($this, "date_compare"));
		foreach($items as $item)
		{
			$body=$body.'<tr><td colspan="2"><p>'.$item['startDate'].'</p>'.$item['startTime'].'<p></p><p>Duration: '.$item['duration'].' minutes</p></td></tr>';
		}
		$body= '<style type="text/css">tr:nth-child(even){background:#CCC}tr:nth-child(odd){background:#FFF}.btn{-webkit-border-radius:4;-moz-border-radius:4;border-radius:4px;font-family: Arial;color: #ffffff;font-size: 15px;padding: 5px 10px 5px 10px;text-decoration: none;}.blue {background: #273fd9;}.blue:hover {background: #3cb0fd;text-decoration: none;}.red {background:#d93434;}.red:hover {background:#f54949;text-decoration: none;}</style><table width="100%" cellpadding="5px">'.$header.$body;
		$body = $body.'<tr><td colspan="2"><a class="btn blue" target="_blank" href="'.$Production->createAbsoluteUrl().'">More Info</a>';
		if(count($links))
		{
			foreach($links as $link)
			{
				$body = $body.' <a class="btn red" target="_blank" href="'.$link->href.'">'.$link->label.'</a>';
			}
		}
		$body = $body.'</td></tr>';
		$body = $body.'<tr><td colspan="2"><p>*Schedule subject to change</p>';
		$body = $body.'<p>All information was accurate at the time the email was sent. Any changes to the schedule, cast, crew, or anything related to the production can be found on the production\'s <a target="_blank" href="'.$Production->createAbsoluteUrl().'">profile page</a>. To receive updates on this production, or any profile on Theatre Profile, visit the profile and add it to your watchlist.</p></td></tr>';
		$body = $body.'</table>';
		$Eventlistingservice = Eventlistingservice::model()->findbyPk($elsid);
		$this->sendMail($Eventlistingservice->submitEndpoint,$subject,$body);
		echo "Request submitted successfully.";
		//$this->sendMail('nagadevs@gmail.com',$subject,$body);
	}
	
	private function date_compare($a, $b)
	{
		$t1 = strtotime($a['startDate']);
		$t2 = strtotime($b['startDate']);
		return $t1 - $t2;
	}
	
	private function processEvent($Productionevent)
	{
		$item = array();
		if($Productionevent->recurs==0)
		{
			$item[] = $this->generate_event($Productionevent);
			return $item;
		}
		else
		{
			return $this->generate_repeating_event($Productionevent);
		}
	}
	
	private function generate_event($Productionevent)
	{
		$start = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		//$diff=$start->diff($end);
		$item=array(
			'startDate'=>$start->format('l, F d, Y'),
			'startTime'=>$start->format('g:i A'),
			'duration'=>isset($Productionevent->productionvenue->production->duration)?$Productionevent->productionvenue->production->duration:'NA',
			'href'=>Yii::app()->createUrl('productionevent/update',array('id'=>$Productionevent->id)),
			//'end'=>$end->format('m-d-Y H:i'),
		);
		return $item;
	}
	
	private function generate_repeating_event($Productionevent) {
		$start = DateTime::createFromFormat('m-d-Y H:i', $Productionevent->startDate);
		$recursEndDate = DateTime::createFromFormat('!m-d-Y', $Productionevent->recursEndDate);
		$recursEndDate->setTime(23, 59, 59);
		$item =array();
		$item[]=$this->generate_event($Productionevent);
		$start = $this->get_next_date($start, $Productionevent->recurs);
		//$end = $this->get_next_date($end, $Productionevent->recurs);
		//$diff=$start->diff($end);
		while ($start <= $recursEndDate)
		{
			$item[]=array(
				'startDate'=>$start->format('l, F d, Y'),
				'startTime'=>$start->format('g:i A'),
				'duration'=>isset($Productionevent->productionvenue->production->duration)?$Productionevent->productionvenue->production->duration:'NA',
				'href'=>Yii::app()->createUrl('productionevent/update',array('id'=>$Productionevent->id)),
				//'end'=>$end->format('m-d-Y H:i'),
			);
			$start = $this->get_next_date($start, $Productionevent->recurs);
			//$end = $this->get_next_date($end, $Productionevent->recurs);
		}
		return $item;
	 }

	private function get_next_date($date, $int) {
		if ($int == 1)
			$date->add(new DateInterval('P1D'));
		if ($int == 2)
			$date->add(new DateInterval('P1W'));
		if ($int == 3)
			$date->add(new DateInterval('P1M'));
		return $date;
	}

	private function get_next_month($date, $n = 1) {
		$newDate = strtotime("+{$n} months", $date);
		// adjustment for events that repeat on the 29th, 30th and 31st of a month
		if (date('j', $date) !== (date('j', $newDate))) {
			$newDate = date($date,strtotime("+" .$n. " months"));
		}
		return $newDate;
	}

	private function get_next_year($date, $n = 1) {
		$newDate = strtotime("+{$n} years", $date);
		// adjustment for events that repeat on february 29th
		if (date('j', $date) !== (date('j', $newDate))) {
			$newDate = date($date,strtotime("+" . $n + 3 . " years"));
		}
		return $newDate;
	}
	
	/**
	 * Send mail method
	 */
	private function sendMail($email,$subject,$message) {
    	$adminEmail = Yii::app()->params['adminEmail'];
	    $headers = "Cc:".Yii::app()->getModule('user')->user()->profile->getAttribute('firstname').' '.Yii::app()->getModule('user')->user()->profile->getAttribute('lastname')."<".Yii::app()->getModule('user')->user()->email.">\r\nMIME-Version: 1.0\r\nFrom:".Yii::app()->name."<$adminEmail>\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
	    $message = wordwrap($message, 70);
	    $message = str_replace("\n.", "\n..", $message);
	    return mail($email,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
	}
}
