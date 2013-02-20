<div class="row-fluid">
	<div class="span12">
		<h1><?php echo Yii::t('Site', 'Edit account');?></h1>
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
			<legend><?php echo Yii::t('Site', 'Account details');?></legend>
			<?php echo $form->textFieldRow($model, 'name');?>
		</fieldset>
	</div>
	<div class="span6">
		<fieldset>
			<legend><?php echo Yii::t('Site', 'Account users');?></legend>
			<?php

				echo $form->dropDownListRow(
						$model, 
						'users', 
						$existingUsers,
						array(
								'class'=>'chzn-select',
								'style'=>'width:250px;',
								'multiple'=>true,
								'data-placeholder'=>Yii::t('Site', 'Click to select existing users'),
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
			<legend><?php echo Yii::t('Site', 'Account platforms');?></legend>
			<?php 
			$this->widget("PlatformEdit", array(
				'accountId' => $model->id,
				'models' => $model->platforms,
				'form' => $form,
				'existingServers' => $existingServers,
			)); ?>
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