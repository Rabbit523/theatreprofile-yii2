<?php

/**
 * This is the model class for table "tbl_productionevent".
 *
 * The followings are the available columns in table 'tbl_productionevent':
 * @property integer $id
 * @property integer $productionVenueID
 * @property string $startDate
 * @property integer $type
 * @property integer $recurs
 * @property string $recursStartDate
 * @property string $recursEndDate
 *
 * The followings are the available model relations:
 * @property Productionvenue $productionvenue
 */
class Productionevent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_productionevent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('productionVenueID, startDate', 'required'),
			array('startDate','date','format'=>'mm-dd-yyyy HH:mm'),
			array('recursStartDate,recursEndDate','date','format'=>'mm-dd-yyyy'),
			array('recursStartDate,recursEndDate', 'customValidation'),
			array('productionVenueID, type, recurs', 'numerical', 'integerOnly'=>true),
			array('startDate, recursStartDate, recursEndDate', 'safe'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, productionVenueID, startDate, type, recurs, recursStartDate, recursEndDate', 'safe', 'on'=>'search'),
			array('startDate,recursStartDate,recursEndDate', 'default', 'setOnEmpty' => true, 'value' => null),
		);
	}
	
	
	public function customValidation($attribute,$params)
	{
		$labels = $this->attributeLabels(); // Getting labels of the attributes
		switch($attribute){
			case "recursStartDate":
				if ($this->recurs!=0)
				{
					if(empty($this->recursStartDate)){ 
						 $this->addError($attribute, $labels[$attribute]." cannot be blank."); 
					}
					$newDate1 = DateTime::createFromFormat('m-d-Y H:i', $this->startDate);
					$newDate2 = DateTime::createFromFormat('m-d-Y', $this->recursStartDate);
					$newDate2->setTime(23, 59, 59);
					if($newDate2<$newDate1){ 
						 $this->addError($attribute, $labels[$attribute]." cannot be earlier than event start date."); 
					}
				}
			break;
			case "recursEndDate":
				if ($this->recurs!=0)
				{
					if(empty($this->recursEndDate)){ 
						 $this->addError($attribute, $labels[$attribute]." cannot be blank."); 
					}
				}
			break;
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'productionvenue' => array(self::BELONGS_TO, 'Productionvenue', 'productionVenueID'),
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
			);
		}
		else
		{
			return array();
		}
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'productionVenueID' => 'Production',
			'startDate' => 'Performance Start Time',
			'type' => 'Type',
			'recurs' => 'Recurs',
			'recursStartDate' => 'Recurs Start Date',
			'recursEndDate' => 'Recurs End Date',
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
		$criteria->compare('productionVenueID',$this->productionVenueID);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('recurs',$this->recurs);
		$criteria->compare('recursStartDate',$this->recursStartDate,true);
		$criteria->compare('recursEndDate',$this->recursEndDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Productionevent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	public function afterFind()
	{
		if(!empty($this->startDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate);
			$this->startDate = $newDate->format('m-d-Y H:i');
		}
		if(!empty($this->recursStartDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d', $this->recursStartDate);
			$this->recursStartDate = $newDate->format('m-d-Y');
		}
		if(!empty($this->recursEndDate))
		{
			$newDate = DateTime::createFromFormat('Y-m-d', $this->recursEndDate);
			$this->recursEndDate = $newDate->format('m-d-Y');
		}
		return parent::afterFind();
	}
	
	
	
	protected function beforeSave(){
		if(!empty($this->startDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y H:i', $this->startDate);
			$this->startDate = $newDate->format('Y-m-d H:i:s');
		}
		if(!empty($this->recursStartDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->recursStartDate);
			$this->recursStartDate = $newDate->format('Y-m-d');
		}
		if(!empty($this->recursEndDate))
		{
			$newDate = DateTime::createFromFormat('m-d-Y', $this->recursEndDate);
			$this->recursEndDate = $newDate->format('Y-m-d');
		}
		return parent::beforeSave();
	}
	
	
}
