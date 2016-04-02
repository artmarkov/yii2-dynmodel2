<?php
/**
 * @copyright (C) FIT-Media.com {@link http://fit-media.com}
 * Date: 09.09.15, Time: 19:17
 *
 * @author        Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 */

use \yii\web\View;
use \yii\helpers\Html;
use m00nk\dynmodel2\models\DynModel;
use m00nk\dynmodel2\models\fields\Field;
use m00nk\dynmodel2\models\fields\FieldString;
use m00nk\dynmodel2\models\fields\FieldText;
use m00nk\dynmodel2\models\fields\FieldDropdown;
use m00nk\dynmodel2\models\fields\FieldCheckbox;
use m00nk\dynmodel2\models\fields\FieldPassword;
use m00nk\dynmodel2\models\fields\FieldEmail;
use m00nk\dynmodel2\models\fields\FieldCheckboxList;
use m00nk\dynmodel2\models\fields\FieldRadioList;
use m00nk\dynmodel2\DynModelEditor;

/**
 * @var View     $this
 * @var DynModel $model
 */

/** @var DynModelEditor $widget */
$widget = $this->context;

// загружаем необходимые скрипты
\yii\web\JqueryAsset::register($this);
\yii\jui\JuiAsset::register($this);


$_ = $this->assetManager->publish(__DIR__.'/../assets');
$this->registerCssFile($_[1].'/dynModel.css');
$this->registerJsFile($_[1].'/dynModel.js');
$this->registerJs('dynModel.init('.\yii\helpers\Json::encode([
		'model' => $model->schemeToArray(),
		'ajaxUrl' => \yii\helpers\Url::to('/dynmodel/ajax'),
		'fieldId' => Html::getInputId($widget->model, $widget->attribute),
		'messages' => [
			'titleError' => Yii::t('dynModel', 'Ошибка'),
			'btnClose' => Yii::t('dynModel', 'Закрыть'),
			'wrongIndex' => Yii::t('dynModel', 'Неверный индекс.'),
			'cantMoveUp' => Yii::t('dynModel', 'Не могу сдвинуть выше, поле является первым.'),
			'cantMoveDown' => Yii::t('dynModel', 'Не могу сдвинуть ниже, поле является последним.'),
		]
	]).')');

//===============================================

echo Html::activeHiddenInput($widget->model, $widget->attribute);

$header = Html::tag('div', \yii\bootstrap\ButtonDropdown::widget([
		'label' => '<span class="glyphicon glyphicon-plus"></span>  '.Yii::t('dynModel', 'Создать поле'),
		'encodeLabel' => false,
		'containerOptions' => ['class' => 'pull-right'],
		'options' => ['class' => 'btn btn-xs btn-primary'],
		'dropdown' => [
			'items' => [
				[
					'label' => Field::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => Field::className()]
				],

				[
					'label' => FieldString::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldString::className()]
				],

				[
					'label' => FieldEmail::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldEmail::className()]
				],

				[
					'label' => FieldPassword::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldPassword::className()]
				],

				[
					'label' => FieldText::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldText::className()]
				],

				[
					'label' => FieldDropdown::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldDropdown::className()]
				],

				[
					'label' => FieldRadioList::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldRadioList::className()]
				],

				[
					'label' => FieldCheckboxList::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldCheckboxList::className()]
				],

				[
					'label' => FieldCheckbox::getTypeName(), 'url' => '#',
					'linkOptions' => ['class' => 'js_dynmodel_link_add_field', 'data-id' => FieldCheckbox::className()]
				],
			],
		],
	])
	.'<span class="glyphicon glyphicon-th-list"></span> '.Yii::t('admin', 'Список полей профилей'),
	['class' => 'panel-heading']
);

echo Html::tag('div', $header.$this->render('_table', ['model' => $model]), ['class' => 'panel panel-default', 'id' => $widget->id]);

