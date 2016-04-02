Компонент для работы с динамическими моделями в Yii2
====================================================

### Конфигурация: прописать в настройках приложения

```php
'controllerMap' => [
	'dynmodel' => [
		'class' => 'm00nk\dynmodel2\controllers\DynmodelController'
	]
]
```

### Встраивание редактора

Контроллер:

```php
// достаем из хранилища схему. Данная модель содержит текстовое поле 'value', в котором хранится схема в JSON. 
$model = Setting::findOne(['category' => 'cms/user', 'key' => 'profile_scheme']);

if($model->load(Yii::$app->request->post()) && $model->save())
{
	Yii::$app->session->addFlash('success', 'Изменения сохранены');
	return $this->redirect(['list']);
}

return $this->render('schemeEditor', ['model' => $model]);
```

Вьюшка редактора (schemeEditor.php):
```php
$form = \yii\bootstrap\ActiveForm::begin();

echo DynModelEditor::widget([
	'model' => $model,
	'attribute' => 'value' // это имя аттрибута в модели $scheme, которых хранит данные
]);

echo \yii\helpers\Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']);

\yii\bootstrap\ActiveForm::end();
```

Использовать привычное **$form->field($model, 'value')** нельзя, если мы хотим, чтобы таблица заняла всю ширину формы.

### Использование созданной модели (например, форма обратной связи) 

Контроллер:

```php
// достаем схему из хранилища
$scheme = Setting::findOne(['category' => 'cms/user', 'key' => 'profile_scheme']);

// создаем пустую модель
$dm = new DynModel();

// загружаем в нее схему
$dm->schemeFromJson($scheme->value);

if($dm->load(Yii::$app->request->post()) && $dm->validate())
{
    // здесь можем работать с $dm как с обычной моделью, например
	// получить данные можно по foreach($dm->attributes as $k => $v)
}

return $this->render('form', ['dm' => $dm]);
```

Вьюшка (form.php):
```php
$form = ActiveForm::begin();

echo m00nk\dynmodel2\DynModelFields::widget([
	'model' => $dm,
	'form' => $form,
]);

ActiveForm::end();
```
