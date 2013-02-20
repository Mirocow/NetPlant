<?php
foreach ($models as $model): 
	$idPrefix = '['.$model->id.']';
?>

	<fieldset class="database">
		<legend>Database #<?php echo $model->id;?></legend>
		<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
		<?php echo $form->toggleButtonRow($model, $idPrefix.'active');?>
		<?php echo $form->passwordFieldRow($model, $idPrefix.'password');?>

	</fieldset>

<?php
endforeach;
?>
<?php 
	$model = new Database;
	$model->Platform_id = $platformId;
	$idPrefix = '[new]';
?>
<fieldset class="database">
	<legend><?php echo Yii::t('Site', 'Add new database');?></legend>
	<?php echo $form->textFieldRow($model, $idPrefix.'name');?>
	<?php echo $form->toggleButtonRow($model, $idPrefix.'active');?>
	<?php echo $form->passwordFieldRow($model, $idPrefix.'password');?>

</fieldset>