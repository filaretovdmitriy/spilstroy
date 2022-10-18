<?php

namespace app\modules\icms\controllers;

use app\components\db\ActiveRecord;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Html;
use app\components\IcmsHelper;
use app\modules\icms\widgets\multi_upload\MultiUpload;

class AjaxController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['upload_file', 'delete_multi', 'save_multi'],
                'rules' => [
                    [
                        'actions' => ['upload_file', 'delete_multi', 'save_multi'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException(403, 'Только для ajax');
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    public function actionDelete_picture()
    {
        $result = ['success' => false];
        $modelName = Yii::$app->request->post('table');
        $id = Yii::$app->request->post('id_elem');
        $attribute = Yii::$app->request->post('field');
        if (!$attribute && !$modelName && !$id) {
            return $result;
        }

        $model = $modelName::findOne($id);
        $fileName = $model->{$attribute};
        $model->{$attribute} = false;
        if ($model->save()) {
            $filePath = $model->getFullPath($attribute, null, false) . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $result['success'] = true;
            return $result;
        } else {
            return $result;
        }
    }

    public function actionDelete_file()
    {
        $result = ['success' => false];
        $modelName = Yii::$app->request->post('table');
        $id = Yii::$app->request->post('id_elem');
        $attribute = Yii::$app->request->post('field');
        if (!$attribute && !$modelName && !$id) {
            return $result;
        }

        $model = $modelName::findOne($id);
        $fileName = $model->{$attribute};
        $model->{$attribute} = false;
        if ($model->save()) {
            $filePath = $model->getFullPath($attribute, null, false) . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $result['success'] = true;
            return $result;
        } else {
            return $result;
        }
    }

    public function actionSave_model()
    {
        $result = ['success' => true];
        $model = Yii::$app->request->post('model', false);
        $id = Yii::$app->request->post('id');
        $modelBase = $model::findOne($id);

        if (is_null($modelBase) === true) {
            $result['text'] = 'Запись не найдена';
            $result['success'] = false;
            return $result;
        }

        $modelBase->load(Yii::$app->request->post());

        if ($modelBase->save() === true) {
            $result['text'] = 'Запись сохранена';
        } else {
            $result['success'] = false;
            $result['text'] = IcmsHelper::getErrorList($modelBase->errors);
        }

        return $result;
    }

    public function actionGrid_view_save_all()
    {
        $modelName = Yii::$app->request->post('modelsName', false);
        $modelsAttributes = Yii::$app->request->post('modelsAttributes', []);
        $savesCount = 0;
        foreach ($modelsAttributes as $modelId => $attributes) {
            $model = $modelName::findOne($modelId);
            if (is_null($model)) {
                continue;
            }

            $model->load($attributes);

            if ($model->save()) {
                $savesCount++;
            }
        }

        return ['text' => ($savesCount == 1 ? 'Сохранена' : 'Сохранено') . " {$savesCount} " . IcmsHelper::pluralForm($savesCount, ['запись', 'записи', 'записей'])];
    }

    public function actionSave_unique_model_value()
    {
        $model = Yii::$app->request->post('model', false);
        $id = Yii::$app->request->post('id');
        $attribute = Yii::$app->request->post('attribute');
        $modelBase = $model::findOne($id);
        $result = ['success' => true];
        if ($model === false || is_null($modelBase) || empty($attribute)) {
            $result['success'] = false;
            return $result;
        }

        $model::updateAll([$attribute => 0]);
        $modelBase->{$attribute} = 1;
        $modelBase->save();

        return $result;
    }

    public function actionDelete_model()
    {
        $model = Yii::$app->request->post('model', false);
        if ($model === false) {
            Yii::$app->session->setFlash('message', 'Ошибка удаления!');
            return true;
        }

        $id = Yii::$app->request->post('id');

        $element = $model::findOne($id);
        if (!is_null($element)) {
            $element->delete();
        }

        Yii::$app->session->setFlash('message', 'Запись удалена');

        return true;
    }

    public function actionGrid_view_delete_all()
    {
        $modelName = Yii::$app->request->post('modelName', false);
        $deleteIds = Yii::$app->request->post('deleteIds', []);
        if ($modelName === false) {
            Yii::$app->session->setFlash('message', 'Ошибка удаления!');
            return true;
        }

        $deleteCount = 0;
        $models = $modelName::findAll(['id' => $deleteIds]);
        foreach ($models as $model) {
            if ($model->delete()) {
                $deleteCount++;
            }
        }

        Yii::$app->session->setFlash('message', $deleteCount . ' ' . IcmsHelper::pluralForm($deleteCount, ['запись', 'записи', 'записей']) . ($deleteCount == 1 ? ' удалена' : ' удалено'));
        return true;
    }

    public function actionGet_multy_images()
    {
        $data = Yii::$app->request->post('newData', false);


        $imageModel = $data['imageModel'];
        $relationField = $data['relationField'];
        $query = $imageModel::find()->andWhere([$relationField => $data['relationId']]);
        if (isset($data['sortField']) && !empty($data['sortField'])) {
            $query->orderBy($data['sortField']);
        }
        $images = $query->all();

        foreach ($images as $image) {
            $dataOptions = [
                'data-id' => $image->id,
                'data-model' => get_class($image),
                'data-prefix' => $data['prefix']
            ];
            ?>
            <div class="uppic-container">
                <a href="<?= $image->getPath('image') ?>">
                    <?= Html::img(\app\components\IcmsHelper::getResizePath($image->getPath('image'), 170, 150, 2)) ?>
                </a>
                <fieldset>
                    <?= Html::activeTextInput($image, 'name', ['class' => 'width-100 upload-image-input'] + $dataOptions) ?>
                </fieldset>
                <fieldset>
                    <?= Html::activeTextInput($image, 'sort', ['class' => 'width-100 upload-image-input'] + $dataOptions) ?>
                </fieldset>
                <fieldset class="checked-main">
                    <?=
                    \app\modules\icms\widgets\Radio::widget([
                        'name' => 'checked',
                        'checked' => $image->is_main,
                        'value' => $image->id,
                        'label' => 'Главная',
                        'options' => $dataOptions
                    ])
                    ?>
                </fieldset>
                <?= Html::button('Удалить', ['class' => 'button delete-button'] + $dataOptions) ?>
            </div>
        <?php } ?>
        <?php if (count($images) === 0) { ?>
            <div style="text-align: center">Нет ни одного изображения</div>
            <?php
        }
    }

    public function actionUpload_file()
    {
        $post = \Yii::$app->request->post();

        $result = ['success' => false];

        /* @var $model \app\components\db\ActiveRecordFiles */
        $model = new $post['model'];
        if (!($model instanceof \app\components\db\ActiveRecordFiles)) {
            throw new \yii\web\ServerErrorHttpException('Модель не поддерживает работу с файлами');
        }

        if ($model->load($post) === true && $model->save() === true) {
            $realNameAttributes = [];
            $realName = '';
            foreach ($model->attributes as $field => $value) {
                if ($value === MultiUpload::DEFAULT_REAL_NAME) {
                    $realNameAttributes[] = $field;
                } elseif (($model->{$field} instanceof \yii\web\UploadedFile) && $model->{$field}->error === UPLOAD_ERR_OK) {
                    $realName = preg_replace('/\.\w+$/', '', $model->{$field}->name);
                }
            }
            foreach ($realNameAttributes as $field) {
                $model->{$field} = $realName;
            }
            $model->saveFiles();
            $result['success'] = true;
        } else {
            $result['errors'] = $model->errors;
        }

        return $result;
    }

    public function actionDelete_multi()
    {
        $result = ['success' => true];
        $model = Yii::$app->request->post('model', false);
        if ($model === false) {
            $result ['error'] = 'Ошибка удаления!';
            return $result;
        }

        $id = Yii::$app->request->post('id');

        /* @var $element \app\components\db\ActiveRecordFiles */
        $element = $model::findOne($id);
        if (is_null($element) === false) {
            $element->delete();
        }

        $result ['text'] = 'Удалено';
        return $result;
    }

    public function actionSave_multi()
    {
        $result = ['success' => true];
        $model = Yii::$app->request->post('model', false);
        if ($model === false) {
            $result ['error'] = 'Ошибка сохранения!';
            return $result;
        }

        $id = Yii::$app->request->post('id');

        $post = Yii::$app->request->post();

        /* @var $element \app\components\db\ActiveRecordFiles */
        $element = $model::findOne($id);
        if ($element->load($post) === true) {
            $element->save(false);
        }

        $result ['text'] = 'Сохранено';
        return $result;
    }

    public function actionSelectLinkAdd()
    {
        $modelName = Yii::$app->getRequest()->post('model', false);
        $value = Yii::$app->getRequest()->post('value', false);

        if (empty($modelName) === true || $modelName === false || empty($value) === true || $value === false) {
            throw new BadRequestHttpException();
        }

        if (class_exists($modelName) === false) {
            throw new BadRequestHttpException('Class not exists');
        }

        /* @var $model ActiveRecord */
        $model = new $modelName();
        if ($model instanceof ActiveRecord === false) {
            throw new BadRequestHttpException();
        }

        $result = [];

        $model->name = $value;
        $result['success'] = $model->save();
        $result['id'] = $model->id;
        $result['errors'] = $model->getErrors();
        $items = $model::getNamesAsArray('name', ['name' => SORT_ASC]);
        $result['items'] = [];

        foreach ($items as $id => $value) {
            $result['items'][] = [
                'id' => $id,
                'value' => $value,
            ];
        }

        return $result;
    }

}
