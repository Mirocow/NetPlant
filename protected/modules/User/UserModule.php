<?php

class UserModule extends CWebModule {
	public $loginzaEnabled = false;
	public $registrationEnabled = true;

	public $user = null;
	public $behaviors = array(
			'Module',
			);
	
	public function init() {
		Yii::import("zii.behaviors.CTimestampBehavior");
		Yii::import("application.modules.User.models.*");
		Yii::import("application.modules.User.components.*");
		$this->initUser();
		return true;
	}
	public function initUser() {

		if (Yii::app()->user->isGuest == false) {

			$this->user = Yii::app()->cache->get("User:".Yii::app()->user->id);
			
			if (!is_object($this->user)) {
				$this->user = User::model()->with('profile')->together(true)->findByPk(Yii::app()->user->id);

				Yii::app()->cache->set("User:".Yii::app()->user->id, $this->user, 86400);
			}
		}

	}
}