<?php

use Bitrix\Main\EventManager;
use Bitrix\Rest\APAuth\PasswordTable;
use Bitrix\Rest\APAuth\PermissionTable;
use Bitrix\Rest\Preset\IntegrationTable;
use Dev\Larabit\Handlers;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class dev_larabit extends CModule
{
    private const CONF_INBOUND_HOOK_ID = 'inbound_hook_id';
    private const CONF_INBOUND_HOOK_PASSWORD = 'inbound_hook_password';
    private static $handlerMethod = 'moduleOnProlog';

    public function __construct()
    {
        $this->MODULE_VERSION = '1.0.0';
        $this->MODULE_VERSION_DATE = '25.04.2023';
        $this->MODULE_ID = self::getModuleId();
        $this->MODULE_NAME = Loc::getMessage('DEV_LARABIT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DEV_LARABIT_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('DEV_LARABIT_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('DEV_LARABIT_PARTNER_URI');

        xdebug_break();
        $dir = __DIR__ . '../include.php';

        if ( !class_exists('\\Dev\\Larabit\\Option') ){
            include __DIR__ . '../include.php';
        }

        Loader::includeModule('rest');

    }

    public static function getModuleId(): string
    {
        return str_ireplace('_', '.', __CLASS__);
    }

    public function doInstall(): ?bool
    {
        $this->inboundHookInstall();
        ModuleManager::registerModule($this->MODULE_ID);
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->registerEventHandler("main", "OnProlog", $this->MODULE_ID, Handlers::class, self::$handlerMethod);
        }
        return true;
    }

    public function doUninstall(): bool
    {
        $this->inboundHookUninstall();
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->unRegisterEventHandler('main', self::$handlerMethod, $this->MODULE_ID);
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    private function inboundHookInstall()
    {
        $userId = 1;
        $title = Loc::getMessage('DEV_LARABIT_MODULE_NAME');
        $comment = Loc::getMessage('DEV_LARABIT_MODULE_DESCRIPTION');
        $password = \Bitrix\Main\Security\Random::getString(16);
        $arScope = \Dev\Larabit\Scope::get();

        $res = PasswordTable::add([
            'USER_ID' => $userId,
            'PASSWORD' => $password,
            'ACTIVE' => 'Y',
            'TITLE' => $title,
            'COMMENT' => $comment,
            'DATE_CREATE' => new \Bitrix\Main\Type\DateTime(),
            //'DATE_LOGIN' => new \Bitrix\Main\Type\DateTime(),
            //'LAST_IP' => ''
        ]);

        if (!$res->isSuccess()) {
            throw new \Exception(implode(', ', $res->getErrorMessages()));
        }

        $passwordId = $res->getId();

        // make arscope array with data
        foreach ($arScope as $item) {
            $arScopeNew[] = array(
                'PASSWORD_ID' => $passwordId,
                'PERM' => $item
            );
        }
        if ($arScopeNew) {
            $res = PermissionTable::addMulti($arScopeNew)->isSuccess();
        }

        $res = IntegrationTable::update(
            (int) IntegrationTable::getList(['filter' => ['=PASSWORD_ID' => $passwordId], 'select' => ['ID']])->fetch()['ID'],
            [
                'USER_ID' => $userId,
                'ELEMENT_CODE' => \Bitrix\Rest\Preset\Data\Element::DEFAULT_IN_WEBHOOK,
                'TITLE' => $title,
                //'PASSWORD_ID' => $passwordId,
                //'APP_ID' => null,
                'SCOPE' => $arScope,
                //'QUERY' => [],
                //'OUTGOING_EVENTS' => ,
                'OUTGOING_NEEDED' => 'N',
                //'OUTGOING_HANDLER_URL',
                'WIDGET_NEEDED' => 'N',
                //'WIDGET_HANDLER_URL',
                //'WIDGET_LIST',
                'APPLICATION_TOKEN' => \Bitrix\Main\Security\Random::getString(32),
                'APPLICATION_NEEDED' => 'N',
                'APPLICATION_ONLY_API' => 'N',
                //'BOT_ID',
                //'BOT_HANDLER_URL'
            ]);

        if (!$res->isSuccess()) {
            throw new \Exception(implode(', ', $res->getErrorMessages()));
        }

        \Bitrix\Main\Config\Option::set($this->MODULE_ID, \Dev\Larabit\Option::CONF_INBOUND_HOOK_ID, $passwordId);
        \Bitrix\Main\Config\Option::set($this->MODULE_ID, \Dev\Larabit\Option::CONF_INBOUND_HOOK_PASSWORD, $password);
    }

    public function inboundHookUninstall()
    {
        $passwordId = (int) \Bitrix\Main\Config\Option::get($this->MODULE_ID, \Dev\Larabit\Option::CONF_INBOUND_HOOK_ID);
        if ( !$passwordId ) return;

        PasswordTable::delete($passwordId);

        $db = PermissionTable::getList(['filter' => ['=PASSWORD_ID' => $passwordId], 'select' => ['ID']]);
        while( $list = $db->fetch() )
        {
            PermissionTable::add($list['ID']);
        }

        $integrId = (int) IntegrationTable::getList(['filter' => ['=PASSWORD_ID' => $passwordId], 'select' => ['ID']])->fetch()['ID'];
        if ( !$integrId ) return;
        IntegrationTable::delete($integrId);
    }

}
