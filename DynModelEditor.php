<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 01.04.16, Time: 21:37
 */

namespace m00nk\dynmodel2;

use m00nk\dynmodel2\models\DynModel;
use yii\widgets\InputWidget;

class DynModelEditor extends InputWidget
{
	public function run()
	{
		$scheme = $this->model->{$this->attribute};

		$dm = new DynModel();
		$dm->schemeFromJson($scheme);

		return $this->render('editor', ['model' => $dm]);
	}
}