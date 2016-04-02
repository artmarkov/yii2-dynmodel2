<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 31.03.16, Time: 3:02
 */

use \yii\web\View;
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use m00nk\dynmodel2\models\fields\Field;

/**
 * @var  View $this
 * @var Field $model
 */

$form = ActiveForm::begin([
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
//	'layout' => 'horizontal',
]);

echo Html::hiddenInput('dynmodel_class', $model->className());

echo $model->renderEditor($form);

?>
	<div class="form-group">
			<?= Html::a(Yii::t('dynModel', 'Сохранить'), '#', ['class' => 'js_dynmodel_link_submit_dlg btn btn-primary']); ?>
			<?= Html::a(Yii::t('dynModel', 'Отмена'), '#', ['class' => 'js_dynmodel_link_close_dlg btn btn-default pull-right']); ?>
		<div class="clearfix"></div>
	</div>

<?php

ActiveForm::end();