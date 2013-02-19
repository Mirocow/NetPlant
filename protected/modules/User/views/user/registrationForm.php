<h2>Регистрация на сайте</h2>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'productForm',
    'type'=>'horizontal',
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
			'validateOnSubmit'=>true,
		),
    'action'=>array("/User/user/registration"),
)); ?>
<?php echo $form->textFieldRow($model, 'username'); ?>
<?php echo $form->passwordFieldRow($model, 'password'); ?>
<?php echo $form->textFieldRow($model, 'email'); ?>
<div class="form-actions">
<?php echo CHtml::submitButton("Зарегистрироваться");?>
</div>
<?php $this->endWidget();?>