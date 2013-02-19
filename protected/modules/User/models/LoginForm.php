<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			//array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>Yii::t('User', 'Remember me'),
			'username' => Yii::t('User', 'Username'),
			'password' => Yii::t('User', 'Password'),

		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new StandardIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate()) {
			$this->addError('password',Yii::t('User', 'Bad username or password.'));
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login($identityClass=null)
	{

		if ($identityClass !== null) {
			$this->_identity = new $identityClass($this->username, $this->password);
		}
		if($this->_identity===null  && Yii::app()->getModule("User")->loginzaEnabled)
		{
			$this->_identity=new LoginzaIdentity();
			
		}
		if($this->_identity===null) {
			return false;
		}

		if($this->_identity->authenticate())
		{

			//! @todo Make configurable
			$duration=$this->rememberMe ? 3600*24*30 : 3600*24*7; // 30 days
			
			Yii::app()->user->login($this->_identity,$duration);
			
			return true;
		}
		else {

			return false;
		}
	}
}
