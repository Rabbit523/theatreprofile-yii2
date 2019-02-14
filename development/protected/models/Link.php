<?php

/**
 * This is the model class for table "tbl_link".
 *
 * The followings are the available columns in table 'tbl_link':
 * @property integer $id
 * @property integer $profileID
 * @property integer $profileType
 * @property string $href
 * @property integer $linkType
 */
class Link extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileID, profileType, linkType', 'numerical', 'integerOnly'=>true),
			array('href', 'length', 'max'=>500),
			array('label', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileID, profileType, href, linkType,label', 'safe', 'on'=>'search'),
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
			'productionVenueLinks' => array(self::BELONGS_TO, 'Productionvenue', array('profileID' => 'id'), 
				  'joinType' => 'INNER JOIN', 
				  'on' => "profileType=5"
			),
			'productionLinks' => array(self::BELONGS_TO, 'Productionvenue', array('profileID' => 'id'), 
				  'joinType' => 'INNER JOIN', 
				  'on' => "profileType=2"
			),
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
			'profileID' => 'Profile',
			'profileType' => 'Profile Type',
			'href' => 'Href',
			'linkType' => 'Link Type',
			'label' => 'Label',
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
		$criteria->compare('profileID',$this->profileID);
		$criteria->compare('profileType',$this->profileType);
		$criteria->compare('href',$this->href,true);
		$criteria->compare('linkType',$this->linkType);
		$criteria->compare('label',$this->label);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Link the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeSave(){
		if (!preg_match("~^https?://~i", $this->href)) {
			$this->href = "http://".$this->href;
		}
		return parent::beforeSave();
	}
}
