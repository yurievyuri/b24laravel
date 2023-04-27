<?php

use Bitrix\Main\EventManager;
use Dev\Larabit\Handlers;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

class dev_larabit extends CModule
{
    private static $mainClass = '\\Dev\\Larabit\\Option';
    private static $handlerMethod = 'moduleOnProlog';

    public function __construct()
    {
        $this->getModuleClasses();

        $this->MODULE_VERSION = \Dev\Larabit\Option::getVersion();
        $this->MODULE_VERSION_DATE = \Dev\Larabit\Option::getDateRealease();
        $this->MODULE_ID = \Dev\Larabit\Option::getModuleId();
        $this->MODULE_NAME = Loc::getMessage('DEV_LARABIT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DEV_LARABIT_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('DEV_LARABIT_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('DEV_LARABIT_PARTNER_URI');
        Loader::includeModule('rest');
    }

    public function doInstall(): ?bool
    {
        \Dev\Larabit\Hooks::install();
        ModuleManager::registerModule($this->MODULE_ID);
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->registerEventHandler("main", "OnProlog", $this->MODULE_ID, Handlers::class, self::$handlerMethod);
        }
        return true;
    }

    public function doUninstall(): bool
    {
        \Dev\Larabit\Hooks::uninstall();
        $event = EventManager::getInstance();
        if (is_object($event)) {
            $event->unRegisterEventHandler('main', self::$handlerMethod, $this->MODULE_ID);
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    private function getModuleClasses(): void
    {
        if ( class_exists(self::$mainClass) ) return;
        require_once __DIR__ . './../include.php';
        if ( !is_countable($arClasses) ) return;
        foreach ($arClasses as $class => $path) {
            if ( !$path ) continue;
            require __DIR__  . './../'.$path;
        }
    }
}
