<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:54
 */

namespace m00nk\dynmodel2\models\fields;

use \Yii;

class FieldCheckbox extends Field
{
	public function compileValidators()
	{
		$out = parent::compileValidators();

		$out[] = [$this->id, 'in', 'range' => [1,0]];

		return $out;
	}

	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Флажок');
	}

	public function render($form, $model)
	{
		return parent::render($form, $model)->checkbox();
	}
}