<div class="row-fluid">
	<div class="span12">
		<h1><?php echo Yii::t('Site', 'Edit server');?></h1>
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
			<legend><?php echo Yii::t('Site', 'Server details');?></legend>
			<?php echo $form->textFieldRow($model, 'name');?>
			<?php echo $form->textFieldRow($model, 'ip');?>
			<?php echo $form->textFieldRow($model, 'description');?>
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