<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 31.03.16, Time: 2:49
 */

use \yii\web\View;
use \yii\bootstrap\ActiveForm;
use m00nk\dynmodel2\models\fields\Field;
use \yii\helpers\Html;

/**
 * @var  View      $this
 * @var ActiveForm $form
 * @var Field      $model
 */

echo Html::activeHiddenInput($model, 'old_id');

echo $form->field($model, 'id') /* ->hint(Yii::t('dynModel', 'Разрешены только строчные латинские буквы, цифры и знаки подчеркивания.')) */;
echo $form->field($model, 'label');
echo $form->field($model, 'hint');
echo $form->field($model, 'isRequired')->checkbox();
echo $form->field($model, 'isEncoded')->checkbox();
