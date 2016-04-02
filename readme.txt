Конфигурация: прописать в натстройках приложения

'controllerMap' => [
	'dynmodel' => [
		'class' => 'm00nk\dynmodel2\controllers\DynmodelController'
	]
]

=======================================================

Встраивание редактора, контроллер:

// достаем из хранилища схему
$model = Setting::findOne(['category' => 'cms/user', 'key' => 'profile_scheme'])ж

if($model->load(Yii::$app->request->post()) && $model->save())
{
	Yii::$app->session->addFlash('success', 'Изменения сохранены');
	return $this->redirect(['list']);
}

return $this->render('schemeEditor', ['model' => $model]);

вьюшка редактора:

$form = \yii\bootstrap\ActiveForm::begin();

echo DynModelEditor::widget([
	'model' => $model,
	'attribute' => 'value' // это имя аттрибута в модели $scheme, которых хранит данные
]);

echo \yii\helpers\Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']);

\yii\bootstrap\ActiveForm::end();


=======================================================


Первый вариант использования (форма обратной связи):

Контроллер:

$scheme = Setting::findOne(['category' => 'cms/user', 'key' => 'profile_scheme']);
$dm = new DynModel();
$dm->schemeFromJson($scheme->value);

if($dm->load(Yii::$app->request->post()) && $dm->validate())
{
	// получить данные можно по foreach($dm->attributes as $k => $v)
}

return $this->render('editor', ['dm' => $dm]);


Вьюшка:

$form = ActiveForm::begin();

echo m00nk\dynmodel2\DynModelFields::widget([
	'model' => $dm,
	'form' => $form,
]);

ActiveForm::end();

