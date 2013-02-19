<?php

class UserController extends CController {


	public function behaviors() {
		return array(
			'Controller',
			);
	}

	// @todo add META NOINDEX,NOFOLLOW HERE, just for the case
	public function actionLogin() {
		
		$model = new LoginForm();
		
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];

			if (isset($_POST['ajax'])) {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			if ($model->validate() && $model->login('StandardIdentity')) {
				
				$this->redirect(Yii::app()->user->returnUrl);
			} else {

			}
		} else {
			if ($model->login()) {
				
				$this->redirect(Yii::app()->user->returnUrl);
			}

			
		}

		$registrationForm = new RegistrationForm();
		$this->render("login", array(
			'loginForm'=>$model,
			'registrationForm'=>$registrationForm,
			));
	}

	public function actionRegistration() {
		$registrationForm = new RegistrationForm();
		if (isset($_POST['RegistrationForm'])) {
			$registrationForm->attributes = $_POST['RegistrationForm'];
			if (isset($_POST['ajax'])) {
				echo CActiveForm::validate($registrationForm);
				Yii::app()->end();
			}

			if ($registrationForm->validate()) {
				$user = new User();
				$user->username = $registrationForm->username;
				
				$user->active = 1;
				$user->save();
				$userProfile = new UserProfile();
				$userProfile->email = $registrationForm->email;
				$userProfile->userId = $user->id;
				$userProfile->save();
				$userAuth = new UserAuth();
				$userAuth->identityClass = 'StandardIdentity';
				$userAuth->userId = $user->id;
				$userAuth->password = $registrationForm->password;
				$userAuth->save();

				$loginForm = new LoginForm();
				$loginForm->username = $user->username;
				$loginForm->password = $registrationForm->password;
				$loginForm->rememberMe = 1;
				$loginForm->validate();
				$loginForm->login('StandardIdentity');

				$this->raiseEvent("onAfterUserRegistered", new CEvent($this, array('user'=>&$user)));

				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		$this->render("registrationForm", array(
			'model'=>$registrationForm,
			));
	}

	public function onAfterUserRegistered($event) {

	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->user->returnUrl);
	}

	public function actionProfileUpdate() {
		if (Yii::app()->user->isGuest == true) {
			throw new CHttpException(403);
		}

		if (is_object($this->module->user)==false){
			$this->redirect(array("/User/user/login"));
		}

		$model = $this->module->user->profile;
		
		if (isset($_POST['UserProfile']) && (@$_POST['ajax'] ==='mini-profile-form' || @$_POST['ajax']==='profileForm')) {
			// weird? yes. @todo check if it works correctly
			$model->attributes = $_POST['UserProfile'];
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (isset($_POST['UserProfile'])) {
			$model->attributes = $_POST['UserProfile'];
			try {
				$model->save();
				Yii::app()->user->setFlash('profile', Yii::t('User', 'Your profile was updated.'));
			} catch (Exception $e) {};
			
			//$this->redirect(array("/User/user/profileUpdate"));
		}
		$this->render("profile", array('model'=>$model,));
		
	}

	public function filters() {
        return array(
                array(
                        'ESetReturnUrlFilter - login, logout, redirect, registration',
                    ),
            );
    }
}