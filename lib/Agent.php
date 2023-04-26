<?php

namespace Dev\Larabit;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class Agent
{
    private const AGENT_MAX_LIMIT_DAYS = 15;

    /**
     * Update agents with removal
     *
     * @return bool|mixed
     * @throws Exception
     */
    public static function create(array $pref = [], bool $delete = false)
    {
        $existId = null;
        if (!$pref) return false;
        if (!$pref['NAME']) return false;
        if (!isset($pref['MODULE_ID'])) {
            $pref['MODULE_ID'] = \Dev\Larabit\Handlers::MODULE_ID;
        }
        if ($pref['PERIOD'] && !isset($pref['IS_PERIOD'])) {
            $pref['IS_PERIOD'] = $pref['PERIOD'];
        }
        if ($delete) {
            $existId = self::isExist($pref['NAME']);
        }
        if ($existId > 0 && $delete === false) return false;
        if ($existId > 0) {
            CAllAgent::Delete($existId);
        }
        if ($pref['DATE_EXEC']) {
            $pref['NEXT_EXEC'] = $pref['DATE_EXEC'];
        }

        // fix incredible dates
        $pref['NEXT_EXEC'] = self::checkDate($pref['NEXT_EXEC']);

        $id = CAgent::AddAgent(
            $pref['NAME'],
            $pref['MODULE_ID'],
            $pref['IS_PERIOD'] ?: 'N',
            $pref['INTERVAL'] ?: 0,
            $pref['DATE_CHECK'],
            $pref['ACTIVE'] ?: "Y",
            $pref['NEXT_EXEC'],
            $pref['SORT'] ?: 300,
            (int) $pref['USER_ID'] > 0 ?: ''
        );

        if ($id > 0 && $pref['NEXT_EXEC']) {
            try {
                self::updateAgentDateNextExecDirect($id, $pref['NEXT_EXEC']);
            } catch (Throwable $e) {
            }
        }

        return $id;
    }

    private static function checkDate($date = null)
    {
        $strNow = (new DateTime)->add('T60S')->format(self::defaultDateTimeFormat());
        if (!$date) return $strNow;

        if ($date instanceof DateTime) {
            $date = DateTime::createFromUserTime($date)->format(self::defaultDateTimeFormat());
        }
        if ($date instanceof \DateTime) {
            $date = DateTime::createFromUserTime($date)->format(self::defaultDateTimeFormat());
        }

        $uNow = date('U');
        $uDate = strtotime((string) $date);

        if (($uDate - $uNow) < 0) return $date;
        if (($uDate - $uNow) > (60 * 60 * 24 * self::AGENT_MAX_LIMIT_DAYS)) {
            return $strNow;
        }

        return $date;
    }

    private static function getAgentDateNextExec($agentId)
    {
        return CAgent::GetById($agentId)->Fetch()['NEXT_EXEC'];
    }

    /**
     * @throws Exception
     */
    private static function updateAgentDateNextExecDirect($id, $date): bool
    {
        global $DB;
        $date = (new \DateTime($date, new DateTimeZone()))->format('Y-m-d H:i:s');
        $strUpdate = "`NEXT_EXEC` = '" . $date . "'";
        $strSql = 'UPDATE b_agent SET ' . $strUpdate . ' WHERE ID=' . $id;
        $DB->Query($strSql, false, 'FILE: ' . __FILE__ . '<br> LINE: ' . __LINE__);
        $bdDate = self::getAgentDateNextExec($id);
        if (strtotime((string) $bdDate) === strtotime($date)) return true;
        return false;
    }

    private static function isExist($name = null): int
    {
        if (!$name) return false;
        return (int) CAllAgent::GetList([], ['=NAME' => $name])->Fetch()['ID'] ?: 0;
    }

    private static function defaultDateTimeFormat(): string
    {
        global $DB;
        return $DB->DateFormatToPHP(CSite::GetDateFormat());
    }
}