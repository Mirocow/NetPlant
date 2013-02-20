<div class="row-fluid">
	<div class="span12">
		<h1><?php echo Yii::t('Site', 'Site configurations list');?></h1>
		<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
		<div class="btn-toolbar topButtons">
			<?php
			$this->widget('bootstrap.widgets.TbButtonGroup', array(
				'buttons'=>array(
							array(
									'type' => 'primary',
									'label' => Yii::t('Site','Create site configuration'),
									'url' => array('siteConfigurations/edit', 'id'=>'new'),
								),
					),
			));

			?>
		</div>

	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<?php $this->widget('bootstrap.widgets.TbExtendedGridView', $params); ?>
	</div>
</div>