<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:54
 */

namespace m00nk\dynmodel2\models\fields;

use \Yii;

class FieldText extends Field
{
	public $maxLen = null;

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'maxLen' => Yii::t('dynModel', 'Максимальная длина'),
		]);
	}

	public function rules()
	{
		return array_merge(parent::rules(), [
			[['maxLen'], 'integer', 'min' => 0],
		]);
	}

	public function compileValidators()
	{
		$out = parent::compileValidators();

		$_ = [];

		if($this->maxLen) $_['max'] = $this->maxLen;

		$out[] = array_merge([$this->id, 'string'], $_);

		return $out;
	}

	public function getInfo()
	{
		$out = [];
		if($this->maxLen)
			$out[] = Yii::t('dynModel', 'до {len} симв.', ['len' => $this->maxLen]);
		return implode(', ', $out);
	}

	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Текст');
	}

	public function render($form, $model)
	{
		return parent::render($form, $model)
			->textarea();
	}
}