<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Dev\Larabit\Option as LarabitOption;

Loc::loadMessages(__FILE__);
$arTextStandart = ['text', 50, 1];

/**
 * @var $module_id
 */
$ar = [
    'DIV' => basename(__FILE__, '.php'),
    'TAB' => Loc::getMessage('DEV_LARABIT_MAIN_TAB'),
    'TITLE' => Loc::getMessage('DEV_LARABIT_MAIN_TITLE'),
    'OPTIONS' => [

        '<div class="header">' . Loc::getMessage('DEV_LARABIT_OPTIONS') . '</div>',

        LarabitOption::CONF_ACTIVATE => [
            LarabitOption::CONF_ACTIVATE,
            Loc::getMessage('DEV_LARABIT_ACTIVATE'),
            Option::get($module_id, LarabitOption::CONF_ACTIVATE) ?: 'N',
            ['checkbox']
        ],

        LarabitOption::CONF_USE_AGENT => [
            LarabitOption::CONF_USE_AGENT,
            Loc::getMessage('DEV_LARABIT_USE_AGENT'),
            Option::get($module_id, LarabitOption::CONF_USE_AGENT) ?: 'Y',
            ['checkbox']
        ],

        LarabitOption::CONF_EXTERNAL_DOMAIN => [
            LarabitOption::CONF_EXTERNAL_DOMAIN,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_DOMAIN'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_DOMAIN),
            $arTextStandart
        ],
        LarabitOption::CONF_EXTERNAL_API_PREFIX => [
            LarabitOption::CONF_EXTERNAL_API_PREFIX,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_API_PREFIX'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_API_PREFIX) ?: LarabitOption::getExternalApiPrefix(),
            $arTextStandart
        ],
        LarabitOption::CONF_EXTERNAL_USER_NAME => [
            LarabitOption::CONF_EXTERNAL_USER_NAME,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_NAME'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_USER_NAME),
            $arTextStandart
        ],
        LarabitOption::CONF_EXTERNAL_USER_EMAIL => [
            LarabitOption::CONF_EXTERNAL_USER_EMAIL,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_EMAIL'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_USER_EMAIL),
            $arTextStandart
        ],
        LarabitOption::CONF_EXTERNAL_USER_PASSWORD => [
            LarabitOption::CONF_EXTERNAL_USER_PASSWORD,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_PASSWORD'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_USER_PASSWORD),
            $arTextStandart
        ],
        LarabitOption::CONF_REGISTRATION_TOKEN => [
            LarabitOption::CONF_REGISTRATION_TOKEN,
            Loc::getMessage('DEV_LARABIT_REGISTRATION_TOKEN'),
            Option::get($module_id, LarabitOption::CONF_REGISTRATION_TOKEN),
            $arTextStandart
        ],
        LarabitOption::CONF_EXTERNAL_USER_TOKEN => [
            LarabitOption::CONF_EXTERNAL_USER_TOKEN,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_TOKEN'),
            Option::get($module_id, LarabitOption::CONF_EXTERNAL_USER_TOKEN),
            $arTextStandart
        ],
        LarabitOption::CONF_HTTP_PROTOCOL => [
            LarabitOption::CONF_HTTP_PROTOCOL,
            Loc::getMessage('DEV_LARABIT_HTTP_PROTOCOL'),
            Option::get($module_id, LarabitOption::CONF_HTTP_PROTOCOL) ?: 'https',
            ['selectbox', ['https' => 'https', 'http' => 'http']]
        ],
        LarabitOption::CONF_DISABLE_SSL_VERIFICATION => [
            LarabitOption::CONF_DISABLE_SSL_VERIFICATION,
            Loc::getMessage('DEV_LARABIT_DISABLE_SSL_VERIFICATION'),
            Option::get($module_id, LarabitOption::CONF_DISABLE_SSL_VERIFICATION) ?: 'N',
            ['checkbox']
        ],
    ]
];

if (LarabitOption::getExternalUserToken()) {
    unset($ar['OPTIONS'][LarabitOption::CONF_REGISTRATION_TOKEN]);
} else {
    unset($ar['OPTIONS'][LarabitOption::CONF_EXTERNAL_USER_TOKEN]);
}

return $ar;
