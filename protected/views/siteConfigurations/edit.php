<div class="row-fluid">
	<div class="span12">
		<h1><?php echo Yii::t('Site', 'Edit site configuration');?></h1>
		<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
	</div>
</div>
<?php /** @var TbActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'editForm',
		'type'=>'horizontal',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
		'focus'=>array($model, 'name'),
	)); 
?>
<div class="row-fluid">

	<div class="span6">
		<fieldset>
			<legend><?php echo Yii::t('Site', 'Site configuration details');?></legend>
			<?php echo $form->textFieldRow($model, 'name');?>
			<?php echo $form->textFieldRow($model, 'handlerClass');?>
			
		</fieldset>
	</div>
	
</div>
<div class="row-fluid">
	<div class="span12">
		<fieldset>
			<legend><?php echo Yii::t('Site', 'Config templates');?></legend>
			<?php
				$tabs = array();

				foreach ($model->configTemplates as $i=>$configTemplate) {
					$tabs[] = array(
							'label' => Yii::t('Site', 'Config template') . ' #'.$configTemplate->id,
							'content' => $this->renderPartial(
									"_configTemplate",
									array(
											'form' => $form,
											'model' => $configTemplate,
										),
									true
								),
							'active' => $i===0
						);
				}
				$configTemplate = new ConfigTemplate;
				$configTemplate->id = 'new';
				$configTemplate->SiteConfiguration_id = $model->id;
				$tabs[] = array(
							'label' => Yii::t('Site', 'Add config template'),
							'content' => $this->renderPartial(
									"_configTemplate",
									array(
											'form' => $form,
											'model' => $configTemplate,
										),
									true
								),
							'active' => count($tabs)==0
						);

				$this->widget('bootstrap.widgets.TbTabs', array(
					'type' => 'tabs',
					'tabs' => $tabs));
			?>
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="form-actions">
		    <?php 
		    	$this->widget(
		    	'bootstrap.widgets.TbButton', 
		    	array(
			    		'buttonType'=>'submit', 
			    		'type'=>'primary', 
			    		'label'=> Yii::t('Site', 'Save'),
			    		'icon' => 'save',
			    		'id' => 'editSaveButton'
		    		)
		    	); ?>
		</div>
	</div>
</div>
<?php
	$this->endWidget();
?>