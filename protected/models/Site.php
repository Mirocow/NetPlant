<?php

class Site extends CActiveRecord {
	private $oldAttributes = array();

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
				array('SiteConfiguration_id', 'numerical', 'integerOnly'=>true),
				array('name, SiteConfiguration_id', 'required'),
			);
	}

	public function afterFind() {
		$this->oldAttributes = $this->getAttributes();
		return true;
	}

	public function handleSaveEvents($platform, $isNewRecord) {
		
		if ($isNewRecord) {
			
			// its new site
			$queueScript = ExecutionQueue::getLast("Platform changed");
			// we must create file with configuration
			$queueScript->script .= $this->configuration->createSite($platform, $this);

			// and upload them

		} else {
			// its old site


		}

		
		return true;
	}

	public static function handleEdit($platform) {
		$models = array();

		if (isset($_POST['Site'])) {
			foreach ($_POST['Site'] as $id => $site) {
				if (is_numeric($id)) {
					$model = Site::model()->findByPk($id);
					if ($model === null) {
						throw new CHttpException(404, "Site not found.");
					}
				} elseif ($id === 'new') {
					if (!isset($site['name'], $site['SiteConfiguration_id'])){
						continue;
					}
					$model = new Site();
					$model->Platform_id = $platform->id;
				} else {
					throw new CHttpException(400, "Bad request.");
				}

				$model->setAttributes($site);

				if (Yii::app()->request->isAjaxRequest) {
	    			// its validation request
	    			echo CActiveForm::validate($model);
					Yii::app()->end();
	    		} else {
	    			// its save request
	    			if ($model->save()) {
	    				$model->handleSaveEvents($platform, $id==='new');
	    				$models[]=$model;
	    				// call saving related here

	    			} else {
	    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving site details.'));
	    			}
	    		}
			}
		}
		return $models;
	}
}