<?php

class Site extends CActiveRecord {

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
		return '{{Site}}';
	}


	public function relations() {
		return array(
				'platform' => array(self::BELONGS_TO, "Platform", "Platform_id"),

				'configuration' => array(self::BELONGS_TO, "SiteConfiguration", "SiteConfiguration_id"),


			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>120),
				array('aliases', 'safe'),
				array('active, deleted', 'numerical', 'integerOnly'=>true),
				

			);
	}

}