<?php

class Account extends CActiveRecord {

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
		return '{{Account}}';
	}


	public function relations() {
		return array(
				'users' => array(self::MANY_MANY, "User", "AccountUser(Account_id,User_id)"),
			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>45),
				array('dateRegistered', 'safe'),
			);
	}

}