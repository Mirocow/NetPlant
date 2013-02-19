
<h2>Войти как:</h2>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'loginFullForm',
    'type'=>'horizontal',
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
			'validateOnSubmit'=>true,
		),
    'action'=>array("/User/user/login"),
));

?>

<?php echo $form->textFieldRow($model, 'username'); ?>
<?php echo $form->passwordFieldRow($model, 'password'); ?>
<?php echo $form->checkBoxRow($model, 'rememberMe'); ?>


<div class="form-actions">
        	<?php echo CHtml::submitButton('Войти'); ?>
</div>


<?php $this->endWidget(); ?>

<?php if (Yii::app()->getModule("User")->loginzaEnabled):?>
<h3>Или войти с помощью</h2>
<div class="loginza">
	
	<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
	<iframe src="http://loginza.ru/api/widget?overlay=loginza&amp;token_url=http://<?php echo Yii::app()->params['mainHostName'];?>/User/user/login" 
	style="width:359px;height:200px" scrolling="no" frameborder="no"></iframe>
</div>


<?php endif;?>