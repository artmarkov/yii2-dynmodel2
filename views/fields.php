<?php
/**
 * @copyright (C) FIT-Media.com {@link http://fit-media.com}
 * Date: 09.09.15, Time: 19:17
 *
 * @author        Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 */

use \yii\web\View;
use m00nk\dynmodel2\DynModelFields;

/**
 * @var View     $this
 * @var DynModelFields $widget
 */

$widget = $this->context;

//-----------------------------------------
$fields = $widget->model->getFields();

foreach($fields as $f)
	echo $f->render($widget->form, $widget->model);
