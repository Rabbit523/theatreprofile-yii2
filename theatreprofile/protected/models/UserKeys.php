<?php

/**
 * This is the model class for table "user_keys".
 *
 * The followings are the available columns in table 'user_keys':
 * @property string $id
 * @property integer $userID
 * @property string $activeKey
 * @property string $ipAddress
 * @property string $expirationDate
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserKeys extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_keys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID, activeKey, ipAddress', 'required'),
			array('userID, status', 'numerical', 'integerOnly'=>true),
			array('activeKey', 'length', 'max'=>128),
			array('ipAddress', 'length', 'max'=>16),
			array('expirationDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userID, activeKey, ipAddress, expirationDate, status', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'userID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userID' => 'User',
			'activeKey' => 'Active Key',
			'ipAddress' => 'Ip Address',
			'expirationDate' => 'Expiration Date',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('activeKey',$this->activeKey,true);
		$criteria->compare('ipAddress',$this->ipAddress,true);
		$criteria->compare('expirationDate',$this->expirationDate,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
		/**
	 * Generates/updates user key
	 */
	public static function generateKey($userID)
	{
		$model=UserKeys::model()->findByAttributes(array('userID'=>$userID));
		if(!$model)
		{
			$model=new UserKeys;
			$model->userID = $userID;
		}
		$user = User::model()->findByPk($userID);
		$salt = UserModule::generateBcryptSalt();
		$model->activeKey=UserModule::encrypting(microtime().$user->email,2,$salt);
		$model->ipAddress=Yii::app()->request->userHostAddress;
		//$date = new DateTime();
		//$date->modify("+2 hour");
		//$model->expirationDate = $date->format('Y-m-d H:i:s');
		$model->expirationDate = null;
		$model->status = 0;
		echo "activeKey in user model";
		echo $model->activeKey; exit();

		if($model->validate())
		{
			echo "usermodal validate"; exit();
			$model->save();
		}		
		return $model->activeKey;
	}
	
	/**
	 * Verifies user key
	 */
	public static function validateKey($key='$2a$14$KhIlgUeLBDHg3OhaClKaR.SF950C/omftMG6Ui1axMRdreGtqQ4B2')
	{
		$model=new UserKeys;	
		$model=UserKeys::model()->findByAttributes(array('activeKey'=>$key));
		if($model&&$model->status==0)
		{
			if($model->expirationDate)
			{
				$date = new DateTime();
				$newDate = $date->format('Y-m-d H:i:s');
				if($newDate>$model->expirationDate)
					return null;
				else
					return $model;
			}
			else
				return $model;
		}
		else
			return null;
	}
	
	/**
	 * Deletes user key
	 */
	public static function invalidateKey($userID)
	{
		$model=UserKeys::model()->findByAttributes(array('userID'=>$userID));
		if($model)
		{
			$model->status=1;
			$model->save();
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserKeys the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
