<?php

class Platform extends CActiveRecord {

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
		return '{{Platform}}';
	}


	public function relations() {
		return array(
				'account' => array(self::BELONGS_TO, "Account", "Account_id"),

				'server' => array(self::BELONGS_TO, "Server", "Server_id"),

				'ftpUsers' => array(self::HAS_MANY, 'FTPUser', 'Platform_id'),
				'databases' => array(self::HAS_MANY, 'Database', 'Platform_id'),
				'sites' => array(self::HAS_MANY, 'Site', 'Platform_id'),
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