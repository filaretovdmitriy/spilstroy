<?php

namespace app\components\pays;

use app\models\Key;

/**
 * Не проверен!
 */
class RoboKassa
{

    static $test = true;
    static private $url = 'http://auth.robokassa.ru/Merchant/Index.aspx';

    const ANSWER_OK = 'OK';
    const ANSWER_NO = "bad sign\n";

    private $_login;
    private $_password1;
    private $_password2;
    private $_logCategorie = 'RoboKassa';

    public function __construct($login = '', $password1 = '', $password2 = '')
    {
        $this->_login = $login;
        $this->_password1 = $password1;
        $this->_password2 = $password2;
    }

    /**
     * Получает данные для аутентификации из ключницы
     * @param string $loginName - имя login в ключнице
     * @param string $password1Name - имя password1 в ключнице
     * @param string $password2Name - имя password2 в ключнице
     */
    public function getAuthenticationFromKeys($loginName = 'robokassa_login', $password1Name = 'robokassa_password_1', $password2Name = 'robokassa_password_2')
    {
        $this->_login = Key::getKeyValue($loginName);
        $this->_password1 = Key::getKeyValue($password1Name);
        $this->_password2 = Key::getKeyValue($password2Name);
    }

    /**
     * Форматирует цену для использования в хеше
     * @param number $price - итоговая стоимость заказа
     * @return string отформатированная цена для хеша робокассы
     */
    private function _formatPrice($price)
    {
        if (self::$test) {
            return number_format($price, 2, '.', '');
        } else {
            return number_format($price, 6, '.', '');
        }
    }

    /**
     * Генерирует хеш для робокассы, использующийся при переходе в оплату
     * @param integer $orderIndetificator - идентификатор заказа
     * @param number $price - итоговая стоимость заказа
     * @return string - хеш
     */
    public function generateSendSignature($orderIndetificator, $price)
    {
        $summ = $this->_formatPrice($price);
        return md5($this->_login . ":{$summ}:{$orderIndetificator}:" . $this->_password1);
    }

    /**
     * Генерирует хеш для робокассы, использующийся для проверки
     * @param integer $orderIndetificator - идентификатор заказа
     * @param number $price - итоговая стоимость заказа
     * @return string - хеш
     */
    private function generateCheckSignature($orderIndetificator, $price)
    {
        $summ = $this->_formatPrice($price);
        return md5($summ . ':' . $orderIndetificator . ':' . $this->_password2);
    }

    /**
     * Проверяет совпадают ли хеши
     * @param number $price - итоговая стоимость заказа
     * @return boolean - совпадают ли хеши
     */
    public function checkHash($price)
    {
        $signatureValue = \Yii::$app->request->getQuery('SignatureValue');
        $hash = $this->generateCheckSignature($this->getOrderId(), $price);
        return strtolower($signatureValue) === strtolower($hash);
    }

    /**
     * Получает идентификатор заказа, пришедший из робокассы
     * @return string - идентификатор заказа
     */
    public function getOrderId()
    {
        return \Yii::$app->request->getQuery('InvId');
    }

    /**
     * Возвращает положительный или отрицательный ответ по результату проверки хешей
     * @param number $price - итоговая стоимость заказа
     * @return string - результат проверки хешей
     */
    public function renderAnswer($price)
    {
        if ($this->checkHash($price)) {
            return $this->answerOK();
        } else {
            \Yii::warning('Хеши не совпали', $this->_logCategorie);
            return self::ANSWER_NO;
        }
    }

    /**
     * Генерирует положительный ответ на запрос робокассы
     * @return string - положительный ответ на запрос робокассы
     */
    public function answerOK()
    {
        return self::ANSWER_OK . $this->getOrderId();
    }

    /**
     * Генерирует url для оплаты
     * @param integer $orderIndetificator - идентификатор заказа
     * @param number $price - итоговая стоимость заказа
     * @param string $decsription - описание заказа
     * @return string - url для переадресации пользователя на страницу оплаты
     */
    public function getUrl($orderIndetificator, $price, $decsription = '')
    {
        $getString = '?MrchLogin=' . $this->_login;
        $getString .= '&OutSum=' . $this->_formatPrice($price);
        $getString .= '&InvId=' . $orderIndetificator;
        if (!empty($decsription)) {
            $getString .= '&Desc=';
        }
        $getString .= '&SignatureValue=' . $this->generateSendSignature($orderIndetificator, $price);

        return self::$url . $getString;
    }

}
