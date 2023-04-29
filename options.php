<?php
global $APPLICATION;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loc::loadMessages(__FILE__);
Loc::loadMessages($context->getServer()->getDocumentRoot() . "/bitrix/modules/main/options.php");

if ( !class_exists('\\Dev\\Larabit\\Handlers') ) {
    CAdminMessage::showMessage([
        "MESSAGE" => GetMessage("DEV_LARABIT_MISSING_MODULE"), "TYPE" => "ERROR",
    ]);
    return false;
}

$arFiles = glob(__DIR__ . "/admin/settings/*.php");
if (!$arFiles) {
    CAdminMessage::showMessage([
        "MESSAGE" => GetMessage("DEV_LARABIT_MISSING_SETTINGS"), "TYPE" => "ERROR",
    ]);
    return false;
}
foreach ($arFiles as $path) {
    if (basename($path, ".php") === 'before') {
        continue;
    }
    if (!file_exists($path)) {
        continue;
    }
    $aTabs[] = require $path;
}
if (empty ($aTabs[0]['OPTIONS'])) {
    CAdminMessage::showMessage([
        "MESSAGE" => GetMessage("DEV_LARABIT_MISSING_PRESETS"), "TYPE" => "ERROR",
    ]);
    return false;
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);
if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (!empty($restore)) {
        Option::delete($module_id);
        CAdminMessage::showMessage([
            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_RESTORED"), "TYPE" => "OK",
        ]);

    } else {
        CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));
    }
}
$tabControl->Begin();
?>
    <style>
        .adm-detail-content-table .ui-tile-selector-selector-wrap {
            max-width: 100%;
        }

        .adm-detail-content-table > tbody > .heading td {
            padding: 8px 30px 10px !important;
        }

        .ui-tile-selector-input {
            margin: 0;
            border-color: #959ea9;
            width: 99%;
            padding-left: 10px;
            font-size: 14px;
        }

        .adm-workarea .heading td {
            font-size: 13px !important;
            font-weight: 400 !important;
            background-color: #f5f9f9;
            border-top: 11px solid #F5F9F9;
            border-bottom: 11px solid #F5F9F9;
            color: #4b6267;
            text-align: left !important;
            text-shadow: 0 1px #fff;
            padding: 8px 4px 10px !important;
            margin-top: 5px !important;
        }

        .adm-workarea .heading .label {
            padding-bottom: 5px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: black !important;
        }

        #bx-admin-prefix table .heading td {
            padding-bottom: 5px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: black !important;
        }

        .adm-workarea div .header {
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            background: wheat;
            padding: 10px;
        }
    </style>
    <form
        action="<?php echo($APPLICATION->GetCurPage()); ?>?mid=<?php echo($module_id); ?>&lang=<?php echo(LANG); ?>"
        method="post">
        <?php
        foreach ($aTabs as $aTab) {
            if ($aTab["OPTIONS"]) {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        }
        $tabControl->Buttons();
        ?>
        <input type="submit" name="apply" value="<?= Loc::getMessage("MAIN_SAVE") ?>" class="adm-btn-save"/>
        <?php $registerValue = \Dev\Larabit\Option::getExternalUserToken() ? 'Unregister' : 'Register'; ?>
            <input type="submit" name="register" value="<?php echo $registerValue?>"/>
        <?php echo(bitrix_sessid_post()); ?>
    </form>

<?php
global $USER;
$arData = [];
$tabControl->End();
if (!$request->isPost() || !check_bitrix_sessid()) return false;
foreach ($aTabs as $aTab) {
    foreach ($aTab['OPTIONS'] as $configName => $arOption) {
        if (!is_array($arOption)) {
            unset($arOption);
        } else {
            if ($arOption[0]) {
                $configName = $arOption[0];
            }
        }
        if ($request['apply']) {
            if (!$configName) continue;
            $optionValue = $request->getPost($configName);
            if ($configName == 'switch_on' && $optionValue == '') {
                $optionValue = 'N';
            }
            $arData[$configName] = $optionValue;
            $setValue = is_array($optionValue)
                ? implode(',', $optionValue)
                : trim($optionValue);

            Option::set($module_id, $configName, $setValue);
            if (Option::get($module_id, $configName) == $setValue) {
                continue;
            }
            $GLOBALS['APPLICATION']->ThrowException("Failed to save $configName property correctly", 1);
        }
    }
}

// Register Created Hooks
$arValues = $request->getValues();
if ( isset($arValues['register']) && in_array(strtolower($arValues['register']),['register', 'unregister']) )
{
    $arData['auth.register'] = \Dev\Larabit\Api\Auth::register($arValues['register']);
    if ( strtolower($arValues['register']) === 'register' )
    {
        $arData['connection.register'] = \Dev\Larabit\Api\Controller::register('connection.register');
    }
}

if ($arData) {
    \CEventLog::Add([
            'SEVERITY' => \CEventLog::SEVERITY_NOTICE,
            'AUDIT_TYPE_ID' => 'SAVE_MODULE_OPTIONS',
            'ITEM_ID' => $module_id,
            'MODULE_ID' => $module_id,
            'USER_ID' => $USER->GetID(),
            'REQUEST_URL' => $request->getDecodedUri(),
            'USER_AGENT' => $request->getUserAgent(),
            'REMOTE_ADDR' => $request->getRemoteAddress(),
            'DESCRIPTION' => serialize($arData)
        ]
    );
}

LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id . '&lang=' . LANG . '&clear_cache=Y');