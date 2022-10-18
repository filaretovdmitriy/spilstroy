<?php

namespace app\components\pays;

use Yii;
use app\models\Key;

class SberBank
{

    /**
     * @var boolean переключатель тестового режима работы
     */
    static $test = true;

    /**
     * @var string боевой url
     */
    static private $url = 'https://securepayments.sberbank.ru/payment/rest/register.do';

    /**
     * @var string тестовый url
     */
    static private $urlTest = 'https://3dsec.sberbank.ru/payment/rest/register.do';

    /**
     * @var string код оплаты в банке
     */
    public $code = null;

    /**
     * @var integer|boolean код ошибки или false в случае, если ошибки не было
     */
    public $errorCode = false;

    /**
     * @var string|null текст ошибки или null в случае если ошибки не было
     */
    public $errorText = null;
    private $_apiUser;
    private $_apiPassword;

    /**
     * Получает url для отправки запроса
     * @return string url для отправки запроса
     */
    static private function getUrl()
    {
        return self::$test ? self::$urlTest : self::$url;
    }

    public function __construct($apiUser = '', $apiPassword = '')
    {
        $this->_apiUser = $apiUser;
        $this->_apiPassword = $apiPassword;

        if (empty($this->_apiUser) && empty($this->_apiPassword)) {
            $this->getAuthenticationFromKeys();
        }
    }

    /**
     * Получает данные для аутентификации из ключницы
     * @param string $apiUserName - имя apiUser в ключнице
     * @param string $apiPasswordName - имя apiPassword в ключнице
     */
    public function getAuthenticationFromKeys($apiUserName = 'sber_bank_api_user_name', $apiPasswordName = 'sber_bank_api_password')
    {
        $this->_apiUser = Key::getKeyValue($apiUserName);
        $this->_apiPassword = Key::getKeyValue($apiPasswordName);
    }

    /**
     * Регистрирует заказ в системе сбербанка
     * @param integer $orderId - идентификатор заказа
     * @param number $price - итоговая стоимость заказа
     * @param string $returnUrl - адрес на который будет отправлен пользователь после оплаты
     * @return string - url на который необходимо переадресовать пользователя
     */
    public function sendToBank($orderId, $price, $returnUrl)
    {
        if (is_double($price)) {
            $price *= 100;
        } else {
            $price = (int) $price * 100;
        }

        $post = http_build_query([
            'userName' => $this->_apiUser,
            'password' => $this->_apiPassword,
            'orderNumber' => $orderId,
            'amount' => $price,
            'returnUrl' => $returnUrl,
        ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::getUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $out = curl_exec($curl);
        $result = json_decode($out);
        curl_close($curl);

        // errorCode = 1 - значит, что заказ уже был однажды отправлен на оплату
        if (isset($result->errorCode) && $result->errorCode != 1) {
            $this->errorCode = $result->errorCode;
            $this->errorText = $result->errorMessage;
            Yii::error('Ошибка оплаты. Код:' . $this->errorCode . '. ' . $this->errorText, 'pays');
            return false;
        }

        $this->code = $result->orderId;

        return $result->formUrl;
    }

}
