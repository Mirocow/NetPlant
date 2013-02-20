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
			                			'label'=>Yii::t('Site','Edit'),
			                			'icon'=>'icon-pencil',
			                			'options'=>array('class'=>'btn btn-small'),
			                			'url'=> 'Yii::app()->controller->createUrl("edit",array("id"=>$data->primaryKey))',
				                		),
				                	'delete' => array(
									    'label'=>Yii::t('Site','Remove'),
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
    			echo CActiveForm::validate($model);
				Yii::app()->end();
    		} else {
    			// its save request
    			if ($model->save()) {

    				// save related users
    				if (isset($_POST['Account']['users'])) {
    					$users = $model->getRelated("users", true);
    					
    					$existingIds = array();

    					foreach ($users as $user) {
    						if (!in_array($user->id, $_POST['Account']['users'])) {
    							$criteria = new CDbCriteria;
    							$criteria->condition = "User_id=:User_id AND Account_id=:Account_id";
    							$criteria->params = array(':User_id' => $user->id, ':Account_id' => $model->id);

    							Yii::app()->db->getCommandBuilder()
    								->createDeleteCommand("AccountUser", $criteria)
    								->execute();
    						} else {
    							$existingIds[] = $user->id;
    						}
    					}
    					$idsToAdd = array_diff($_POST['Account']['users'],$existingIds);
    					
    					foreach ($idsToAdd as $id) {
    						Yii::app()->db->getCommandBuilder()
    								->createInsertCommand("AccountUser", array(
    										'Account_id' => $model->id,
    										'User_id' => $id,
    									)
    								)
    								->execute();
    					}
    					$users = $model->getRelated("users", true);
    				}
    				// now process platform
    				Platform::handleEdit($model->id);

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
    			$existingUsers[$user->id] = $user->username;
    		}
    		$users = null;
    		Yii::app()->cache->set("existingUsers", $existingUsers, 86400, new TagDependency("User"));
    	}

        $existingServers = Yii::app()->cache->get("existingServers");
        if (!is_array($existingServers) || $existingServers === false) {
            $servers = Server::model()->findAll();
            foreach ($servers as $server) {
                $existingServers[$server->id] = $server->name . ' - ' . $server->ip;
            }
            $servers = null;
            Yii::app()->cache->set("existingServers", $existingServers, 86400, new TagDependency("Server"));
        }


    	$this->render("edit", array(
    			'model' => $model,
    			'existingUsers' => $existingUsers,
                'existingServers' => $existingServers,
    		));
    }
}