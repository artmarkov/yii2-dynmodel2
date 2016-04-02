<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 31.03.16, Time: 2:49
 */

use \yii\web\View;
use \yii\bootstrap\ActiveForm;
use m00nk\dynmodel2\models\fields\Field;

/**
 * @var  View      $this
 * @var ActiveForm $form
 * @var Field      $model
 */

echo $this->render('Field', ['form' => $form, 'model' => $model]);

echo $form->field($model, 'minLen', [
	'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">'.Yii::t('dynModel', 'символов').'</span></div>'
]);

echo $form->field($model, 'maxLen', [
	'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">'.Yii::t('dynModel', 'символов').'</span></div>'
]);
