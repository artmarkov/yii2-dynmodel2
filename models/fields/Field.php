<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:39
 */

namespace m00nk\dynmodel2\models\fields;

use yii\base\Model;
use \Yii;
use yii\bootstrap\ActiveField;
use yii\widgets\ActiveForm;

class Field extends Model
{
	public $id = null;
	public $label = '';
	public $hint = '';
	public $isRequired = 0;
	public $isEncoded = 0;

	/** @var array кастомные правила валидации БЕЗ идентификатора поля (он будет вставлен автоматически) */
	public $rules = [];

	/** @var string старое значение ID, применяется для определения изменения или создания новой модели */
	public $old_id = null;

	public $value = null;

	public function init()
	{
		parent::init();
		$this->old_id = $this->id;
	}

	public function attributeLabels()
	{
		return [
			'id' => Yii::t('dynModel', 'Идентификатор'),
			'label' => Yii::t('dynModel', 'Заголовок'),
			'hint' => Yii::t('dynModel', 'Подсказка'),
			'isRequired' => Yii::t('dynModel', 'Обязательное'),
			'isEncoded' => Yii::t('dynModel', 'Шифруемое'),
//		'rules' => Yii::t('dynModel', 'Правила'),
			'value' => Yii::t('dynModel', 'Значение')
		];
	}

	public function rules()
	{
		return [
			[['id', 'label'], 'required'],
			[['old_id', 'id', 'label', 'hint'], 'string'],
			[['old_id', 'id', 'label', 'hint'], 'trim'],

			['id', function ($attribute, $params)
			{
				if(preg_match('/^[a-z0-9\_]+$/', $this->$attribute) === 0)
					$this->addError($attribute,
						Yii::t('dynModel', 'Поле содержит недопустимые символы. Разрешены только строчные латинские буквы, цифры и знаки подчеркивания.'));
			}],

			['value', 'safe'],
			[['isRequired', 'isEncoded'], 'in', 'range' => [0, 1]],
			[['rules'], 'safe']
		];
	}

	public function compileValidators()
	{
		$out = $this->isRequired ? [[$this->id, 'required']] : [];
		foreach ($this->rules as $r)
			$out[] = array_merge([$this->id], $r);
		return $out;
	}

	/**
	 * Рендерит поле в указанной форме
	 *
	 * @param ActiveForm $form
	 * @param Model      $model
	 *
	 * @return ActiveField
	 */
	public function render($form, $model)
	{
		return $form->field($model, $this->id)->hint($this->hint);
	}

	/**
	 * Возвращает дополнительную информацию о поле (нужно для списка полей в редакторах модели)
	 *
	 * @return string
	 */
	public function getInfo()
	{
		return '';
	}

	/**
	 * Возвращает текстовое названием типа поля (нужно для списка полей в редакторах модели)
	 *
	 * @return string
	 */
	public static function getTypeName()
	{
		return Yii::t('dynModel', 'Кастомное поле');
	}

	/**
	 * @param ActiveForm $form
	 *
	 * @return string
	 */
	public function renderEditor($form)
	{
		$template = __DIR__.'/../../views/forms/'.array_pop(explode('\\', get_class($this))).'.php';

		return $form->view->renderFile($template, ['form' => $form, 'model' => $this]);
	}

	/**
	 * Это новая модель или уже существующая?
	 *
	 * @return bool
	 */
	public function isNew()
	{
		return empty($this->old_id);
	}
}