<?php

class SearchController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
			'rights',
		);
	}
	
	public function allowedActions()
	{
		return 'index,searchajax,Searchajax';
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($term)
	{	
		
		$term=preg_replace('/[^A-Za-z0-9 \-]/', '', $term);
		$optimizedterm=str_replace(' ', '%', $term);
		$criteria1 = new CDbCriteria();
		$criteria1->addSearchCondition("replace(showName, '\'', '')", '%'.$optimizedterm.'%',false);
		$criteria1->order="case when replace(showName, '\'', '')  like concat('".$optimizedterm."','%') then 1 
		when replace(showName, '\'', '') like concat('%','".$optimizedterm."','%') then 2 
		when replace(showName, '\'', '') like concat('%','".$optimizedterm."') then 3 end";
		$dataProvider1 = new CActiveDataProvider('Show',array('criteria'=>$criteria1,'pagination'=>array('pageSize'=>5,)));
		$criteria2 = new CDbCriteria();
		$criteria2->addSearchCondition("replace(showName, '\'', '')", '%'.$optimizedterm.'%',false);
		$criteria2->order="case when replace(show.showName, '\'', '')  like concat('".$optimizedterm."','%') then 1 
		when replace(show.showName, '\'', '') like concat('%','".$optimizedterm."','%') then 2 
		when replace(show.showName, '\'', '') like concat('%','".$optimizedterm."') then 3 end";
		$criteria2->with=array('show');
		$dataProvider2 = new CActiveDataProvider('Production',array('criteria'=>$criteria2,'pagination'=>array('pageSize'=>5,)));
		$criteria3 = new CDbCriteria();
		$criteria3->addSearchCondition("replace(concat(firstName,middleName,lastName,suffix),'\'','')", '%'.$optimizedterm.'%',false);
		$criteria3->order="case when replace(concat(firstName,middleName,lastName,suffix),'\'','') like concat('".$optimizedterm."','%') then 1 
		when replace(concat(firstName,middleName,lastName,suffix),'\'','') like concat('%','".$optimizedterm."','%') then 2 
		when replace(concat(firstName,middleName,lastName,suffix),'\'','') like concat('%','".$optimizedterm."') then 3 end";
		$dataProvider3 = new CActiveDataProvider('Individual',array('criteria'=>$criteria3,'pagination'=>array('pageSize'=>5,)));
		$criteria4 = new CDbCriteria();
		$criteria4->addSearchCondition("replace(venueName,'\'','')", '%'.$optimizedterm.'%',false);
		$criteria4->order="case when replace(venueName, '\'', '') like concat('".$optimizedterm."','%') then 1 
		when replace(venueName, '\'', '') like concat('%','".$optimizedterm."','%') then 2 
		when replace(venueName, '\'', '') like concat('%','".$optimizedterm."') then 3 end";
		$dataProvider4 = new CActiveDataProvider('Venue',array('criteria'=>$criteria4,'pagination'=>array('pageSize'=>5,)));
		$criteria5 = new CDbCriteria();
		$criteria5->addSearchCondition("replace(companyName,'\'','')", '%'.$optimizedterm.'%',false);
		$criteria5->order="case when replace(companyName, '\'', '') like concat('".$optimizedterm."','%') then 1 
		when replace(companyName, '\'', '') like concat('%','".$optimizedterm."','%') then 2 
		when replace(companyName, '\'', '') like concat('%','".$optimizedterm."') then 3 end";
		$dataProvider5 = new CActiveDataProvider('Company',array('criteria'=>$criteria5,'pagination'=>array('pageSize'=>5,)));
		
		$this->render('index',array('term'=>$term,'dataProvider1'=>$dataProvider1,'dataProvider2'=>$dataProvider2,'dataProvider3'=>$dataProvider3,'dataProvider4'=>$dataProvider4,'dataProvider5'=>$dataProvider5));
	}
	
	public function actionSearchajax()
    {
		$term = CHtml::decode(Yii::app()->request->getQuery('term'));
		$term=preg_replace('/[^A-Za-z0-9 \-]/', '', $term);
		$term=str_replace(' ', '%', $term);
		
		$command = Yii::app()->db->createCommand("select s.id as profileID,'1' as profileType ,s.showName as label,i1.imageURL
		,case when replace(s.showName, '\'', '')  like concat(:term,'%') then 1 
		when replace(s.showName, '\'', '') like concat('%',:term,'%') then 2 
		when replace(s.showName, '\'', '') like concat('%',:term) then 3 end as sortOrder
		from tbl_show s LEFT JOIN tbl_profileimage p1 on s.id = p1.profileID and p1.profileType=1 and p1.imageType =1
		LEFT JOIN tbl_image i1 on p1.imageID = i1.id
		where replace(s.showName, '\'', '') like concat('%',:term,'%')
		UNION
		select i.id as profileID,'3' as profileType ,REPLACE(concat(i.firstName,' ',i.middleName,' ',i.lastName,' ',i.suffix),'  ',' ') as label,i3.imageURL
		,case when replace(concat(i.firstName,i.middleName,i.lastName,i.suffix),'\'','') like concat(:term,'%') then 1 
		when replace(concat(i.firstName,i.middleName,i.lastName,i.suffix),'\'','') like concat('%',:term,'%') then 2 
		when replace(concat(i.firstName,i.middleName,i.lastName,i.suffix),'\'','') like concat('%',:term) then 3 end as sortOrder
		from tbl_individual i  LEFT JOIN tbl_profileimage p3 on i.id = p3.profileID and p3.profileType=3 and p3.imageType =1
		LEFT JOIN tbl_image i3 on p3.imageID = i3.id
		where replace(concat(i.firstName,i.middleName,i.lastName,i.suffix),'\'','') like concat('%',:term,'%')
		UNION
		select v.id as profileID,'4' as profileType, v.venueName as label,i4.imageURL
		,case when replace(v.venueName, '\'', '') like concat(:term,'%') then 1 
		when replace(v.venueName, '\'', '') like concat('%',:term,'%') then 2 
		when replace(v.venueName, '\'', '') like concat('%',:term) then 3 end as sortOrder
		from tbl_venue v  LEFT JOIN tbl_profileimage p4 on v.id = p4.profileID and p4.profileType=4 and p4.imageType =1
		LEFT JOIN tbl_image i4 on p4.imageID = i4.id
		where replace(v.venueName, '\'', '') like concat('%',:term,'%')
		UNION
		select c.id as profileID,'5' as profileType, c.companyName as label,i4.imageURL
		,case when replace(c.companyName, '\'', '') like concat(:term,'%') then 1 
		when replace(c.companyName, '\'', '') like concat('%',:term,'%') then 2 
		when replace(c.companyName, '\'', '') like concat('%',:term) then 3 end as sortOrder
		from tbl_company c  LEFT JOIN tbl_profileimage p4 on c.id = p4.profileID and p4.profileType=5 and p4.imageType =1
		LEFT JOIN tbl_image i4 on p4.imageID = i4.id
		where replace(c.companyName, '\'', '') like concat('%',:term,'%')
		order by profileType,sortOrder");
		$command->bindParam(":term",$term,PDO::PARAM_STR);
		
		$results= $command->queryAll();
		
        echo json_encode($results);
    }
}