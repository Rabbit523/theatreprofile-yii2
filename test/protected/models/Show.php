<?php

/**
 * This is the model class for table "tbl_show".
 *
 * The followings are the available columns in table 'tbl_show':
 * @property integer $id
 * @property string $showName
 * @property integer $categoryID
 * @property string $showDesc
 * @property string $showDate
 *
 * The followings are the available model relations:
 * @property Production[] $productions
 * @property Showcategory $category
 * @property Showcreator[] $showcreators
 * @property Showownership[] $showownerships
 * @property Showwatchlist[] $showwatchlists
 */
class Show extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_show';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('showName,categoryID','required'),
			array('categoryID', 'numerical', 'integerOnly'=>true),
			array('showName', 'length', 'max'=>100),
			array('showDesc', 'length', 'max'=>3000),
			array('showDate', 'safe'),
			array('showDate','date','format'=>'yyyy'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, showName, categoryID, showDesc, showDate', 'safe', 'on'=>'search'),
			array('showDate', 'default', 'setOnEmpty' => true, 'value' => null),
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
			'productions' => array(self::HAS_MANY, 'Production', 'showID','order'=>'case (productions.startDate is null and productions.endDate is null) when 0 then case (productions.startDate is not null and productions.endDate is null) when 1 then 1 else 2 end else 3 end,productions.endDate desc,productions.startDate desc'),
			'category' => array(self::BELONGS_TO, 'Showcategory', 'categoryID'),
			'showcreators' => array(self::HAS_MANY, 'Showcreator', 'showID'),
			'showownerships' => array(self::HAS_MANY, 'Showownership', 'showID'),
			'showwatchlists' => array(self::HAS_MANY, 'Showwatchlist', 'showID'),
			'profileimage' => array(self::HAS_MANY, 'Profileimage', array('profileID'=>'id'),'joinType' => 'INNER JOIN', 'on' => 'profileType=1 AND imageType=1'),
			'galleryimages' => array(self::HAS_MANY, 'Profileimage', array('profileID'=>'id'),'joinType' => 'INNER JOIN', 'on' => 'profileType=1 AND imageType=2'),
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
					'route'=>'show/view',
					'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
				),
			);
		}
		else
		{
			return array(
				'seo'=>array(
					'class'=>'theatreprofile.extensions.seo.behaviors.SeoActiveRecordBehavior',
					'route'=>'show/view',
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
			'showName' => 'Show Name',
			'categoryID' => 'Category',
			'showDesc' => 'Show Desc',
			'showDate' => 'Show Date',
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
		$criteria->compare('showName',$this->showName,true);
		$criteria->compare('categoryID',$this->categoryID);
		$criteria->compare('showDesc',$this->showDesc,true);
		$criteria->compare('showDate',$this->showDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Show the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function afterFind()
	{
		if(!empty($this->showDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->showDate);
			$this->showDate = $newDate->format('Y');
		}
		return parent::afterFind();
	}
	
	
	protected function beforeSave(){
		if(!empty($this->showDate))
		{
			$newDate = DateTime::createFromFormat('Y', $this->showDate);
			$this->showDate = $newDate->format('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}
	
	
	/*
	 * Custom slug generator for show model
	 */
	
	protected function toAscii($replace=array(), $delimiter='-') {
		if(isset($this->id))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$str=$this->showName;
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
