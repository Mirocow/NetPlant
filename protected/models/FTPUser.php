<?php

class FTPUser extends CActiveRecord {
	//! @var string Password, if we need to change it
	public $password = "";

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
		return '{{FTPUser}}';
	}


	public function relations() {
		return array(
				'platform' => array(self::BELONGS_TO, "Platform", "Platform_id"),
			);
	}

	public function rules() {
		return array(
				array('username', 'length', 'max'=>45),
				array('chroot', 'length', 'max'=>255),
				array('username, chroot', 'required'),
			);
	}

	public function afterSave() {
		if (isset($this->password)) {
			// call password change here
		}

		return true;
	}
}