<?php


class ExecutionQueueController extends CController {
	public function actionIndex() {
		$this->redirect("List");
	}

	public function actionMarkComplete($id) {
		$model = ExecutionQueue::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'Not found.');
		}
		$model->complete = 1;
		$model->dateComplete = date("Y-m-d H:i:s");
		$model->save();
		echo CJSON::encode(array('status'=>'ok'));
		die();
	}
}