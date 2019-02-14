<?php

/**
 * This is the model class for table "tbl_feeditem".
 *
 * The followings are the available columns in table 'tbl_feeditem':
 * @property integer $id
 * @property integer $feedID
 * @property string $title
 * @property string $descr
 * @property string $href
 * @property string $publishDate
 *
 * The followings are the available model relations:
 * @property Feed $feed
 */
class Feeditem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_feeditem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feedID', 'required'),
			array('feedID', 'numerical', 'integerOnly'=>true),
			array('title, href', 'length', 'max'=>1000),
			array('descr', 'length', 'max'=>5000),
			array('publishDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, feedID, title, descr, href, publishDate', 'safe', 'on'=>'search'),
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
			'feed' => array(self::BELONGS_TO, 'Feed', 'feedID'),
		);
	}
	
	/**
	 * @return array behavior rules.
	 */
	public function behaviors()
    {
        return array(
			'seo'=>array(
				'class'=>'ext.seo.behaviors.SeoActiveRecordBehavior',
				'route'=>'news/view',
				'params'=>array('id'=>$this->id, 'title'=>$this->toAscii()),
			),
        );
    }
	

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'feedID' => 'Feed',
			'title' => 'Title',
			'descr' => 'Descr',
			'href' => 'Href',
			'publishDate' => 'Publish Date',
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
		$criteria->compare('feedID',$this->feedID);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('href',$this->href,true);
		$criteria->compare('publishDate',$this->publishDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Feeditem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * Custom slug generator for feed item model
	 */
	
	protected function toAscii($replace=array(), $delimiter='-') {
		if(isset($this->id))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$str = $this->title;
			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			return $clean;
		}
	}
}
