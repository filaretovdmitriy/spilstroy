<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use app\models\Key;

class Mailer
{

    /**
     * идентификатор параметра в базе, который содержит адреса
     */
    const PARAMETER_EMAILS_ID = 1;

    /**
     * Генерирует email адрес из текущего домена
     * @return string сгенерированный email
     */
    static function generateEmailByDomain()
    {
        $host = Yii::$app->urlManager->getHostInfo();
        $domainParse = preg_replace('/(^.*\/\/www\.)|(^.*\/\/)/', '', $host);
        return 'noanswer@' . $domainParse;
    }

    /**
     * Получает адреса из параметра указанного в self::PARAMETER_EMAILS_ID и разбивает их по запятой
     * @return array|string массив адресов или один адрес
     */
    static function getEmails()
    {
        $parameter = \app\models\Parameter::getValue(self::PARAMETER_EMAILS_ID);
        if (empty($parameter) === true) {
            return null;
        }
        if (is_array($parameter) === true) {
            $emails = [];
            foreach ($parameter as $value) {
                $emailsParse = str_replace(' ', '', $value);
                $emails[] = $value;
            }
            if (count($emails) == 1) {
                $parameter = array_shift($emails);
            }
        } else {
            $emailsParse = preg_replace('/(^,+)|(,+$)|( )/s', '', $parameter);
            $emails = explode(',', $emailsParse);
        }
        if (count($emails) > 1) {
            return $emails;
        } else {
            return trim($parameter);
        }
    }

    /**
     * Отправляет сообщение. Если email не передан, то будет произведен поиск в параметрах
     * @param array|string|null $emails адреса на которые будут отправлены сообщения
     * @param string $subject тема письма
     * @param string $textHtml HTML письма
     * @param array $attachs аттачи в виде ['filename' => [options]] или ['filename1', 'filename2']
     * @param boolean $multiple мультиотправка (получатель увидит только свой email)
     * @param string $view представление письма
     * @param array $parameters дополнительные параметры представления
     * @return boolean результат отправки письма
     */
    static function send($emails, $subject, $textHtml, $attachs = [], $multiple = true, $view = 'views/mail', $parameters = [])
    {

        if (is_null($emails)) {
            $emails = self::getEmails();
        }

        $emailFrom = Key::getKeyValue('emailFrom', false);
        if ($emailFrom === false || empty($emailFrom)) {
            $emailFrom = self::generateEmailByDomain();
        }

        if (!empty($textHtml)) {
            $parameters['content'] = $textHtml;
        }

        if ($multiple) {
            $emailsBCC = Key::getGroup(7);
            if (!empty($emailsBCC)) {
                $emails = array_merge((array) $emails, array_keys((array) $emailsBCC));
            }
            return self::sendMultiple(self::getValidEmails(array_unique($emails)), $emailFrom, $subject, $attachs, $view, $parameters);
        }

        return self::sendSingle($emails, $emailFrom, $subject, $attachs, $view, $parameters);
    }

    /**
     * Проверка правильности email'ов
     * @param string|array $emails Адреса для проверки. Возможна как строка, так и массив адерсов в видах [0 => 'email', 1 => 'email2'] или ['email' => 'Ivanov', 'email2' => 'Petrov']
     * @return array массив валидных адресов
     */
    public static function getValidEmails($emails) {
        $emails = (array) $emails;
        $validator = new \yii\validators\EmailValidator();

        $validEmails = [];
        foreach ($emails as $key => $value) {

            $email = is_numeric($key) === true ? trim($value) : trim($key) ;
            if (empty($email) === true) {
                \Yii::warning('Попытка отправки на пустой адрес', 'emails');
                continue;
            }

            if ($validator->validate($email) === false) {
                \Yii::warning('Попытка отправки на НЕ валидный адрес - ' . $email, 'emails');
                continue;
            }

            if (is_numeric($key) === true) {
                $validEmails[] = $email;
            } else {
                $validEmails[$email] = $value;
            }
        }

        return $validEmails;
    }

    /**
     * Отправляет письмо
     * @param array|string $emails адреса на которые будут отправлены сообщения
     * @param string $emailFrom от кого
     * @param string $subject тема письма
     * @param array $attachs аттачи в виде ['filename' => [options]] или ['filename1', 'filename2']
     * @param string $view представление письма
     * @param array $parameters дополнительные параметры представления
     * @return boolean успешность оправки всех писем
     */
    private static function sendSingle($emails, $emailFrom, $subject, $attachs = [], $view = 'views/mail', $parameters = [])
    {
        $mailer = Yii::$app->mailer->compose($view, $parameters);
        if (is_array($emails)) {
            $emails = array_unique($emails);
        }
        $emailsBCC = Key::getGroup(7);
        if (empty($emailsBCC) === false) {
            $mailer->setBcc(self::getValidEmails(array_keys($emailsBCC)));
        }

        foreach ($attachs as $key => $attach) {
            if (is_array($attach)) {
                $mailer->attach($key, $attach);
            } else {
                $mailer->attach($attach);
            }
        }

        return $mailer->setFrom($emailFrom)->setTo(self::getValidEmails($emails))->setSubject($subject)->send();
    }

    /**
     * Отправляет письма мультиотправкой (не видно других адресатов)
     * @param array $emails адреса на которые будут отправлены сообщения
     * @param string $emailFrom от кого
     * @param string $subject тема письма
     * @param array $attachs аттачи в виде ['filename' => [options]] или ['filename1', 'filename2']
     * @param string $view представление письма
     * @return boolean успешность оправки всех писем
     */
    private static function sendMultiple($emails, $emailFrom, $subject, $attachs = [], $view = 'views/mail', $parameters = [])
    {
        $messages = [];

        foreach ($emails as $email) {
            $mailer = Yii::$app->mailer->compose($view, $parameters);

            foreach ($attachs as $key => $attach) {
                if (is_array($attach)) {
                    $mailer->attach($key, $attach);
                } else {
                    $mailer->attach($attach);
                }
            }

            $messages[] = $mailer->setFrom($emailFrom)->setTo($email)->setSubject($subject);
        }
        $countSuccess = \Yii::$app->mailer->sendMultiple($messages);
        return $countSuccess === count($emails);
    }

    /**
     * Отправляет уведомление о регистрации пользователя
     * @param integer $userId - идентификатор пользователя, которму будет отправлено сообщение
     * @return boolean результат отправки письма
     */
    static function registration($userId)
    {
        $user = \app\models\User::findOne($userId);
        $message = $user->login . ", Вы успешно зарегистрировались на сайте " . Yii::$app->name;
        return self::send($user->email, 'Успешная регистрация на сайте ' . Yii::$app->name, $message);
    }

    /**
     * Отправляет уведомление о восстановлении пользователя
     * @param string $login - логин пользователя
     * @param string $email - email пользователя
     * @return boolean результат отправки письма
     */
    static function lostPassword($login, $email, $newPassword)
    {
        $message = $login . ", Ваш новый пароль на сайте " . Yii::$app->name . ": " . $newPassword;
        return self::send($email, 'Новый пароль на сайте ' . Yii::$app->name, $message);
    }

    /**
     * Отправляет уведомление пользователю о смене статуса заказа
     * @param integer $orderId - идентификатор заказа
     * @param string $email - email пользователя
     * @param integer $newStatusId - идентификатор статуса
     * @return boolean результат отправки письма
     */
    static function orderStatusChange($orderId, $email, $newStatusId)
    {
        $statusName = \app\models\CatalogOrderStatus::getStatuses()[$newStatusId];
        $message = '<p>Заказу №' . $orderId . ' присвоен статус "' . $statusName . '"</p>';
        return self::send($email, 'Статус заказа ' . $orderId . ' на сайте ' . Yii::$app->name, $message);
    }

    /**
     * Отправляе уведомление о новом заказе
     * @param \app\models\CatalogOrder $order - заказ пользователя
     * @return boolean результат отправки письма
     */
    static function orderSend(&$order)
    {
        $message = "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
                <tr>
                    <td style='text-align:center'>Код товара на сайте</td>
                    <td style='text-align:center' colspan='2'>Наименование</td>
                    <td style='text-align:center'>Кол-во</td>
                    <td style='text-align:center'>Цена за ед. (руб.)</td>
                    <td style='text-align:center'>Общая цена</td>
                </tr>";

        $items = $order->getGoods();
        foreach ($items as $item) {
            $message .= "<tr align='center' style ='padding-top:10px;'>
                <td style='text-align:center'>{$item->id}</td>";
            $message .= "<td style ='padding-top:10px; padding-bottom:10px; text-align:left' " . (empty($item->sku) ? 'colspan=2' : '') . ">";
            $message .= $item->name;
            $message .= (!empty($item->article) ? "<br>арт. {$item->article}" : '');
            $message .= "</td>";
            if (empty($item->sku) === false) {
                $message .= '<td style="text-align: left">';
                foreach ($item->sku as $sku) {
                    $message .= "<strong>{$sku['name']}</strong>: {$sku['value']}<br>";
                }
                $message .= '</td>';
            }
            $message .= "<td style='text-align:center'>{$item->quant}</td>
                <td style='text-align:center'>{$item->price}</td>
                <td style='text-align:center'>{$item->summ}</td></tr>";
        }
        $message .= "</table>";

        $message .= "<p>Итог: " . number_format($order->total_price, 2, '.', ' ') . " руб.</p>";
        $message .= "<p><strong>Контактные данные</strong></p>";
        $message .= "<p><strong>ФИО:</strong> {$order->user_name}</p>";
        $message .= "<p><strong>Email:</strong> {$order->user_email}</p>";
        $message .= "<p><strong>Телефон:</strong> {$order->user_phone}</p>";
        $delivery = $order->delivery;
        $message .= "<p><strong>Способ доставки:</strong> {$delivery->name}" . ($delivery->price !== 0 ? " ({$delivery->price} руб.)" : '') . "</p>";
        if ($delivery->have_address) {
            $message .= "<p><strong>Город:</strong> {$order->user_city}</p>";
            $message .= "<p><strong>Улица:</strong> {$order->user_street}</p>";
            if (!empty($order->user_home)) {
                $message .= "<p><strong>Дом:</strong> {$order->user_home}</p>";
            }
        }
        $pay = $order->pay;
        $message .= "<p><strong>Способ оплаты:</strong> {$pay->name}</p>";
        if (!empty($order->comment)) {
            $message .= "<p>{$order->comment}</p>";
        }

        return self::send(null, 'Заказ на сайте ' . Yii::$app->name . ' с кодом ' . $order->id, $message);
    }

}
