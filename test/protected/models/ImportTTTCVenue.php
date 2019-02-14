<?php

/**
 * This is the model class for table "_import_tttcvenue".
 *
 * The followings are the available columns in table '_import_tttcvenue':
 * @property integer $venueID
 * @property string $venueName
 * @property integer $capacity
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $latitude
 * @property string $longitude
 * @property integer $organizationID
 * @property string $organization
 * @property integer $source
 * @property integer $status
 */
class ImportTTTCVenue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '_import_tttcvenue';
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
			array('capacity, organizationID, source, status', 'numerical', 'integerOnly'=>true),
			array('venueName, organization', 'length', 'max'=>100),
			array('address, city, state', 'length', 'max'=>45),
			array('latitude', 'length', 'max'=>10),
			array('longitude', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('venueID, venueName, capacity, address, city, state, latitude, longitude, organizationID, organization, source, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'venueID' => 'Venue',
			'venueName' => 'Venue Name',
			'capacity' => 'Capacity',
			'address' => 'Address',
			'city' => 'City',
			'state' => 'State',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'organizationID' => 'Organization',
			'organization' => 'Organization',
			'source' => 'Source',
			'status' => 'Status',
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

		$criteria->compare('venueID',$this->venueID);
		$criteria->compare('venueName',$this->venueName,true);
		$criteria->compare('capacity',$this->capacity);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('organizationID',$this->organizationID);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImportTTTCVenue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return array('venueID','source');
	}
}
