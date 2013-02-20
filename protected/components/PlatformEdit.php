<?php

class PlatformEdit extends CWidget {
	public $accountId = null;
	public $models = array();
	public $form = null;
	public $existingServers = array();

	public function run() {
		if ($this->form === null) {
			throw new CHttpException(500, "Form is null.");
		}
		$this->render("platformEdit", array(
				'accountId' => $this->accountId,
				'models' => $this->models,
				'form' => $this->form,
				'existingServers' => $this->existingServers,
			));
	}
}