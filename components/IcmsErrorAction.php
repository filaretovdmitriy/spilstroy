<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\UserException;
use yii\helpers\Html;

class IcmsErrorAction extends Action
{

    public $view;
    public $layout;
    public $defaultName;
    public $defaultMessage;
    public $defaultGuestRedirect = '/icms';

    public function run()
    {
        if (!empty($this->layout)) {
            $this->controller->layout = $this->layout;
        }
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->controller->redirect($this->defaultGuestRedirect);
        }

        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new HttpException(404, Yii::t('yii', 'Страница не найдена.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'Ошибка сервера.');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            $exceptionText = '';

            if (YII_DEBUG) {
                $exceptionText = Html::tag('h2', 'Stack:');
                $exceptionText .= Html::tag('pre', Yii::$app->errorHandler->exception);
            }

            Yii::$app->view->params['breadCrumbs']['crumbs'] = [
                ['url' => '/icms', 'title' => 'Ошибка'],
                ['title' => $name],
            ];

            return $this->controller->render($this->view ?: $this->id, [
                        'name' => $name,
                        'message' => $message,
                        'exception' => $exception,
                        'exceptionText' => $exceptionText
            ]);
        }
    }

}
