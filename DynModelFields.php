<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 01.04.16, Time: 21:37
 */

namespace m00nk\dynmodel2;

use m00nk\dynmodel2\models\DynModel;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;

class DynModelFields extends Widget
{
	/** @var  ActiveForm */
	public $form;
	
	/** @var  DynModel */
	public $model;

	public function run()
	{
		return $this->render('fields');
	}
}