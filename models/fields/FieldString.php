<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:54
 */

namespace m00nk\dynmodel2\models\fields;

use \Yii;

class FieldString extends Field
{
	public $maxLen = null;
	public $minLen = null;

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'maxLen' => Yii::t('dynModel', 'Максимальная длина'),
			'minLen' => Yii::t('dynModel', 'Минимальная длина'),
		]);
	}

	public function rules()
	{
		return array_merge(parent::rules(), [
			[['maxLen', 'minLen'], 'integer', 'min' => 0],
		]);
	}

	public function compileValidators()
	{
		$out = parent::compileValidators();

		$_ = [];

		if($this->maxLen) $_['max'] = $this->maxLen;
		if($this->minLen) $_['min'] = $this->minLen;

		$out[] = array_merge([$this->id, 'string'], $_);

		return $out;
	}

	public function getInfo()
	{
		$out = [];
		if($this->maxLen && $this->minLen)
		{
			$out[] = Yii::t('dynModel', 'от {min} до {max} симв.', ['max' => $this->maxLen, 'min' => $this->minLen]);
		}
		else
		{
			if($this->maxLen)
				$out[] = Yii::t('dynModel', 'до {len} симв.', ['len' => $this->maxLen]);

			if($this->minLen)
				$out[] = Yii::t('dynModel', 'от {len} симв.', ['len' => $this->minLen]);
		}
		return implode(', ', $out);
	}

	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Строка');
	}

}