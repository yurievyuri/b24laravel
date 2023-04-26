<?php

namespace Dev\Larabit;

class Http
{
    protected function request(array $params = [], array $headers = [])
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

        $domain = Option::getHttpProtocol() . '://' . Option::getExternalDomain() . $this->path;
        $response = $ob->post($domain, $params, $skipContentTypeCharset = false);

        return json_decode($response, true);
    }
}