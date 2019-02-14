<?php

/**
 * This is the model class for table "tbl_individual".
 *
 * The followings are the available columns in table 'tbl_individual':
 * @property integer $id
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $suffix
 * @property integer $gender
 * @property integer $individualType
 * @property integer $countryID
 * @property string $zip
 * @property string $dateOfBirth
 * @property string $descr
 * @property string $dateofDeath
 *
 * The followings are the available model relations:
 * @property Country $country
 * @property Individualownership[] $individualownerships
 * @property Individualwatchlist[] $individualwatchlists
 * @property Productioncast[] $productioncasts
 * @property Showcreator[] $showcreators
 */
class Individual extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_individual';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firstName', 'required'),
			array('gender, individualType, countryID', 'numerical', 'integerOnly'=>true),
			array('firstName, middleName, lastName, suffix', 'length', 'max'=>45),
			array('descr', 'length', 'max'=>3000),
			array('zip', 'length', 'max'=>10),
			array('dateOfBirth', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firstName, middleName, lastName, suffix, gender, individualType, countryID, zip, dateOfBirth, descr', 'safe', 'on'=>'search'),
			array('dateOfBirth', 'default', 'setOnEmpty' => true, 'value' => null),
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
			'country' => array(self::BELONGS_TO, 'Country', 'countryID'),
			'productioncasts' => array(self::HAS_MANY, 'Productioncast', 'individualID', 'with'=>'production', 'order'=>'case (ifnull(productioncasts.startDate,production.startDate) is null and ifnull(productioncasts.endDate,production.endDate) is null) when 0 then case (ifnull(productioncasts.startDate,production.startDate) is not null and ifnull(productioncasts.endDate,production.endDate) is null) when 1 then 1 else 2 end else 3 end,ifnull(productioncasts.endDate,production.endDate) desc,ifnull(productioncasts.startDate,production.startDate) desc'),
			'productioncrews' => array(self::HAS_MANY, 'Productioncrew', 'profileID','on'=>'profileType=1', 'with'=>'production', 'order'=>'case (ifnull(productioncrews.startDate,production.startDate) is null and ifnull(productioncrews.endDate,production.endDate) is null) when 0 then case (ifnull(productioncrews.startDate,production.startDate) is not null and ifnull(productioncrews.endDate,production.endDate) is null) when 1 then 1 else 2 end else 3 end,ifnull(productioncrews.endDate,production.endDate) desc,ifnull(productioncrews.startDate,production.startDate) desc'),
			'showcreators' => array(self::HAS_MANY, 'Showcreator', 'individualID','order'=>'show.showName','with'=>'show'),
			'individualownerships' => array(self::HAS_MANY, 'Individualownership', 'individualID'),
			'individualwatchlists' => array(self::HAS_MANY, 'Individualwatchlist', 'individualID'),
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
					'route'=>'people/view',
					'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
				),
			);
		}
		else
		{
			return array(
				'seo'=>array(
					'class'=>'theatreprofile.extensions.seo.behaviors.SeoActiveRecordBehavior',
					'route'=>'people/view',
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
			'firstName' => 'First Name',
			'middleName' => 'Middle Name',
			'lastName' => 'Last Name',
			'suffix' => 'Suffix',
			'gender' => 'Gender',
			'individualType' => 'Individual Type',
			'countryID' => 'Country',
			'zip' => 'Zip',
			'dateOfBirth' => 'Date Of Birth',
			'descr' => 'About',
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
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('middleName',$this->middleName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('suffix',$this->suffix,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('individualType',$this->individualType);
		$criteria->compare('countryID',$this->countryID);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('dateOfBirth',$this->dateOfBirth,true);
		$criteria->compare('descr',$this->descr,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Individual the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function afterFind()
	{
		if(!empty($this->dateOfBirth))
		{
			$newDate = DateTime::createFromFormat('Y-m-d', $this->dateOfBirth);
			$this->dateOfBirth = $newDate->format('m-d-Y');
		}
		return parent::afterFind();
	}
	
	
	protected function beforeSave(){
		if(!empty($this->dateOfBirth))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->dateOfBirth);
			$this->dateOfBirth = $newDate->format('Y-m-d');
		}
		return parent::beforeSave();
	}
	
	
	/*
	 * Custom slug generator for individual model
	 */
	
	protected function toAscii($replace=array(), $delimiter='-') {
		if(isset($this->id))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$str=trim($this->firstName.' '.$this->middleName.' '.$this->lastName.' '.$this->suffix);
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
