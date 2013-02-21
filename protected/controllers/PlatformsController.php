<?php

class PlatformsController extends CController {
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
    	$model = new Platform('search');
    	if (isset($_GET['Platform'])) {
			$model->setAttributes($_GET['Platform']);
		}
		$params = array(
				'id' => 'platforms-grid',
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
                        'systemUser',
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
    		$model = Platform::model()->findByPk($id);
    		if ($model === null) {
    			throw new CHttpException(404, "Not found.");
    		}
    	} else {
            // platform creation is(and actually should be) handled in accounts controller
    		throw new CHttpException(400, "Bad request.");
    	}

    	if (isset($_POST['Platform'])) {
    		$model->setAttributes($_POST['Platform']);

    		if (Yii::app()->request->isAjaxRequest) {
    			// its validation request
    			echo CActiveForm::validate($model);
				Yii::app()->end();
    		} else {
    			// its save request
    			if ($model->save()) {

                    
                    Site::handleEdit($model);
                    Database::handleEdit($model->id);
                    FTPUser::handleEdit($model->id);

                    Yii::app()->cache->clear('Site','Database','FTPUser');

    				Yii::app()->user->setFlash('success', Yii::t('Site', 'Changes saved successfully.'));
    				if ($id == 'new') {
    					$this->redirect(array('Platforms/Edit', 'id'=>$model->id));
    				}
    			} else {
    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving details.'));
    			}
    		}
    	}

        $existingServers = Yii::app()->cache->get("existingServers");
        if (!is_array($existingServers) || $existingServers === false) {
            $existingServers = array();
            $servers = Server::model()->findAll();
            foreach ($servers as $server) {
                $existingServers[$server->id] = $server->name . ' - ' . $server->ip;
            }
            $servers = null;
            Yii::app()->cache->set("existingServers", $existingServers, 86400, new TagDependency("Server"));
        }

        $existingSiteConfigurations = Yii::app()->cache->get("existingSiteConfigurations");
        if (!is_array($existingSiteConfigurations) || $existingSiteConfigurations === false) {
            $existingSiteConfigurations = array();
            $siteConfigurations = SiteConfiguration::model()->findAll();
            foreach ($siteConfigurations as $siteConf) {
                $existingSiteConfigurations[$siteConf->id] = $siteConf->name . ' - ' . $siteConf->handlerClass;
            }
            $siteConfigurations = null;
            Yii::app()->cache->set("existingSiteConfigurations", $existingSiteConfigurations, 86400, new TagDependency("SiteConfiguration"));
        }



    	$this->render("edit", array(
    			'model' => $model,
                'existingServers' => $existingServers,
                'existingSiteConfigurations' => $existingSiteConfigurations,
    		));
    }
}