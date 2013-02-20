<?php foreach ($models as $model): 
	$idPrefix = '['.$model->id.']';
?>
	<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
	<?php echo $form->textFieldRow($model, $idPrefix.'systemUser');?>
	<?php
		echo $form->dropDownListRow(
				$model, 
				$idPrefix.'Server_id', 
				$existingServers,
				array(
						'class'=>'chzn-select',
						'style'=>'width:250px;',
						'data-placeholder'=>Yii::t('Site', 'Click to select existing servers'),
					)
			);
		$this->widget( 'EChosen' );
	?>

<?php endforeach;?>

<fieldset>
	<legend><?php echo Yii::t('Site', "Add new platform");?></legend>

	<?php
		$model = new Platform;
		$idPrefix = '[new]';
	?>
	<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
	<?php echo $form->textFieldRow($model, $idPrefix.'systemUser');?>
	<?php
		echo $form->dropDownListRow(
				$model, 
				$idPrefix.'Server_id', 
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