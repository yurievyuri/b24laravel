<?php
use Dev\Larabit\Controller;
class External extends \Bitrix\Main\Engine\Controller
{
    public static function OnRestServiceBuildDescription(): array
    {
        return [
            \CRestUtil::GLOBAL_SCOPE => [
                'dev.larabit.custom' => [
                    'callback' => [self::class, 'custom'],
                    'params' => []
                ],
            ]
        ];
    }
}