<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<div style="width:50%;float:left;">
	<?php $this->renderPartial("loginForm", array('model'=>$loginForm));?>
</div>

<?php if (Yii::app()->getModule("User")->registrationEnabled):?>
<div style="width:50%;float:left;">
	<?php $this->renderPartial("registrationForm", array('model'=>$registrationForm));?>
</div>
<?php endif;?>

<div  style="clear:both"></div>


<PRE>
isGuest: <?php var_dump(Yii::app()->user->isGuest);?>
id: <?php var_dump(Yii::app()->user->id);?>
user: <?php var_dump(Yii::app()->getModule("User")->user);?>
</PRE>