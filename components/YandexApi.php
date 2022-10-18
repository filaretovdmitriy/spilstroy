<?php

namespace app\components;

class YandexApi
{

    private $client_id;
    private $client_secret;
    public $access_token;
    protected static $service = 'https://api-metrika.yandex.ru';

    public function __construct($token, $id = null, $secret = null)
    {
        $this->client_id = $id;
        $this->client_secret = $secret;
        $this->access_token = $token;
    }

    /**
     * @param string $code
     * @throws \Exception on fail
     */
    public function getTokenByCode($code)
    {
        $data = self::rawRequest('POST', 'https://oauth.yandex.ru/token', array(
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
        ));
        if (!$data) {
            throw new \Exception("Can't take access_token by application code");
        }
        $data = json_decode($data);
        if (isset($data['error'])) {
            throw new \Exception("Shit happens: {$data['error']}");
        }
        if (!isset($data['access_token'])) {
            throw new \Exception("No errors, but token not send: " . \yii\helpers\VarDumper::dumpAsString($data));
        }
        return $data;
    }

    protected function request($method = 'GET', $url, $options = array())
    {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query(array('oauth_token' => $this->access_token));
        return json_decode(self::rawRequest($method, $url, $options), true);
    }

    /**
     * Send request with token
     * @param $url
     * @param array $options
     * @param string $method
     */
    protected static function rawRequest($method = 'GET', $url, $options = array())
    {
        //Default options for all requests
        $curlOpt = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => dirname(__FILE__) . '/cert/cacert.pem',
            CURLOPT_CAPATH => dirname(__FILE__) . '/cert',
//            CURLOPT_CAINFO => dirname(__FILE__) . '/cert/solid-cert.crt',
        );

        switch (strtoupper($method)) {
            case 'DELETE':
                $curlOpt[CURLOPT_CUSTOMREQUEST] = "DELETE";
            case 'GET':
                if (!empty($options)) {
                    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($options);
                }
                break;
            case 'PUT':
                $body = http_build_query($options);
                $fp = fopen('php://temp/maxmemory:256000', 'w');
                if (!$fp) {
                    throw new \Exception('Could not open temp memory data');
                }
                fwrite($fp, $body);
                fseek($fp, 0);
                $curlOpt[CURLOPT_PUT] = 1;
                $curlOpt[CURLOPT_BINARYTRANSFER] = 1;
                $curlOpt[CURLOPT_INFILE] = $fp; // file pointer
                $curlOpt[CURLOPT_INFILESIZE] = strlen($body);
                break;
            case 'POST':
                $curlOpt[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8;');
                $curlOpt[CURLOPT_POST] = true;
                $curlOpt[CURLOPT_POSTFIELDS] = http_build_query($options);
                break;
            default:
                throw new \Exception("Unsupported request method '$method'");
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOpt);
        $return = curl_exec($curl);
        $err_no = curl_errno($curl);
        if ($err_no === 0) {
            curl_close($curl);
            return $return;
        } else {
            $err_msg = curl_error($curl);
            curl_close($curl);
            throw new \Exception($err_msg, $err_no);
        }
    }

    /**
     * Список доступных счетчиков
     * GET /counters
     * @param string $type - simple, partner
     * @param string $permission - ownn, view, edit
     * @param string $ulogin
     * @param array $field - array of mirrors, goals, filters, operations, grants
     * @return mixed
     * @throws \Exception
     * @link http://api.yandex.ru/metrika/doc/ref/reference/get-counter-list.xml
     */
    public function getCounters($type = null, $permission = null, $ulogin = null, array $field = array())
    {
        if (!empty($type) && !in_array($type, array('simple', 'partner'))) {
            throw new \Exception("Unsupported type value: '$type'");
        } else {
            $options['type'] = $type;
        }
        if (!empty($permission) && !in_array($permission, array('own', 'view', 'edit'))) {
            throw new \Exception("Unsupported permission value: '$permission'");
        } else {
            $options['permission'] = $permission;
        }
        if (!empty($ulogin)) {
            $options['ulogin'] = $ulogin;
        }
        if (!empty($field)) {
            foreach ($field as $item) {
                if (!in_array($item, array('mirrors', 'goals', 'filters', 'operations', 'grants'))) {
                    throw new \Exception("Unsupported field value: '$item'");
                }
            }
            $options['field'] = implode(',', $field);
        }
        return $this->request('GET', self::$service . '/counters.json', $options);
    }

    /**
     * Отчет Посещаемость
     * GET /stat/traffic/summary
     * @param int $id - Идентификатор счетчика
     * @param int $goalId - Идентификатор цели счетчика для получения целевого отчета
     * @param timestamp $dateFrom - Дата начала периода выборки в формате YYYYMMDD
     * @param timestamp $dateTo - Дата окончания периода выборки в формате YYYYMMDD
     * @param string $group - day, week, month
     * @param int $perPage - Количество элементов на странице выдачи
     * @link http://api.yandex.ru/metrika/doc/ref/stat/traffic-summary.xml
     */
    public function statTrafficSummary($id, $goalId = null, $dateFrom = null, $dateTo = null, $group = null, $perPage = 100)
    {
        $options = array();
        if (!empty($id)) {
            $options['id'] = $id;
        }

        if (!empty($goalId)) {
            $options['goalId'] = $goalId;
        }

        if (!empty($dateFrom)) {
            $options['date1'] = $dateFrom;
        }

        if (!empty($dateTo)) {
            $options['date2'] = $dateTo;
        }

        if (!empty($group)) {
            $options['group'] = $group;
        }

        if (!empty($perPage)) {
            $options['perPage'] = $perPage;
        }

        return $this->request('GET', self::$service . '/stat/traffic/summary.json', $options);
    }

}
