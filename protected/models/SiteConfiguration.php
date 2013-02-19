<?php

class SiteConfiguration extends CActiveRecord {

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
		return '{{SiteConfiguration}}';
	}


	public function relations() {
		return array(
				'template' => array(self::MANY_MANY, "ConfigTemplate", "SiteConfigurationConfigTemplate(SiteConfiguration_id, ConfigTemplate_id)"),

			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>255),
				array('handlerClass', 'length', 'max'=>80),
				

			);
	}

}