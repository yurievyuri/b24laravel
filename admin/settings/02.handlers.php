<?php
    use Bitrix\Main\Localization\Loc;

    Loc::loadMessages( __FILE__ );
    /**
     * checkbox
     * note
     * text
     * selectbox
     * multiselectbox
     *
     * @var $prefix
     * @var $module_id
     */

    $array = [
        'DIV' => basename( __FILE__, '.php' ),
        'TAB' => '🔌 Handlers',
        'TITLE' => '⚙️ Main Settings',
        'OPTIONS' => []
    ];

    /*try {
        \Dev\Handlers\Config::getAdminOptions( $array );
    } catch ( Throwable $e ) {
        echo '<pre style="background:orange">';print_r($e->getMessage());echo '</pre>';
    }*/

    return $array;