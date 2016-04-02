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

/**
 * @var View     $this
 * @var DynModel $model
 */

echo \yii\grid\GridView::widget([
	'tableOptions' => [
		'class' => 'table table-striped',
		'id' => 'dynmodel_fields_table',
	],
	'layout' => '{items}',

	'dataProvider' => new \yii\data\ArrayDataProvider([
		'allModels' => $model->getFields(),
		'pagination' => false
	]),

	'rowOptions' => function ($model)
	{
		/** @var $model Field */
		return [
			'data-id' => $model->id
		];
	},

	'columns' => [
		[
			'attribute' => 'id',
			'format' => 'raw',
			'value' => function ($model)
			{
				/** @var Field $model */
				$labels = [];

				if($model->isRequired == 1) $labels[] = Html::tag('span', '', ['class' => 'glyphicon glyphicon-warning-sign', 'title' => Yii::t('admin', 'Обязательное')]).' ';
				if($model->isEncoded == 1) $labels[] = Html::tag('span', '', ['class' => 'glyphicon glyphicon-lock', 'title' => Yii::t('admin', 'Шифруемое')]).' ';
				return Html::tag('div', implode('', $labels), ['class' => 'pull-right']).
				Html::a($model->id, '#',
					[
						'class' => 'js_dynmodel_link_edit_field',
						'title' => Yii::t('dynModel', 'Редактировать поле'),
					]
				);
			}
		],

		[
			'attribute' => 'label',
		],

		[
			'header' => Yii::t('admin', 'Тип поля'),
			'format' => 'raw',
			'value' => function ($model)
			{
				/** @var Field $model */
				$info = $model->getInfo();
				if(!empty($info)) $info = ' '.Html::tag('small', $info, ['class' => 'hint']);

				return $model->getTypeName().$info;
			}
		],

		[
			'class' => 'yii\grid\ActionColumn',
			'headerOptions' => ['style' => 'width: 100px;'],
			'contentOptions' => ['style' => 'text-align: right;'],
			'template' => '{up} {down} {edit} {del}',
			'buttons' => [
				'edit' => function ($url, $model, $key)
				{
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
						'class' => 'js_dynmodel_link_edit_field',
						'title' => Yii::t('dynModel', 'Редактировать поле'),
					]);
				},
				'up' => function ($url, $model, $key)
				{
					return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', '#', [
						'class' => 'js_dynmodel_link_move_up_field',
						'title' => Yii::t('dynModel', 'Поднять'),
					]);
				},
				'down' => function ($url, $model, $key)
				{
					return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', '#', [
						'class' => 'js_dynmodel_link_move_down_field',
						'title' => Yii::t('dynModel', 'Опустить'),
					]);
				},
				'del' => function ($url, $model, $key)
				{
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
						'class' => 'js_dynmodel_link_delete_field',
						'title' => Yii::t('dynModel', 'Удалить'),
					]);
				},
			]
		],
	],
]);

