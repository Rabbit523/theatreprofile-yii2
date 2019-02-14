<?php

/**
 * This is the model class for table "tbl_tttcvenue".
 *
 * The followings are the available columns in table 'tbl_tttcvenue':
 * @property integer $id
 * @property integer $tpVenueID
 * @property string $venueName
 * @property integer $capacity
 * @property integer $addressID
 * @property integer $tttcCompanyID
 * @property integer $source
 *
 * The followings are the available model relations:
 * @property Tttcproduction[] $tttcproductions
 * @property Address $address
 * @property Tttccompany $tttcCompany
 */
class TTTCVenue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_tttcvenue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('venueName', 'required'),
			array('tpVenueID, capacity, addressID, tttcCompanyID, source', 'numerical', 'integerOnly'=>true),
			array('venueName', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tpVenueID, venueName, capacity, addressID, tttcCompanyID, source', 'safe', 'on'=>'search'),
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
			'tttcproductions' => array(self::HAS_MANY, 'Tttcproduction', 'tttcVenueID'),
			'address' => array(self::BELONGS_TO, 'Address', 'addressID'),
			'tttcCompany' => array(self::BELONGS_TO, 'Tttccompany', 'tttcCompanyID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tpVenueID' => 'Tp Venue',
			'venueName' => 'Venue Name',
			'capacity' => 'Capacity',
			'addressID' => 'Address',
			'tttcCompanyID' => 'Tttc Company',
			'source' => 'Source',
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
		$criteria->compare('tpVenueID',$this->tpVenueID);
		$criteria->compare('venueName',$this->venueName,true);
		$criteria->compare('capacity',$this->capacity);
		$criteria->compare('addressID',$this->addressID);
		$criteria->compare('tttcCompanyID',$this->tttcCompanyID);
		$criteria->compare('source',$this->source);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TTTCVenue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return array('source','id');
	}
}
