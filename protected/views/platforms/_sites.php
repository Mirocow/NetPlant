<?php
foreach ($models as $model): 
	$idPrefix = '['.$model->id.']';
?>

	<fieldset class="site">
		<legend>Site #<?php echo $model->id;?></legend>
		<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
		<?php echo $form->textFieldRow($model, $idPrefix.'aliases');?>
		<?php echo $form->toggleButtonRow($model, $idPrefix.'active');?>
		<?php
			echo $form->dropDownListRow(
					$model, 
					$idPrefix.'SiteConfiguration_id', 
					$existingSiteConfigurations,
					array(
							'class'=>'chzn-select',
							'style'=>'width:250px;',
							'data-placeholder'=>Yii::t('Site', 'Click to select existing site configurations'),
						)
				);
			$this->widget( 'EChosen' );
		?>
	</fieldset>

<?php
endforeach;
?>
<?php 
	$model = new Site;
	$model->Platform_id = $platformId;
	$idPrefix = '[new]';
?>
<fieldset class="site">
	<legend><?php echo Yii::t('Site', 'Add new site');?></legend>
	<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
	<?php echo $form->textFieldRow($model, $idPrefix.'aliases');?>
	<?php echo $form->toggleButtonRow($model, $idPrefix.'active');?>
	<?php
		echo $form->dropDownListRow(
				$model, 
				$idPrefix.'SiteConfiguration_id', 
				$existingSiteConfigurations,
				array(
						'class'=>'chzn-select',
						'style'=>'width:250px;',
						'data-placeholder'=>Yii::t('Site', 'Click to select existing site configurations'),
					)
			);
		$this->widget( 'EChosen' );
	?>
</fieldset>