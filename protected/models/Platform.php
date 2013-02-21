<?php

class Platform extends CActiveRecord {
	public $oldAttributes = array();
	public $password="";

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
				array('Server_id, allowSsh', 'numerical', 'integerOnly'=>true),
				array('name, systemUser', 'required'),
				array('password', 'length', 'max'=>45),
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

	public function afterFind() {
		$this->oldAttributes = $this->getAttributes();
		return true;
	}

	public function beforeSave() {
		if (isset($this->oldAttributes["Server_id"])) {
			$this->Server_id = $this->oldAttributes["Server_id"];// don't change server_id!!!!
		}
		return true;
	}

	public function afterSave() {
		$isNewUser = false;

		$changedAttributes = array_diff($this->getAttributes(), $this->oldAttributes);

		if (count($changedAttributes) > 0) {

			$queueScript = ExecutionQueue::getLast("Platform changed");



			if (isset($changedAttributes["systemUser"])) {
				if (!isset($this->oldAttributes["systemUser"])) {
					// its new user
					$queueScript->script .= "# create new user \n";
					$user = escapeshellcmd($this->systemUser);
					$password = empty($this->password) ? $this->generatePassword() : $this->password;
					$password = escapeshellcmd($password);
					
					$command = "useradd -p \\`openssl passwd -1 $password\\` $user";
					$queueScript->script .= "ssh root@".$this->server->ip . " '$command' || exit 1\n";

					$command = "mkdir -p /home/$user/sites/";
					$queueScript->script .= "ssh root@".$this->server->ip . " '$command' || exit 1\n";

					$command = "chown -R $user:$user /home/$user/";
					$queueScript->script .= "ssh root@".$this->server->ip . " '$command' || exit 1\n";					

					$queueScript->script .= "# shell for new user\n";
					$queueScript->script .= $this->getShellCommand()."\n\n";
					$isNewUser = true;
				} else {
					// its old user 
					$queueScript->script .= "# rename existing user \n";
					$command = "usermod -l " . escapeshellcmd($this->oldAttributes["systemUser"]) . " " . escapeshellcmd($this->systemUser);
					$queueScript->script .= "ssh root@".$this->server->ip . " '$command' || exit 1\n";
				}
			}
			if (isset($changedAttributes['allowSsh']) && $isNewUser == false){
				$queueScript->script .= "# change shell \n";
				$queueScript->script .= $this->getShellCommand()."\n\n";
			}
			
		}

		if (!empty($this->password) && $isNewUser == false) {
			if (!isset($queueScript)) {
				$queueScript = ExecutionQueue::getLast("Platform password changed");
			}
			$queueScript->script .= "# change password for existing user! \n";
			// change password only for existing user!
			$user = escapeshellcmd($this->systemUser);
			$password = escapeshellcmd($this->password);

			$command = "echo -e \"$password\\n$password\" | (passwd --stdin $user)";
			$queueScript->script .= "ssh root@".$this->server->ip . " '$command' || exit 1\n"."\n\n";
		}



		return true;
	}

	private function getShellCommand() {
		if ($this->allowSsh) {
			$command = "chsh --shell=/bin/bash ".escapeshellcmd($this->systemUser);
		} else {
			$command = "chsh --shell=/bin/false ".escapeshellcmd($this->systemUser);
		}
		return "ssh root@".$this->server->ip . " '$command' || exit 1\n";
	}

	private function generatePassword($length = 18) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);

	    for ($i = 0, $result = ''; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }

	    return $result;
	}
}