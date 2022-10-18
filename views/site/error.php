<?php
/* @var $this app\components\View */
/* @var $exception \yii\web\HttpException|\Exception */
/* @var $handler \yii\web\ErrorHandler */

switch ($exception->statusCode) {
    case 403:
        $this->title = 'Доступ запрещен [ошибка 403]';
        break;
    case 404:
        $this->title = 'Страница не существует [ошибка 404]';
        break;
    case 500:
        $this->title = 'Ошибка сервера [ошибка 500]';
        break;
    default:
        $this->title = $exception->getName() . ' [error ' . $exception->statusCode . ']';
        break;
}

$message = $exception->getMessage();
?>

<h1><?= $this->title ?></h1>
<?php if (empty($message) === false) { ?>
    <p><?= $message ?></p>
<?php } ?>
<ul>
    <li>Вы перешли по ссылке с сайта, на котором была установлена неверная ссылка на наш сайт;</li>
    <li>Вы ошибочно набрали адрес сайта в строке браузера;</li>
    <li>Страница, которую Вы хотели посетить, была удалена или переименована.</li>
</ul>
<a href="/">Вернуться на главную страницу </a>