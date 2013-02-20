<div class="row-fluid">
	<div class="span12">
		<h1><?php echo Yii::t('Site', 'Edit platform');?> #<?php echo $model->id;?></h1>
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

	<div class="span12">
		<fieldset>
			<legend><?php echo Yii::t('Site', 'Platform details');?></legend>
			<?php echo $form->textFieldRow($model, 'name');?>
			<?php echo $form->textFieldRow($model, 'systemUser');?>
			<?php
				echo $form->dropDownListRow(
					$model, 
					'Server_id', 
					$existingServers,
					array(
							'class'=>'chzn-select',
							'style'=>'width:250px;',
							'data-placeholder'=>Yii::t('Site', 'Click to select existing servers'),
						)
				);
			$this->widget( 'EChosen' );
			?>
		</fieldset>
	</div>
	
	
</div>
<div class="row-fluid">
	<div class="span12">
		<fieldset>
			<legend><?php echo Yii::t('Site', 'Platform services');?></legend>
			<?php
				$this->widget('bootstrap.widgets.TbTabs', array(
					'type'=>'tabs',
					'tabs'=>array(
						array(
							'label' => Yii::t('Site', 'Sites'),
							'content' => $this->renderPartial(
									"_sites",
									array(
											'form' => $form,
											'models' => $model->sites,
											'platformId' => $model->id,
											'existingSiteConfigurations' => $existingSiteConfigurations,
										),
									true
								),
							'active' => true
						),
						array(
							'label' => Yii::t('Site', 'Databases'),
							'content' => $this->renderPartial(
									"_databases",
									array(
											'form' => $form,
											'models' => $model->databases,
											'platformId' => $model->id,
										),
									true
								),
						),
						array(
							'label' => Yii::t('Site', 'FTP users'),
							'content' => $this->renderPartial(
									"_ftpUsers",
									array(
											'form' => $form,
											'models' => $model->databases,
											'platformId' => $model->id,
										),
									true
								),
						),
					),
				));
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