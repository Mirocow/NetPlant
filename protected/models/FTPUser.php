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

	public static function handleEdit($Platform_id) {
		$models = array();

		if (isset($_POST['FTPUser'])) {
			foreach ($_POST['FTPUser'] as $id => $ftpUser) {
				if (is_numeric($id)) {
					$model = FTPUser::model()->findByPk($id);
					if ($model === null) {
						throw new CHttpException(404, "FTPUser not found.");
					}
				} elseif ($id === 'new') {
					if (!isset($ftpUser['name'], $ftpUser['SiteConfiguration_id'])){
						continue;
					}
					$model = new FTPUser();
					$model->Platform_id = $Platform_id;
				} else {
					throw new CHttpException(400, "Bad request.");
				}

				$model->setAttributes($ftpUser);

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
	    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving FTPUser details.'));
	    			}
	    		}
			}
		}
		return $models;
	}
}