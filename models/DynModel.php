<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 29.03.16, Time: 2:38
 */

namespace m00nk\dynmodel2\models;

use m00nk\dynmodel2\models\fields\Field;
use yii\base\InvalidParamException;
use yii\base\Model;
use \Yii;
use yii\helpers\Json;

class DynModel extends Model
{
	/** @var Field[] */
	private $_fields = [];

	public function schemeFromArray($scheme)
	{
		if(!is_array($scheme)) $scheme = [];

		$this->_fields = [];
		foreach ($scheme as $f)
		{
			$class = array_key_exists('class', $f) ? $f['class'] : Field::className();
			unset($f['class']);
			$this->_fields[] = new $class($f);
		}
	}

	public function schemeFromJson($scheme)
	{
		$this->_fields = [];
		try
		{
			$this->schemeFromArray(Json::decode($scheme));
		}
		catch (InvalidParamException $e)
		{
		}
	}

	public function schemeToArray()
	{
		$out = [];

		foreach ($this->_fields as $f)
		{
			$_ = $f->attributes;
			$_['class'] = $f->className();
			$out[] = $_;
		}

		return $out;
	}

	public function schemeToJson()
	{
		return Json::encode($this->schemeToArray());
	}

	public function getFields()
	{
		return $this->_fields;
	}

	public function getField($id)
	{
		return $this->_getFieldById($id, false);
	}

	/**
	 * Очищает значения всех полей
	 */
	public function clearAttributeValues()
	{
		foreach ($this->_fields as $f)
			$f->value = null;
	}

	/**
	 * Заполняет поля значениями из переданного JSON. Заполняет только существующие в модели поля, остальной мусор игнорирует.
	 *
	 * @param string $json
	 */
	public function fillAttributesFromJson($json)
	{
		$this->clearAttributeValues();
		
		$availableAttributes = $this->attributes();
		if(empty($json)) $json = '[]';

		try{
			$data = Json::decode($json);
		}
		catch(InvalidParamException $e)
		{
			$data = [];
		}

		foreach($data as $name => $value)
			if(in_array($name, $availableAttributes))
				$this->$name = $value;
	}

	/**
	 * Возвращает значения полей в виде ассоциативного массива, упакованного в JSON
	 * 
	 * @return string
	 */
	public function getAttributesAsJson()
	{
		return Json::encode($this->attributes);
	}
	
	//<editor-fold desc="Перегруженные методы">
	public function rules()
	{
		$_ = [];

		foreach ($this->_fields as $f)
			$_ = array_merge($_, $f->compileValidators());

		return $_;
	}

	public function attributes()
	{
		$_ = [];

		foreach ($this->_fields as $f)
			$_[] = $f->id;

		return $_;
	}

	public function attributeLabels()
	{
		$_ = [];

		foreach ($this->_fields as $f)
			$_[$f->id] = $f->label;

		return $_;
	}

	public function attributeHints()
	{
		$_ = [];

		foreach ($this->_fields as $f)
			$_[$f->id] = $f->hint;

		return $_;
	}

	public function getAttributes($names = null, $except = [])
	{
		$values = [];
		if($names === null)
			$names = $this->attributes();

		foreach ($names as $name)
			if(!in_array($name, $except))
				$values[$name] = $this->$name;

		return $values;
	}
	
	public function __set($name, $value)
	{
		$setter = 'set'.$name;
		if(method_exists($this, $setter))
		{
			$this->$setter($value);
			return;
		}

		$this->_getFieldById($name)->value = $value;
	}

	public function __get($name)
	{
		$getter = 'get'.$name;
		if(method_exists($this, $getter))
			return $this->$getter();

		return $this->_getFieldById($name)->value;
	}
	//</editor-fold>
	
	//-----------------------------------------
	private function _getFieldById($id, $throwException = true)
	{
		foreach ($this->_fields as $f)
			if($f->id == $id) return $f;

		if($throwException)
			throw new InvalidParamException(Yii::t('dynModel', 'Поле "{fld}" не найдено в модели', ['fld' => $id]));
		else
			return null;
	}
}