<?php

class ConfigTemplate extends CActiveRecord {

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
		return '{{ConfigTemplate}}';
	}


	public function relations() {
		return array(
			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>45),
				array('view', 'length', 'max'=>255),
				array('SiteConfiguration_id', 'numerical', 'integerOnly'=>true),
				array('name, view, SiteConfiguration_id', 'required'),
				

			);
	}

	public static function handleEdit($SiteConfiguration_id) {
		$models = array();

		if (isset($_POST['ConfigTemplate'])) {
			foreach ($_POST['ConfigTemplate'] as $id => $platform) {
				if (is_numeric($id)) {
					$model = ConfigTemplate::model()->findByPk($id);
					if ($model === null) {
						throw new CHttpException(404, "ConfigTemplate not found.");
					}
				} elseif ($id === 'new') {
					if (!isset($platform['name'], $platform['view'])){
						continue;
					}
					$model = new ConfigTemplate();
					$model->SiteConfiguration_id = $SiteConfiguration_id;
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
	    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving config template details.'));
	    			}
	    		}
			}
		}



		return $models;
	}

}