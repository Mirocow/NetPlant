<?php
class RegistrationForm extends CFormModel {
	public $username;
	public $password;
	public $email;

	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			array('username', 'unique', 'className'=>'User',),
			array('email', 'email'),
		);
	}

	public function attributeLabels() {
		return array(
			'email'=>Yii::t('User', 'E-Mail'),
			'username' => Yii::t('User', 'Username'),
			'password' => Yii::t('User', 'Password'),
			);
	}

}