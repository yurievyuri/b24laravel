<?php
const NO_KEEP_STATISTIC = true;
const NOT_CHECK_PERMISSIONS = true;
//const BX_NO_ACCELERATOR_RESET = true;
//const BX_CRONTAB = true;
const STOP_STATISTICS = true;
const NO_AGENT_STATISTIC = "Y";
const DisableEventsCheck = true;
const NO_AGENT_CHECK = true;
const BX_WITH_ON_AFTER_EPILOG = true;
//const LOG_FILENAME = 'php://stderr';

if (empty($_SERVER["DOCUMENT_ROOT"])) {
    $_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . '/../../../');
}

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require_once($DOCUMENT_ROOT . "/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule("main");
\Bitrix\Main\Loader::includeModule("crm");
initBitrixCoreT();
function initBitrixCoreT()
{
    global $DB;
    $app = \Bitrix\Main\Application::getInstance();
    $con = $app->getConnection();
    $DB->db_Conn = $con->getResource();
    $_SESSION["SESS_AUTH"]["USER_ID"] = 1;
}