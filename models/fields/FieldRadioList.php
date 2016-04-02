<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:54
 */

namespace m00nk\dynmodel2\models\fields;

use \Yii;
use yii\bootstrap\ActiveField;

class FieldRadioList extends FieldDropdown
{
	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Радио-кнопки');
	}

	public function render($form, $model)
	{
		return $form->field($model, $this->id)->hint($this->hint)
				->radioList($this->_getOptions());
	}
}