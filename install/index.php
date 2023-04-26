<?php

use Bitrix\Main\EventManager;
use Dev\Larabit\Handlers;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class dev_larabit extends CModule
{
    private static $handlerMethod = 'moduleOnProlog';
    public function __construct()
    {
        $this->MODULE_VERSION = '1.0.0';
        $this->MODULE_VERSION_DATE = '25.04.2023';
        $this->MODULE_ID = self::getModuleId();
        $this->MODULE_NAME = 'Larabit';
        $this->MODULE_DESCRIPTION = 'Laravel Connector Module';
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'Yuri Yurev';
        $this->PARTNER_URI = 'mailto:yurievyuri@live.com';
    }

    public static function getModuleId(): string
    {
        return str_ireplace('_', '.', __CLASS__);
    }

    public function doInstall(): ?bool
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->registerEventHandler("main", "OnProlog", $this->MODULE_ID, Handlers::class, self::$handlerMethod);
        }
        return true;
    }

    public function doUninstall(): bool
    {
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->unRegisterEventHandler('main', self::$handlerMethod, $this->MODULE_ID);
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

}
