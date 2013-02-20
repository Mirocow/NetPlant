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
				array('Server_id', 'numerical', 'integerOnly'=>true),
				array('name, systemUser', 'required'),
			);
	}


	public static function handleEdit($accoountId) {
		$models = array();

		if (isset($_POST['Platform'])) {
			foreach ($_POST['Platform'] as $id => $platform) {
				if (is_numeric($id)) {
					$model = Platform::model()->findByPk($id);
					if ($model === null) {
						throw new CHttpException(404, "Platform not found.");
					}
				} elseif ($id === 'new') {
					if (!isset($platform['name'], $platform['systemUser'], $platform['Server_id'])){
						continue;
					}
					$model = new Platform();
					$model->Account_id = $accoountId;
				} else {
					throw new CHttpException(400, "Bad request.");
				}

				$model->setAttributes($platform);

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
	    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving platform details.'));
	    			}
	    		}
			}
		}



		return $models;
	}
}