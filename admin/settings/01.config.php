<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$arTextStandart = ['text' , 50, 1 ];

/**
 * @var $module_id
 */
return [
    'DIV' => basename(__FILE__, '.php'),
    'TAB' => Loc::getMessage('DEV_LARABIT_MAIN_TAB'),
    'TITLE' => Loc::getMessage('DEV_LARABIT_MAIN_TITLE'),
    'OPTIONS' => [

        '<div class="header">' . Loc::getMessage('DEV_LARABIT_OPTIONS') . '</div>',

        [
            \Dev\Larabit\Option::CONF_ACTIVATE,
            Loc::getMessage('DEV_LARABIT_ACTIVATE'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_ACTIVATE) ?: 'N',
            ['checkbox']
        ],
        [
            \Dev\Larabit\Option::CONF_EXTERNAL_DOMAIN,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_DOMAIN'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_EXTERNAL_DOMAIN),
            $arTextStandart
        ],
        [
            \Dev\Larabit\Option::CONF_EXTERNAL_USER_NAME,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_NAME'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_EXTERNAL_USER_NAME),
            $arTextStandart
        ],
        [
            \Dev\Larabit\Option::CONF_EXTERNAL_USER_EMAIL,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_EMAIL'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_EXTERNAL_USER_EMAIL),
            $arTextStandart
        ],
        [
            \Dev\Larabit\Option::CONF_EXTERNAL_USER_PASSWORD,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_PASSWORD'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_EXTERNAL_USER_PASSWORD),
            $arTextStandart
        ],
        [
            \Dev\Larabit\Option::CONF_EXTERNAL_USER_TOKEN,
            Loc::getMessage('DEV_LARABIT_EXTERNAL_USER_TOKEN'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_EXTERNAL_USER_TOKEN),
            $arTextStandart
        ],
        [
            \Dev\Larabit\Option::CONF_HTTP_PROTOCOL,
            Loc::getMessage('DEV_LARABIT_HTTP_PROTOCOL'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_HTTP_PROTOCOL) ?: 'https',
            ['selectbox', ['https' => 'https', 'http' => 'http']]
        ],
        [
            \Dev\Larabit\Option::CONF_DISABLE_SSL_VERIFICATION,
            Loc::getMessage('DEV_LARABIT_DISABLE_SSL_VERIFICATION'),
            Option::get($module_id, \Dev\Larabit\Option::CONF_DISABLE_SSL_VERIFICATION) ?: 'N',
            ['checkbox']
        ],
    ]
];