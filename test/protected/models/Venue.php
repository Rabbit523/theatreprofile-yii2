<?php

/**
 * This is the model class for table "tbl_venue".
 *
 * The followings are the available columns in table 'tbl_venue':
 * @property integer $id
 * @property string $venueName
 * @property integer $addressID
 * @property string $descr
 *
 * The followings are the available model relations:
 * @property Productionvenue[] $productionvenues
 * @property Address $address
 * @property Venueownership[] $venueownerships
 * @property Venuewatchlist[] $venuewatchlists
 */
class Venue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_venue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('venueName','required'),
			array('addressID', 'numerical', 'integerOnly'=>true),
			array('venueName', 'length', 'max'=>100),
			array('descr', 'length', 'max'=>3000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, venueName, addressID, descr','safe', 'on'=>'search'),
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
			'productionvenues' => array(self::HAS_MANY, 'Productionvenue', 'venueID', 'with'=>'production', 'order'=>'case (ifnull(productionvenues.startDate,production.startDate) is null and ifnull(productionvenues.endDate,production.endDate) is null) when 0 then case (ifnull(productionvenues.startDate,production.startDate) is not null and ifnull(productionvenues.endDate,production.endDate) is null) when 1 then 1 else 2 end else 3 end,ifnull(productionvenues.endDate,production.endDate) desc,ifnull(productionvenues.startDate,production.startDate) desc'),
			'address' => array(self::BELONGS_TO, 'Address', 'addressID'),
			'venueownerships' => array(self::HAS_MANY, 'Venueownership', 'venueID'),
			'venuewatchlists' => array(self::HAS_MANY, 'Venuewatchlist', 'venueID'),
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
					'route'=>'venue/view',
					'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
				),
			);
		}
		else
		{
			return array(
				'seo'=>array(
					'class'=>'theatreprofile.extensions.seo.behaviors.SeoActiveRecordBehavior',
					'route'=>'venue/view',
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
			'venueName' => 'Venue Name',
			'addressID' => 'Address',
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
		$criteria->compare('venueName',$this->venueName,true);
		$criteria->compare('addressID',$this->addressID);
		$criteria->compare('descr',$this->descr,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Venue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * Custom slug generator for venue model
	 */
	
	protected function toAscii($replace=array(), $delimiter='-') {
		if(isset($this->id))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$str=$this->venueName;
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
