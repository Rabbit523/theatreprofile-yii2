<?php

/**
 * This is the model class for table "_import_tttcticketsale".
 *
 * The followings are the available columns in table '_import_tttcticketsale':
 * @property integer $salesID
 * @property integer $venueID
 * @property string $purchaseDate
 * @property integer $boxOffice
 * @property integer $boxOfficeComp
 * @property integer $refundInventory
 * @property integer $refundSale
 * @property string $refundDate
 * @property string $refundReason
 * @property string $title
 * @property integer $eventID
 * @property string $eventDate
 * @property string $section
 * @property string $ticket
 * @property integer $qty
 * @property string $seat
 * @property string $first
 * @property string $last
 * @property string $name
 * @property string $billingName
 * @property string $billingAddress1
 * @property string $billingAddress2
 * @property string $billingCity
 * @property string $billingState
 * @property string $billingZip
 * @property string $email
 * @property string $phone
 * @property string $transactionID
 * @property string $invoiceID
 * @property string $subtotal
 * @property string $fees
 * @property string $netTotal
 * @property string $BOOrderDiscount
 * @property integer $organizationID
 * @property string $organization
 * @property integer $source
 * @property integer $userID
 * @property integer $status
 */
class ImportTTTCTicketSale extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '_import_tttcticketsale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salesID, venueID, title', 'required'),
			array('salesID, venueID, boxOffice, boxOfficeComp, refundInventory, refundSale, eventID, qty, organizationID, source, userID, status', 'numerical', 'integerOnly'=>true),
			array('refundReason, title, section, ticket, seat, phone, organization', 'length', 'max'=>255),
			array('first, last, name, billingName, email', 'length', 'max'=>50),
			array('billingAddress1, billingAddress2, billingCity, billingState', 'length', 'max'=>45),
			array('billingZip, subtotal, fees, netTotal, BOOrderDiscount', 'length', 'max'=>10),
			array('transactionID', 'length', 'max'=>20),
			array('invoiceID', 'length', 'max'=>32),
			array('purchaseDate, refundDate, eventDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('salesID, venueID, purchaseDate, boxOffice, boxOfficeComp, refundInventory, refundSale, refundDate, refundReason, title, eventID, eventDate, section, ticket, qty, seat, first, last, name, billingName, billingAddress1, billingAddress2, billingCity, billingState, billingZip, email, phone, transactionID, invoiceID, subtotal, fees, netTotal, BOOrderDiscount, organizationID, organization, source, userID, status', 'safe', 'on'=>'search'),
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
			'salesID' => 'Sales',
			'venueID' => 'Venue',
			'purchaseDate' => 'Purchase Date',
			'boxOffice' => 'Box Office',
			'boxOfficeComp' => 'Box Office Comp',
			'refundInventory' => 'Refund Inventory',
			'refundSale' => 'Refund Sale',
			'refundDate' => 'Refund Date',
			'refundReason' => 'Refund Reason',
			'title' => 'Title',
			'eventID' => 'Event',
			'eventDate' => 'Event Date',
			'section' => 'Section',
			'ticket' => 'Ticket',
			'qty' => 'Qty',
			'seat' => 'Seat',
			'first' => 'First',
			'last' => 'Last',
			'name' => 'Name',
			'billingName' => 'Billing Name',
			'billingAddress1' => 'Billing Address1',
			'billingAddress2' => 'Billing Address2',
			'billingCity' => 'Billing City',
			'billingState' => 'Billing State',
			'billingZip' => 'Billing Zip',
			'email' => 'Email',
			'phone' => 'Phone',
			'transactionID' => 'Transaction',
			'invoiceID' => 'Invoice',
			'subtotal' => 'Subtotal',
			'fees' => 'Fees',
			'netTotal' => 'Net Total',
			'BOOrderDiscount' => 'Boorder Discount',
			'organizationID' => 'Organization',
			'organization' => 'Organization',
			'source' => 'Source',
			'userID' => 'User',
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

		$criteria->compare('salesID',$this->salesID);
		$criteria->compare('venueID',$this->venueID);
		$criteria->compare('purchaseDate',$this->purchaseDate,true);
		$criteria->compare('boxOffice',$this->boxOffice);
		$criteria->compare('boxOfficeComp',$this->boxOfficeComp);
		$criteria->compare('refundInventory',$this->refundInventory);
		$criteria->compare('refundSale',$this->refundSale);
		$criteria->compare('refundDate',$this->refundDate,true);
		$criteria->compare('refundReason',$this->refundReason,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('eventID',$this->eventID);
		$criteria->compare('eventDate',$this->eventDate,true);
		$criteria->compare('section',$this->section,true);
		$criteria->compare('ticket',$this->ticket,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('seat',$this->seat,true);
		$criteria->compare('first',$this->first,true);
		$criteria->compare('last',$this->last,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('billingName',$this->billingName,true);
		$criteria->compare('billingAddress1',$this->billingAddress1,true);
		$criteria->compare('billingAddress2',$this->billingAddress2,true);
		$criteria->compare('billingCity',$this->billingCity,true);
		$criteria->compare('billingState',$this->billingState,true);
		$criteria->compare('billingZip',$this->billingZip,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('transactionID',$this->transactionID,true);
		$criteria->compare('invoiceID',$this->invoiceID,true);
		$criteria->compare('subtotal',$this->subtotal,true);
		$criteria->compare('fees',$this->fees,true);
		$criteria->compare('netTotal',$this->netTotal,true);
		$criteria->compare('BOOrderDiscount',$this->BOOrderDiscount,true);
		$criteria->compare('organizationID',$this->organizationID);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImportTTTCTicketSale the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return array('salesID','source');
	}
}
