<?php

class LoginFormWidget extends DotPlantWidget {
	public function run() {
		
		if (Yii::app()->user->isGuest == true || is_object(Yii::app()->getModule("User")->user)==false) {
			$model = new LoginForm();
			$this->render("loginForm", array('model'=>$model));
		} else {
			// check if user specified firstName and email
			$user = Yii::app()->getModule("User")->user;
			
			$nameRequired = empty($user->profile->firstName);
			$emailRequired = empty($user->profile->email);

			$this->render("userMenu", array(
					'user'=>$user,
					'nameRequired' => $nameRequired,
					'emailRequired' => $emailRequired,
				));

		}
	}
}