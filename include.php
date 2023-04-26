<?php
\Bitrix\Main\Loader::registerAutoLoadClasses('dev.larabit', [
    "\\Dev\\Larabit\\Handlers" => "lib/Handlers.php",
    "\\Dev\\Larabit\\Config" => "lib/Config.php",
    "\\Dev\\Larabit\\Agent" => "lib/Agent.php",
    "\\Dev\\Larabit\\Http" => "lib/Http.php",
    "\\Dev\\Larabit\\Option" => "lib/Option.php",
    "\\Dev\\Larabit\\Api\\Auth" => "lib/Api/Auth.php",
    "\\Dev\\Larabit\\Api\\Laravel" => "lib/Api/Laravel.php",
    "\\Dev\\Larabit\\Scope" => "lib/Scope.php",
]);