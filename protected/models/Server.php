<?php

class Server extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Config the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{Server}}';
	}


	public function relations() {
		return array(
				'platform' => array(self::HAS_MANY, "Platform", "Server_id"),
				
			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>255),
				array('dateRegistered', 'safe'),
				array('systemUser', 'length', 'max'=>45),

			);
	}

}