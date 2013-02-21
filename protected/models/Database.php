<?php

class Database extends CActiveRecord {
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
		return '{{Database}}';
	}


	public function relations() {
		return array(
				'platform' => array(self::BELONGS_TO, "Platform", "Platform_id"),
			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>45),
				array('active', 'numerical', 'integerOnly'=>true,),
				array('name', 'required'),
				
				

			);
	}

	public function afterSave() {
		if (isset($this->password)) {
			// call password change here
		}

		return true;
	}

	public static function handleEdit($Platform_id) {
		$models = array();

		if (isset($_POST['Database'])) {
			foreach ($_POST['Database'] as $id => $database) {
				if (is_numeric($id)) {
					$model = Database::model()->findByPk($id);
					if ($model === null) {
						throw new CHttpException(404, "Database not found.");
					}
				} elseif ($id === 'new') {
					if (!isset($database['name'], $database['SiteConfiguration_id'])){
						continue;
					}
					$model = new Database();
					$model->Platform_id = $Platform_id;
				} else {
					throw new CHttpException(400, "Bad request.");
				}

				$model->setAttributes($database);

				if (Yii::app()->request->isAjaxRequest) {
	    			// its validation request
	    			echo CActiveForm::validate($model);
					Yii::app()->end();
	    		} else {
	    			// its save request
	    			if ($model->save()) {
	    				$models[]=$model;
	    				// call saving related here

	    			} else {
	    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving database details.'));
	    			}
	    		}
			}
		}
		return $models;
	}
}