<?php

class AccountsController extends CController {
	public function filters()
    {
        return array(
            'accessControl',
        );
    }

	public function accessRules()
    {
        return array(
            array('deny',
                'users'=>array('?'),
            ),
        );
    }

    public function actionIndex() {
    	$model = new Account('search');
    	if (isset($_GET['Account'])) {
			$model->setAttributes($_GET['Account']);
		}
		$params = array(
				'id' => 'accounts-grid',
				'type'=>'striped bordered condensed',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'pager'=>array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true),
				'template'=>"{summary} {pager} {items} {pager}",
				'fixedHeader' => true,
				'headerOffset' => 40,
				'responsiveTable' => true,
				'columns' => array(
						'id',
						'name',
						'dateRegistered',
						array(
								'htmlOptions' => array('nowrap'=>'nowrap'),
								'class'=>'bootstrap.widgets.TbButtonColumn',
								'buttons'=>array(
				                	'update' => array(
			                			'label'=>'Редактировать',
			                			'icon'=>'icon-pencil',
			                			'options'=>array('class'=>'btn btn-small'),
			                			'url'=> 'Yii::app()->controller->createUrl("edit",array("id"=>$data->primaryKey))',
				                		),
				                	'delete' => array(
									    'label'=>'Удалить',
									    'icon'=>'icon-remove',
									    'options'=>array('class'=>'btn btn-small delete'),
									    'url'=> 'Yii::app()->controller->createUrl("delete",array("id"=>$data->primaryKey))',
									),

				                ),
				                'template'=>'{update} {delete}',
							),
					),
			);

		$this->render('index', array(
				'model' => $model,
				'params' => $params,
			));
    }

    public function actionEdit($id) {
    	if (is_numeric($id)) {
    		$model = Account::model()->findByPk($id);
    		if ($model === null) {
    			throw new CHttpException(404, "Not found.");
    		}
    	} elseif ($id==='new') {
    		$model = new Account;
    	} else {
    		throw new CHttpException(400, "Bad request.");
    	}

    	if (isset($_POST['Account'])) {
    		$model->setAttributes($_POST['Account']);

    		if (Yii::app()->request->isAjaxRequest) {
    			// its validation request
    			echo CActiveForm::validate($registrationForm);
				Yii::app()->end();
    		} else {
    			// its save request
    			if ($model->save()) {
    				Yii::app()->user->setFlash('success', Yii::t('Site', 'Changes saved successfully.'));
    				if ($id == 'new') {
    					$this->redirect(array('Accounts/Edit', 'id'=>$model->id));
    				}
    			} else {
    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving details.'));
    			}
    		}
    	}

    	$existingUsers = Yii::app()->cache->get("existingUsers");
    	if (!is_array($existingUsers) || $existingUsers === false) {
    		$users = User::model()->findAll();
    		foreach ($users as $user) {
    			$existingUsers['id'] = $user->username;
    		}
    		$users = null;
    		Yii::app()->cache->set("existingUsers", $existingUsers, 86400, new TagDependency("User"));
    	}


    	$this->render("edit", array(
    			'model' => $model,
    			'existingUsers' => $existingUsers,
    		));
    }
}