<?php

class SiteConfigurationsController extends CController {
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
    	$model = new SiteConfiguration('search');
    	if (isset($_GET['SiteConfiguration'])) {
			$model->setAttributes($_GET['SiteConfiguration']);
		}
		$params = array(
				'id' => 'siteConfigurations-grid',
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
						'handlerClass',
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
    		$model = SiteConfiguration::model()->findByPk($id);
    		if ($model === null) {
    			throw new CHttpException(404, "Not found.");
    		}
    	} elseif ($id==='new') {
    		$model = new SiteConfiguration;
    	} else {
    		throw new CHttpException(400, "Bad request.");
    	}

    	if (isset($_POST['SiteConfiguration'])) {
    		$model->setAttributes($_POST['SiteConfiguration']);

    		if (Yii::app()->request->isAjaxRequest) {
    			// its validation request
    			echo CActiveForm::validate($model);
				Yii::app()->end();
    		} else {
    			// its save request
    			if ($model->save()) {
    				
    				ConfigTemplate::handleEdit($model->id);

    				Yii::app()->cache->clear('SiteConfiguration', 'ConfigTemplates');

    				Yii::app()->user->setFlash('success', Yii::t('Site', 'Changes saved successfully.'));
    				if ($id == 'new') {
    					$this->redirect(array('SiteConfigurations/Edit', 'id'=>$model->id));
    				}
    			} else {
    				Yii::app()->user->setFlash('error', Yii::t('Site', 'Error saving details.'));
    			}
    		}
    	}

    	


    	
    	$this->render("edit", array(
    			'model' => $model,
    		
    		));
    }
}