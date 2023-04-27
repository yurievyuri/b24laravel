<?php
    use Bitrix\Main\Localization\Loc;
    Loc::loadMessages(__FILE__);
    $menu = array(
        array(
            'parent_menu'   => 'global_menu_services', //global_menu_content
            'sort'          => 5,
            'text'          => 'Larabit Connector',
            'title'         => 'Module Connector',
            'url'           => 'settings.php?lang=ru&mid='.\Dev\Larabit\Option::getModuleId().'&lang=' . LANGUAGE_ID,
            'icon'          => 'fileman_sticker_icon'
            //'items_id'      => 'dev.tools',
            /*'items'         => array(
                array(
                    'text' => 'Основные настройки',
                    'url' => 'settings.php?lang=ru&mid=dev.tools&lang=' . LANGUAGE_ID,
                    //'more_url' => array('settings.php?lang=ru&mid=dev.tools&lang=' . LANGUAGE_ID),
                    //'title' => Loc::getMessage('SUBMENU_TITLE'),
                ),
            ),*/
        ),
    );

    return $menu;