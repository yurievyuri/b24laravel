<?php

namespace Dev\Larabit;

use Bitrix\Main\HttpRequest;
use Http\Client\HttpClient;

final class Option
{
    const MODULE_ID = 'dev.larabit';
    const CONF_MODULE_ID = self::MODULE_ID;
    const CONF_USER_ID = 1;
    const CONF_EXTERNAL_USER_ID = 'external_user_id';
    const CONF_INBOUND_HOOK_ID = 'inbound_hook_id';
    const CONF_INBOUND_HOOK_PASSWORD = 'inbound_hook_password';
    const CONF_ACTIVATE = 'activate';
    const CONF_USE_AGENT = 'agent';
    const CONF_EXTERNAL_DOMAIN = 'external_domain';
    const CONF_EXTERNAL_USER_NAME = 'external_user_name';
    const CONF_EXTERNAL_USER_PASSWORD = 'external_user_password';
    const CONF_EXTERNAL_USER_EMAIL = 'external_user_email';
    const CONF_REGISTRATION_TOKEN = 'registration_token';
    const CONF_EXTERNAL_USER_TOKEN = 'external_user_token';
    const CONF_DISABLE_SSL_VERIFICATION = 'disable_ssl_verification';
    const CONF_HTTP_PROTOCOL = 'http_protocol';

    const HOOK_INBOUND = 'inbound';
    const HOOK_OUTBOUND = 'outbound';
    const HOOK_INTERNAL = 'internal';

    public static function isActive():bool
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_ACTIVATE) === 'Y';
    }
    public static function getExternalDomain(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_DOMAIN);
    }
    public static function getExternalUserName(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_USER_NAME);
    }
    public static function getExternalUserPassword(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_USER_PASSWORD);
    }
    public static function getExternalUserEmail(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_USER_EMAIL);
    }
    public static function getExternalUserToken(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_USER_TOKEN);
    }
    public static function setExternalUserToken(string $value = ''): void
    {
        \Bitrix\Main\Config\Option::set(self::MODULE_ID, self::CONF_EXTERNAL_USER_TOKEN, $value);
    }
    public static function setExternalUserId(int $userId = null): void
    {
        \Bitrix\Main\Config\Option::set(self::MODULE_ID, self::CONF_EXTERNAL_USER_ID, $userId);
    }
    public static function getExternalUserId(): ?int
    {
        return (int) \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_EXTERNAL_USER_ID);
    }
    public static function isDisableSslVerification(): bool
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_DISABLE_SSL_VERIFICATION) === 'Y';
    }
    public static function getHttpProtocol(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_HTTP_PROTOCOL) ?: 'http';
    }
    public static function getRegistrationToken(): string
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_REGISTRATION_TOKEN);
    }
    public static function getUseAgent(): bool
    {
        return \Bitrix\Main\Config\Option::get(self::MODULE_ID, self::CONF_USE_AGENT) === 'Y';
    }
    public static function setUseAgent(string $value = 'Y'): bool
    {
        return \Bitrix\Main\Config\Option::set(self::MODULE_ID, self::CONF_USE_AGENT, $value);
    }

    public static function getModuleId(): string
    {
        return self::MODULE_ID;
    }
    public static function getVersion(): string
    {
        return '1.0.0';
    }
    public static function getDateRealease(): string
    {
        return '2023-04-27';
    }

}