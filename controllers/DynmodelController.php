<?php
/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 01.04.16, Time: 1:25
 */

namespace m00nk\dynmodel2\controllers;

use m00nk\dynmodel2\models\DynModel;
use m00nk\dynmodel2\models\fields\Field;
use yii\helpers\Json;
use yii\web\Controller;
use \Yii;
use yii\web\HttpException;

class DynmodelController extends Controller
{
	public function actionAjax()
	{
		if(!Yii::$app->request->isAjax) throw new HttpException(404);

		$cmd = Yii::$app->request->post('cmd');

		switch($cmd)
		{
			//-----------------------------------------
			case 'storeField' :
				$out = $this->_storeField();
				break;

			//-----------------------------------------
			case 'getFieldEditor':
				$out = $this->_getFieldEditor();
				break;

			//-----------------------------------------
			case 'getNewFieldEditor':
				$out = $this->_getNewFieldEditor();
				break;

			//-----------------------------------------
			default:
				$out = [
					'status' => 'error',
					'message' => Yii::t('dynModel', 'Неверная команда')
				];
		}

		return Json::encode($out);
	}


	//======================================================
	// PRIVATES
	//======================================================

	private function _storeField()
	{
		$data = Yii::$app->request->post('data');
		$class = $data['dynmodel_class'];

		$dm = new DynModel();
		$dm->schemeFromArray(Yii::$app->request->post('params')['model']);

		/** @var Field $fld */
		$fld = new $class();
		$fld->load($data);

		if($fld->validate())
		{
			if($fld->isNew())
			{
				$_ = $fld->attributes;
				$_['class'] = $fld->className();
				$flds = $dm->schemeToArray();
				$flds[] = $_;
				$dm->schemeFromArray($flds);
			}
			else
			{
				$_ = $dm->getField($fld->id);
				$_->attributes = $fld->attributes;
			}

			return [
				'status' => 'ok',
				'model' => $dm->schemeToArray(),
				'html' => $this->renderFile(__DIR__.'/../views/_table.php', ['model' => $dm])
			];
		}

		return [
			'status' => 'reload_form',
			'title' => Yii::t('dynModel', 'Новое поле'),
			'html' => $this->renderFile(__DIR__.'/../views/field_editor.php', ['model' => $fld])
		];
	}

	private function _getNewFieldEditor()
	{
		$class = Yii::$app->request->post('type');

		$fld = new $class();
		if(!$fld)
			return [
				'status' => 'error',
				'message' => Yii::t('dynModel', 'Неверный тип поля.')
			];

		return [
			'status' => 'ok',
			'title' => Yii::t('dynModel', 'Новое поле'),
			'html' => $this->renderFile(__DIR__.'/../views/field_editor.php', ['model' => $fld])
		];
	}

	private function _getFieldEditor()
	{
		$id = Yii::$app->request->post('id');
		$params = Yii::$app->request->post('params');

		$dm = new DynModel();
		$dm->schemeFromArray($params['model']);

		$fld = $dm->getField($id);
		if(!$fld)
			return [
				'status' => 'error',
				'message' => Yii::t('dynModel', 'Неверный идентификатор поля.')
			];

		return [
			'status' => 'ok',
			'title' => Yii::t('dynModel', 'Новое поле'),
			'html' => $this->renderFile(__DIR__.'/../views/field_editor.php', ['model' => $fld])
		];
	}
}