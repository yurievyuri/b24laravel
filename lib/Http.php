<?php

namespace Dev\Larabit;

class Http
{
    protected array $response = [];
    protected string $method;

    public function request(array $params = [], array $headers = [])
    {
        $ob = (new \Bitrix\Main\Web\HttpClient)
            ->waitResponse(true)
            ->setTimeout(5);

        if (Option::isDisableSslVerification()) {
            $ob->disableSslVerification();
        }
        if (Option::getExternalUserToken()) {
            $ob->setHeader('Authorization', 'Bearer ' . Option::getExternalUserToken(), true);
        }

        $domain = Option::getHttpProtocol() . '://' . Option::getExternalDomain() . $this->getMethod();
        $response = $ob->post($domain, $params, $skipContentTypeCharset = false);
        $this->response = json_decode($response, true);

        return $this;
    }

    public function setMethod(string $method)
    {
        $method = str_replace('.', '/', $method);
        $this->method = strtolower($method);
        return $this;
    }

    public function getMethod(): string
    {
        return $this->path . ($this->method ? '/' . $this->method : '');
    }

    public function getResult()
    {
        return $this->response;
    }
    public function getResponse(string $key = null)
    {
        return $key ? $this->response[ $key ] : $this->response;
    }
    public function getData( string $key = null )
    {
        return $key ? $this->getResponse('data')[$key] : $this->getResponse('data');
    }
}