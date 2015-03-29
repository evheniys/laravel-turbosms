<?php
/**
 * laravel-turbosms.
 * autor: evheniys
 *
 */

namespace Evheniys\Turbosms;

use   SoapClient;

class Turbosms
{

    protected $client;
    protected $wsdl = 'http://turbosms.in.ua/api/wsdl.html';

    protected $app;
    protected $config;
    protected $lang;
    protected $code;

    protected $senderID;
    protected $message;

    public function __construct($app)
    {
        $this->app = $app;
        $locale = $app['config']['app.locale'];
        $this->lang = $app['translator']->get("turbosms::{$locale}");
        $this->config = $app['config']['turbosms'];
        $this->senderID = $this->config['default_sender'];
    }

    public function send($text, $phones)
    {
        if (!$this->config['debug'] || !$this->client) {
            $this->connect();
        }

        if (!is_array($phones)) {
            $phones = [$phones];
        }

        foreach ($phones as $phone) {
            $message = 'Сообщения успешно отправлено';
            if (!$this->config['debug']) {
                $result = $this->client->SendSMS([
                    'sender' => $this->config['sender'],
                    'destination' => $phone,
                    'text' => $text
                ]);

                if ($result->SendSMSResult->ResultArray[0] != 'Сообщения успешно отправлены') {
                    $message = 'Сообщения не отправлено (ошибка: "' . $result->SendSMSResult->ResultArray[0] . '")';
                    $sms['text'] = $text;
                    $sms['phone'] = $phone;
                    $sms['status'] = $message;

                    return $sms;
                }
                return true;
            }
        }
    }

    protected function connect()
    {
        if ($this->client) {
            return $this->client;
        }
        $client = new SoapClient($this->wsdl);
        if (!$this->config['login'] || !$this->config['password']) {
            return false;
        }
        $result = $client->Auth([
            'login' => $this->config['login'],
            'password' => $this->config['password'],
        ]);
        if ($result->AuthResult . '' != 'Вы успешно авторизировались') {
            return ($result->AuthResult);
        }

        $this->client = $client;

        return $this->client;
    }

    public function getBalance()
    {
        $result = $this->client->GetCreditBalance();
        return intval($result->GetCreditBalanceResult);
    }

    public function getMessageStatus($messageId)
    {
        $result = $this->client->GetMessageStatus(['MessageId' => $messageId]);
        return $result->GetMessageStatusResult;
    }

}