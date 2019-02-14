<?php

/**
 * This is the model class for table "tbl_production".
 *
 * The followings are the available columns in table 'tbl_production':
 * @property integer $id
 * @property integer $showID
 * @property string $firstPreviewDate
 * @property string $startDate
 * @property string $endDate
 * @property string $descr
 * @property integer $categoryID
 * @property string $productionName
 * @property integer $duration
 * @property integer $intermissions
 * @property tinyint $privateRatings
 *
 * The followings are the available model relations:
 * @property Productioncategory $category
 * @property Show $show
 * @property Productioncast[] $productioncasts
 * @property Productioncompanycrew[] $productioncompanycrews
 * @property Productioncrew[] $productioncrews
 * @property Productionownership[] $productionownerships
 * @property Productionrating[] $productionratings
 * @property Productionvenue[] $productionvenues
 * @property Productionwatchlist[] $productionwatchlists
 */
class Production extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_production';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('showID', 'required'),
			array('showID, categoryID, duration, intermissions, privateRatings', 'numerical', 'integerOnly'=>true),
			array('descr', 'length', 'max'=>3000),
			array('productionName', 'length', 'max'=>100),
			array('firstPreviewDate,startDate,endDate', 'safe'),
			array('firstPreviewDate,startDate,endDate','date','format'=>'mm-dd-yyyy'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, showID, firstPreviewDate, startDate, endDate, descr, categoryID, productionName', 'safe', 'on'=>'search'),
			array('firstPreviewDate,startDate,endDate', 'default', 'setOnEmpty' => true, 'value' => null),
			
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'show' => array(self::BELONGS_TO, 'Show', 'showID'),
			'category' => array(self::BELONGS_TO, 'Productioncategory', 'categoryID'),
			'productioncasts' => array(self::HAS_MANY, 'Productioncast', 'productionID', 'order'=>'productioncasts.endDate, individual.lastName, individual.firstName','with'=>'individual'),
			'productioncrews' => array(self::HAS_MANY, 'Productioncrew', 'productionID', 'order'=>'productioncrews.endDate, individual.lastName, individual.firstName','with'=>'individual'),
			'productioncompanycrews' => array(self::HAS_MANY, 'Productioncompanycrew', 'productionID', 'order'=>'productioncompanycrews.endDate, company.companyName','with'=>'company'),
			'productionvenues' => array(self::HAS_MANY, 'Productionvenue', 'productionID', 'order'=>'productionvenues.startDate desc','with'=>'venue'),
			//'productioncasts' => array(self::HAS_MANY, 'Productioncast', 'productionID', 'order'=>'case (productioncasts.startDate is null and productioncasts.endDate is null) when 0 then case (productioncasts.startDate is not null and productioncasts.endDate is null) when 1 then 1 else 2 end else 3 end,productioncasts.endDate desc,productioncasts.startDate desc,individual.firstName,individual.lastName','with'=>'individual'),
			//'productioncrews' => array(self::HAS_MANY, 'Productioncrew', 'productionID', 'order'=>'case (productioncrews.startDate is null and productioncrews.endDate is null) when 0 then case (productioncrews.startDate is not null and productioncrews.endDate is null) when 1 then 1 else 2 end else 3 end,productioncrews.endDate desc,productioncrews.startDate desc,individual.firstName,individual.lastName','with'=>'individual'),
			//'productionvenues' => array(self::HAS_MANY, 'Productionvenue', 'productionID', 'order'=>'case (productionvenues.startDate is null and productionvenues.endDate is null) when 0 then case (productionvenues.startDate is not null and productionvenues.endDate is null) when 1 then 1 else 2 end else 3 end,productionvenues.endDate desc,productionvenues.startDate desc,venue.venueName','with'=>'venue'),
			'productionratings' => array(self::HAS_MANY, 'Productionrating', 'productionID'),
			'avgrating' => array(self::STAT, 'Productionrating', 'productionID','select' =>'ROUND(AVG(rating),1)','group' => 'productionID',),
			'ratingcount' => array(self::STAT, 'Productionrating', 'productionID','select' =>'count(*)','group' => 'productionID',),
			'productionownerships' => array(self::HAS_MANY, 'Productionownership', 'productionID'),
			'productionwatchlists' => array(self::HAS_MANY, 'Productionwatchlist', 'productionID'),
			'profileimage' => array(self::HAS_MANY, 'Profileimage', array('profileID'=>'id'),'joinType' => 'INNER JOIN', 'on' => 'profileType=2 AND imageType=1'),
			'galleryimages' => array(self::HAS_MANY, 'Profileimage', array('profileID'=>'id'),'joinType' => 'INNER JOIN', 'on' => 'profileType=2 AND imageType=2'),
		);
	}

	/**
	 * @return array behavior rules.
	 */
	public function behaviors()
    {
		if(Yii::app()->name=='Theatre Profile')
		{
			return array(
				'AuditFieldBehavior' => array(
					// Path to AuditFieldBehavior class.
					'class' => 'audit.components.AuditFieldBehavior',
	 
					// Set to false if you just want to use getDbAttribute and other methods in this class.
					// If left unset the value will come from AuditModule::enableAuditField
					'enableAuditField' => null,
	 
					// Any additional models you want to use to write model and model_id audits to.  If this array is not empty then
					// each field modifed will result in an AuditField being created for each additionalAuditModels.
					'additionalAuditModels' => array(
						//'Post' => 'post_id',
					),
	 
					// A list of values that will be treated as if they were null.
					//'ignoreValues' => array('0', '0.0', '0.00', '0.000', '0.0000', '0.00000', '0.000000', '0000-00-00', '0000-00-00 00:00:00'),
				),
				'seo'=>array(
					'class'=>'ext.seo.behaviors.SeoActiveRecordBehavior',
					'route'=>'production/view',
					'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
				),
			);
		}
		else
		{
			return array(
				'seo'=>array(
					'class'=>'theatreprofile.extensions.seo.behaviors.SeoActiveRecordBehavior',
					'route'=>'production/view',
					'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
				),
			);
		}
    }
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'showID' => 'Show',
			'firstPreviewDate' => 'First Preview Date',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'descr'=> 'About',
			'categoryID'=>'Production Category',
			'productionName'=>'Production Name',
			'duration' => 'Duration',
			'intermissions' => 'Intermissions',
			'privateRatings' => 'Keep Ratings Private?'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('showID',$this->showID);
		$criteria->compare('firstPreviewDate',$this->firstPreviewDate,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('categoryID',$this->categoryID);
		$criteria->compare('productionName',$this->productionName,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('intermissions',$this->intermissions);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Production the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function afterFind()
	{
		if(!empty($this->firstPreviewDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->firstPreviewDate);
			$this->firstPreviewDate = $newDate->format('m-d-Y');
		}
		if(!empty($this->startDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate);
			$this->startDate = $newDate->format('m-d-Y');
			
		}
		if(!empty($this->endDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->endDate);
			$this->endDate = $newDate->format('m-d-Y');
		}
		return parent::afterFind();
	}

	
	protected function beforeSave(){
		if(!empty($this->firstPreviewDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->firstPreviewDate);
			$this->firstPreviewDate = $newDate->format('Y-m-d');
		}
		if(!empty($this->startDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->startDate);
			$this->startDate = $newDate->format('Y-m-d');
		}
		if(!empty($this->endDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->endDate);
			$this->endDate = $newDate->format('Y-m-d');
		}
		return parent::beforeSave();
	}
	
	
	/*
	 * Custom slug generator for production model
	 */
	
	protected function toAscii($replace=array(), $delimiter='-') {
		if(isset($this->id))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$venue_count = count($this->productionvenues);
			if($venue_count==1)
			{
				$venue = array_values($this->productionvenues)[0];
				$str = (!empty($this->productionName)?$this->show->showName.' - '.$this->productionName:$this->show->showName.' at '.$venue->venue->venueName);
			}
			else if($venue_count>1)
			{
				$str = (!empty($this->productionName)?$this->show->showName.' - '.$this->productionName:$this->show->showName.' - Multiple venues');
			}
			else
			{
				$str = (!empty($this->productionName)?$this->show->showName.' - '.$this->productionName:$this->show->showName.' - Venue not available');
			}
			if( !empty($replace) ) {
				$str = str_replace((array)$replace, ' ', $str);
			}
			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			return $clean;
		}
	}
}
