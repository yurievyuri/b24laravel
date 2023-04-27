<?php
use Dev\Larabit\Controller;
class External extends \Bitrix\Main\Engine\Controller
{
    public static function OnRestServiceBuildDescription(): array
    {
        return [
            \CRestUtil::GLOBAL_SCOPE => [
                \Dev\Larabit\Option::getModuleId() . '.custom' => [
                    'callback' => [self::class, 'custom'],
                    'params' => []
                ],
            ]
        ];
    }
}