<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:54
 */

namespace m00nk\dynmodel2\models\fields;

use \Yii;

class FieldCheckboxList extends Field
{
	public $options = null;

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'options' => Yii::t('dynModel', 'Опции'),
		]);
	}

	public function rules()
	{
		return array_merge(parent::rules(), [
			[['options'], 'required'],
			[['options'], function($a, $p){
				$_ = str_replace("\r", '', $this->$a);
				$_ = array_map('trim', explode("\n", $_));
				$_ = array_filter($_, function($v){return !empty($v);});

				$out = [];
				foreach($_ as $v){
					list($k, $v) = explode('=', $v);
					if(empty($v)) $v = $k;
					$out[] = trim($k).'='.trim($v);
				}

				$this->$a = implode("\n", $out);
			}],
		]);
	}

	public function compileValidators()
	{
		$out = parent::compileValidators();

		$out[] = [$this->id, 'each', 'rule' => ['in', 'range' => array_keys($this->_getOptions())]];

		return $out;
	}

	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Список флажков');
	}

	public function render($form, $model)
	{
		return $form->field($model, $this->id)->hint($this->hint)
				->checkboxList($this->_getOptions());
	}

	protected function _getOptions()
	{
		$_ = str_replace("\r", '', $this->options);
		$_ = array_map('trim', explode("\n", $_));
		$_ = array_filter($_, function($v){return !empty($v);});

		$opts = [];
		foreach($_ as $v){
			list($k, $v) = explode('=', $v);
			if(empty($v)) $v = $k;
			$opts[trim($k)] = trim($v);
		}

		return $opts;
	}
}